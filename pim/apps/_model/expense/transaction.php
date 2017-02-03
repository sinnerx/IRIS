<?php 

namespace model\expense;
use db, model, session, pagination, url;

class transaction extends \Origami
{ 
	protected $table = 'purchase_requisition';
	protected $primary = 'purchaseRequisitionId';
	
	public function getPRList($allSiteID = null,$level = null,$page)
	{
		if ($level == \model\user\user::LEVEL_CLUSTERLEAD) {
			db::where('siteID IN (' . implode(',', array_map('intval', $allSiteID)) . ')');

		} elseif ($level == \model\user\user::LEVEL_SITEMANAGER)  {

			db::where('siteID',$allSiteID);
		}

		db::from("purchase_requisition");
				pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
				pagination::initiate(Array(
								"totalRow"=>db::num_rows(),
								"limit"=>5,				
								"urlFormat"=>url::base("expense/listStatus/{page}"),
								"currentPage"=>$page
										));

		db::limit(pagination::get("limit"),pagination::recordNo()-1);
		return db::get()->result();
	}

	public function getRLList($allSiteID = null,$level = null, $page, $prNumber = null)
	{
		if ($level == \model\user\user::LEVEL_CLUSTERLEAD) {
			db::where('siteID IN (' . implode(',', array_map('intval', $allSiteID)) . ')');
		}  elseif ($level == \model\user\user::LEVEL_SITEMANAGER)  {

			db::where('siteID',$allSiteID);
		}
	
		db::from("purchase_requisition");

		if ($prNumber != null) {

			db::where("purchase_requisition.purchaseRequisitionNumber LIKE '$prNumber'");
		} else {

			db::where("purchase_requisition.purchaseRequisitionNumber != ''");
		}
				pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
				pagination::initiate(Array(
								"totalRow"=>db::num_rows(),
								"limit"=>5,				
								"urlFormat"=>url::base("expense/listStatusRL/{page}"),
								"currentPage"=>$page
										));

		db::limit(pagination::get("limit"),pagination::recordNo()-1);		
		
		return db::get()->result();
	}

	public function getFileList($siteID,$year,$month)
	{
		$where	= Array(
				"purchase_requisition.siteID"=>$siteID,								
				"purchase_requisition_file.purchaseRequisitionFileStatus"=>1,
				"year(purchaseRequisitionDate)" => $year,
				"month(purchaseRequisitionDate)" => $month 
						);
		db::from("purchase_requisition");
		db::where($where);
		db::join("purchase_requisition_file", "purchase_requisition_file.purchaseRequisitionId = purchase_requisition.purchaseRequisitionId");
		db::join("purchase_requisition_category", "purchase_requisition_category.purchaseRequisitionCategoryId = purchase_requisition_file.purchaseRequisitionCategoryId");
		
		return db::get()->result();
	}

	public function getTotalAmount($siteID,$year,$month)
	{
		$where	= Array(
				"purchase_requisition.siteID"=>$siteID,								
				"purchase_requisition_file.purchaseRequisitionFileStatus"=>1,
				"year(purchaseRequisitionDate)" => $year,
				"month(purchaseRequisitionDate)" => $month 
						);
		db::select("sum(purchase_requisition_file.purchaseRequisitionFileTotal) as totalAmount");
		db::from("purchase_requisition");
		db::where($where);
		db::join("purchase_requisition_file", "purchase_requisition_file.purchaseRequisitionId = purchase_requisition.purchaseRequisitionId");
		db::join("purchase_requisition_category", "purchase_requisition_category.purchaseRequisitionCategoryId = purchase_requisition_file.purchaseRequisitionCategoryId");

		return db::get()->row();
	}

	//getRLSummary
	public function getRLSummary($siteID,$year,$month)
	{
		$where	= Array(
				"purchase_requisition.siteID"=>$siteID,								
				"purchase_requisition_file.purchaseRequisitionFileStatus"=>1,
				"year(purchase_requisition.purchaseRequisitionDate)" => $year,
				"month(purchase_requisition.purchaseRequisitionDate)" => $month 
						);

		db::select("*,purchase_requisition_file.purchaseRequisitionCategoryId, sum(purchase_requisition_file.purchaseRequisitionFileTotal) as amount ");
		db::from("purchase_requisition_file");
		db::where($where);

		db::join("purchase_requisition", "purchase_requisition.purchaseRequisitionId = purchase_requisition_file.purchaseRequisitionId");
		
		db::join("purchase_requisition_category", "purchase_requisition_category.purchaseRequisitionCategoryId = purchase_requisition_file.purchaseRequisitionCategoryId");
		db::group_by("purchase_requisition_file.purchaseRequisitionCategoryId");	

		return db::get()->result();
	}

	public function getPRId($siteID,$year,$month)
	{

		$where	= Array(
				"siteID"=>$siteID,								
				"year(purchaseRequisitionDate)" => $year,
				"month(purchaseRequisitionDate)" => $month 
						);

		db::from("purchase_requisition");
		db::where($where);

		return db::get()->result();
	}

	public function addTransaction($id,$itemList)
	{
		$data	= Array(

			"purchaseRequisitionId" => $id,
			"purchaseRequisitionDetailItemId" => $itemList['itemCategory'],
			"purchaseRequisitionDetailDescription" => $itemList['itemDescription'],
			"purchaseRequisitionDetailPrice" => $itemList['itemPrice'],
			"purchaseRequisitionDetailQuantity" => $itemList['itemQuantity'],
			"purchaseRequisitionDetailTotal" => $itemList['itemTotalPrice'],
			"purchaseRequisitionDetailRemark" => $itemList['itemRemark'],
			"purchaseRequisitionDetailCreatedDate" => now(),

						);

		if ($itemList['itemPrice'] != ""){
			db::insert("purchase_requisition_detail",$data);
		}
	}	

	public function updateTransaction($id,$itemList) # userid:itemid:quantity:price:total
	{
			
		$update = $this->getCompareTransaction($itemList);

			$changes = $test = "";

		if ($update[0][purchaseRequisitionDetailQuantity] != $itemList['itemQuantity']) {

			$changes = $changes.$update[0][purchaseRequisitionDetailQuantity]."-".$itemList['itemQuantity'].":";
		} else {
			$changes = $changes."x:";
		}	

		if ($update[0][purchaseRequisitionDetailPrice] != $itemList['itemPrice']) {

			$changes = $changes.$update[0][purchaseRequisitionDetailPrice]."-".$itemList['itemPrice'].":";			
		} else {
			$changes = $changes."x:";
		}

		if ($update[0][purchaseRequisitionDetailTotal] != $itemList['itemTotalPrice']) {

			$changes = $changes.$update[0][purchaseRequisitionDetailTotal]."-".$itemList['itemTotalPrice'].":";
		} else {

			$changes = $changes."x:";
		}

		/*if ($update[0][purchaseRequisitionDetailRemark] != $itemList['itemRemark']) {

			$changes = $changes."r ".$update[0][purchaseRequisitionDetailRemark]."-".$itemList['itemRemark'].":";
		}  */

		
		if (substr_count($changes, 'x') != 3){

			$changes = authData('user.userID').":".$update[0][purchaseRequisitionDetailId].":".$changes."|";

			$log = $this->setLog($id,authData('user.userID'));

			$data	= Array(

				"purchaseRequisitionDetailPrice" => $itemList['itemPrice'],
				"purchaseRequisitionDetailQuantity" => $itemList['itemQuantity'],
				"purchaseRequisitionDetailTotal" => $itemList['itemTotalPrice'],
				"purchaseRequisitionDetailRemark" => $itemList['itemRemark'],
				"purchaseRequisitionDetailEdit" => $changes,
				"purchaseRequisitionDetailUpdatedDate" => now(),

						);

			db::where('purchaseRequisitionDetailId', $itemList['prDetailId']);
			db::update("purchase_requisition_detail",$data);
		} 
	}

	public function getCompareTransaction($itemList)
	{
			
		db::from("purchase_requisition_detail");
		db::where('purchaseRequisitionDetailId', $itemList['prDetailId']);
		return db::get()->result();
	}

	public function setLog($id,$changes)
	{

		$data	= Array(

			"purchaseRequisitionUpdatedDate" => now(),
			"purchaseRequisitionRemark" => $changes			
		
						);

		db::where('purchaseRequisitionId', $id);
		db::update('purchase_requisition', $data);
		
	}

	public function getPrFile($prId)
	{

		db::from("purchase_requisition");
		db::where("purchase_requisition.purchaseRequisitionId",$prId);
		return db::get()->result();
	}

	public function getPrTerm()
	{
		return array(
					// '1' => "Collection Money",
					 '2' => "Cash Advance" );
	}

	public function checkLog($list)
	{	
		# userid:itemid:quantity:price:total
		# 170:6:2-1:111-20:222-20:

		$opsManager = model::load('user/user')->getUsersByID($list);		
		$check = "Edited by ".$opsManager[$list][userProfileFullName];

		return $check;
	}

	public function extractLog($list)
	{	
		# userid:itemid:quantity:price:total
		# 170:6:2-1:111-20:222-20:

		$pieces = explode(":", $list);

		if ($pieces[2] == "x") { $q = 1; } else { $q = 0; }
		if ($pieces[3] == "x") { $p = 1; } else { $p = 0; }
		if ($pieces[4] == "x") { $t = 1; } else { $t = 0; }

		return array('1' => $q,
					 '2' => $p,
					 '3' => $t );
	}
}	

?>