<?php 

namespace model\billing;
use db, session, pagination, model, url;

class billing extends \Origami
{ 
	protected $table = 'billing_item';
	protected $primary = 'billingItemID';
		
	## update table billing_item .
	public function updateItemInfo($id,$data)
	{
		$data	= Array(

				"billingItemHotkey"=>$data['hotKey'],
				"billingItemName"=>$data['itemName'],
				"billingItemDescription"=>$data['description'],
				"billingItemPrice"=>$data['price'],
				"billingItemUnit"=>1,
				"billingItemQuantity"=>1,
				"billingItemDescriptionDisabled"=>$data['descriptionDisabled'],
				"billingItemPriceDisabled"=>$data['priceDisabled'],
				"billingItemUnitDisabled"=>$data['unitDisabled'],
				"billingItemQuantityDisabled"=>$data['quantityDisabled'],
				"billingItemCreatedDate"=>now()

						);

		db::where("billingItemID",$id);
		db::update("billing_item",$data);
		
	}

	public function getItem()
	{
		db::from("billing_item");
		db::where("billingItemStatus",1);	
		db::order_by("billingItemHotkey","ASC");
		
		return db::get()->result();		
	}

	public function getAllHotkey()
	{
		db::select("billingItemID, billingItemHotkey");	
		db::where("billingItemStatus",1);	
		db::from("billing_item");

		return db::get()->result();		
	}

	/**
	 * TO BE REMOVED
	 */
	public function addTransaction($siteID,$id,$data_sales)
	{
		
		if ($data_sales['utilitiesList'] != ""){
			$description = $data_sales['utilitiesList']." = ".$data_sales['description1'];	

		} elseif ($data_sales['transferList'] != ""){
			$description = $data_sales['transferList'];	
		} else {
			$description = $data_sales['description'];
		}

		$todayDate = date('Y-m-d H:i', strtotime($data_sales['selectDate'])); 	
		$billing = model::orm('billing/billing')->find($id);

		if  ($billing->billingItemType == 2)	{
		 	$data_sales['total'] = 0 - $data_sales['total'];
		}

		if ($data_sales['type'] == 2){
			$accountType = 2;
		} else {
			$accountType = 1;
		}

		$data	= Array(

			"siteID" => $siteID,
			"userID" => session::get('userID'),
			"billingItemID" => $id,
			"billingTransactionQuantity" => $data_sales['quantity'],
			"billingTransactionUnit" => $data_sales['unit'],
			"billingTransactionTotal" => $data_sales['total'],
			"billingTransactionAccountType" => $accountType,
			"billingTransactionStatus" => 1,
			"billingTransactionDate" => $todayDate,
			"billingTransactionCreatedDate" => now(),
			"billingTransactionUpdatedDate" => now()

						);

		db::insert("billing_transaction",$data);

		$billingTransactionID = db::getLastID('billing_transaction', 'billingTransactionID', true);

		$data_item	= Array(

			"billingItemID" => $id,
			"billingTransactionID" => $billingTransactionID['billingTransactionID'],
			"billingTransactionItemDescription" => $description,
			"billingTransactionItemQuantity" => $data_sales['quantity'],
			"billingTransactionItemUnit" => $data_sales['unit'],
			"billingTransactionItemPrice" => $data_sales['total']
						);

		db::insert("billing_transaction_item",$data_item);

		return db::getLastID('billing_transaction', 'billingTransactionID', true);
	}

	public function getList($siteID,$limit = null)
	{
		if ($limit == null) {
			$limit = 5;
		}

		$where	= Array(
				"billing_transaction.siteID"=>$siteID,								
				"billingTransactionStatus" => 1
						);

		db::from("billing_transaction_item")->where($where);
		
		db::join("billing_transaction", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");


		db::order_by("billingTransactionDate","DESC");
		db::limit($limit);

		return db::get()->result();
	}


	public function getFinanceList($userID)
	{
		$where	= Array(
				"userID"=>$userID,								
				"billingTransactionStatus" => 1
						);

		db::from("billing_transaction");
		db::where($where);
		db::join("site", "site.siteID = billing_transaction.siteID");
		db::order_by("billingTransactionDate","DESC");
		
		return db::get()->result();
	}

	public function getTotalToday($siteID,$selectDate)
	{
		$selectDate = date('Y-m-d', strtotime($selectDate)); 		

		$where	= Array(
				"siteID"=>$siteID,				
				"billingTransactionDate like"=>$selectDate."%",
				 "billingTransactionStatus" => 1
						);
	
		db::from("billing_transaction")->where($where);		
		db::join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID");
		db::order_by("billingTransactionDate","ASC");
		
		return db::get()->result();
	}


	public function getAllList($siteID,$itemID,$selectDate)
	{
		$selectDate = date('Y-m-d', strtotime($selectDate)); 		

		if ($itemID == null) {

		$where	= Array(
				"siteID"=>$siteID,				
				"billingTransactionDate like"=>$selectDate."%",
				 "billingTransactionStatus" => 1
						);
		} else {

		$where	= Array(
				"siteID"=>$siteID,
				"billing_transaction.billingItemID"=>$itemID,
				"billingTransactionDate like"=>$selectDate."%",
				 "billingTransactionStatus" => 1
						);			
		}

		db::from("billing_transaction")->where($where);		
		db::join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID");
		db::order_by("billingTransactionDate","ASC");
		
		return db::get()->result();
	}

	public function getPaginationList($siteID,$itemID,$selectDate,$page )
	{
		$selectDate = date('Y-m-d', strtotime($selectDate)); 	

		if ($itemID == null) {

		$where	= Array(
				"siteID"=>$siteID,				
				"billingTransactionDate like"=>$selectDate."%",
				 "billingTransactionStatus" => 1
						);
		} else {

		$where	= Array(
				"siteID"=>$siteID,
				"billing_transaction_item.billingItemID"=>$itemID,
				"billingTransactionDate like"=>$selectDate."%",
				 "billingTransactionStatus" => 1
						);			
		}

		db::from("billing_transaction")->where($where);		

				pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
				pagination::initiate(Array(
								"totalRow"=>db::num_rows(),
								"limit"=>5,				
								"urlFormat"=>url::base("billing/edit/{page}?&siteID={$siteID}&selectDate={$selectDate}"),
								"currentPage"=>$page
										));

		db::limit(pagination::get("limit"),pagination::recordNo()-1); 

		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");

		db::order_by("billingTransactionDate","ASC");
				
		return db::get()->result();
	}	

	public function getHqTransaction()
	{		
		$where	= Array(
				"billing_transaction.billingItemID"=>15,			
				 "billingTransactionStatus" => 1
						);			
		
		db::from("billing_transaction")->where($where);		
		db::join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID");
		db::order_by("billingTransactionDate","ASC");
		
		return db::get()->result();
	}
}
?>