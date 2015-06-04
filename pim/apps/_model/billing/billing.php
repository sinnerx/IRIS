<?php 

namespace model\billing;
use db, session;

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
		return db::get()->result();		
	}

	public function addTransaction($siteID,$id,$data_sales,$lastBalance)
	{
		
		if ($data_sales['utilitiesList'] != ""){
			$description = $data_sales['utilitiesList']." = ".$data_sales['description'];	

		} elseif ($data_sales['transferList'] != ""){
			$description = $data_sales['transferList'];	
		} else {
			$description = $data_sales['description'];
		}

		$todayDate = date('Y-m-d H:i', strtotime($data_sales['selectDate'])); 	

		if  (($id == 14) || ($id == 15))	{

		 $billingTransactionBalance = $lastBalance - $data_sales['total'];	
		 $data_sales['total'] = 0 - $data_sales['total'];

		} else {
		 $billingTransactionBalance = $data_sales['total'] + $lastBalance;

		}

		$data	= Array(

			"siteID" => $siteID,
			"userID" => session::get('userID'),
			"billingItemID" => $id,
			"billingTransactionQuantity" => $data_sales['quantity'],
			"billingTransactionUnit" => $data_sales['unit'],
			"billingTransactionTotal" => $data_sales['total'],
			"billingTransactionBalance" => $billingTransactionBalance,
			"billingTransactionDescription" => $description,
			"billingTransactionStatus" => 1,
			"billingTransactionDate" => $todayDate,
			"billingTransactionCreatedDate" => now(),
			"billingTransactionUpdatedDate" => now()

						);
		//print_r($data);
		db::insert("billing_transaction",$data);

		return db::getLastID('billing_transaction', 'billingTransactionID', true);
	}

	public function getList($siteID,$limit = null)
	{
		if ($limit == null) {
			$limit = 5;
		}

		$where	= Array(
				"siteID"=>$siteID,								
				"billingTransactionStatus" => 1
						);


		db::from("billing_transaction")->where($where);
		db::join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID");
		db::order_by("billingTransactionDate","DESC");
		db::limit($limit);

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