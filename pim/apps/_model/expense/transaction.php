<?php 

namespace model\expense;
use db, model, session;

class transaction extends \Origami
{ 
	protected $table = 'purchase_requisition';
	protected $primary = 'purchaseRequisitionId';
	
	public function getPRList($allSiteID = null,$level = null)
	{
		if ($level == \model\user\user::LEVEL_CLUSTERLEAD) {
			db::where('siteID IN (' . implode(',', array_map('intval', $allSiteID)) . ')');

		} elseif ($level == \model\user\user::LEVEL_SITEMANAGER)  {

			db::where('siteID',$allSiteID);
		}

		db::from("purchase_requisition");
		
		return db::get()->result();
	}

	public function getRLList($allSiteID = null,$level = null)
	{
		if ($level == \model\user\user::LEVEL_CLUSTERLEAD) {
			db::where('siteID IN (' . implode(',', array_map('intval', $allSiteID)) . ')');
		}  elseif ($level == \model\user\user::LEVEL_SITEMANAGER)  {

			db::where('siteID',$allSiteID);
		}
	
		db::from("purchase_requisition");
		db::where("purchase_requisition.purchaseRequisitionNumber != ''");
	//	db::join("purchase_requisition_file", "purchase_requisition_file.purchaseRequisitionId = purchase_requisition.purchaseRequisitionId");
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

		db::insert("purchase_requisition_detail",$data);
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
		} else {
			$log = $this->setLog($id,"");
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
		return array('1' => "Collection Money",
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

		// str_replace("world","Peter","Hello world!");

		//Array ( [0] => 170 [1] => 6 [2] => 1-3 [3] => 33-11 [4] => x [5] => )
//
		//$opsManager = model::load('user/user')->getUsersByID($pieces[0]);

			//echo "--".$pieces[1];

		if ($pieces[2] != "x") { $q = 1; } else { $q = 0; }
		if ($pieces[3] != "x") { $p = 1; } else { $p = 0; }
		if ($pieces[4] != "x") { $t = 1; } else { $t = 0; }


		
	/*	$pieces[0] edit item $pieces[1] quantity $pieces[2]
										price $pieces[3]
										total $pieces[4]		*/	
		

//170:1:1-3:33-11:x: 170:2:x:66-44:66-44: 170:3:x:22-10:22-20: 
		return array('1' => $q,
					 '2' => $p,
					 '3' => $t );
	}
}	

?>