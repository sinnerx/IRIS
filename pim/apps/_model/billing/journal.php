<?php 

namespace model\billing;
use db, session;

class journal extends \Origami
{ 
	protected $table = 'billing_transaction';
	protected $primary = 'billingTransactionID';
		
	public function getList($siteID,$todayDateStart,$todayDateEnd)
	{
		$todayDateEnd = $todayDateEnd." 23:59:59";

		db::select("siteID","billingTransactionID","billingTransactionDate","billingTransactionTotalQuantity as quantity", "billingTransactionTotalUnit as unit" ,
				   "billingTransactionTotal as total");

		db::from("billing_transaction")
			->where("siteID = '$siteID' AND billingTransactionDate between '$todayDateStart' AND '$todayDateEnd' AND billingTransactionStatus = 1");
					
		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}

	public function getListTotal($transactionID)
	{
		db::from("billing_transaction_item")
			->where("billingTransactionID = '$transactionID'");

		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");
		
		return db::get()->result();
	}

	public function checkTransaction($itemID)
	{
		db::from("billing_transaction")->where("billingItemID = '$itemID'");
	
		return db::get()->result();
	}

	public function getTransactionalList($siteID,$todayDateStart,$todayDateEnd)
	{

		db::from("billing_transaction")
			->where("siteID = '$siteID' AND billingTransactionDate >= '$todayDateStart' AND billingTransactionDate <= '$todayDateEnd 23:59:59' AND billingTransactionStatus = 1");		

		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");		

		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}

	public function editTranscation($itemTransactionID){

		db::from("billing_transaction_item")
			->where("billingTransactionItemID = '$itemTransactionID'");		
		
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");		
		return db::get()->result();
	}

	public function updateTranscationItem($itemTransactionID,$data){
	
		db::where("billingTransactionItemID = '$itemTransactionID'");
		db::update("billing_transaction_item",$data);	
		
	}

	public function updateTranscation($transactionID){

		db::select("sum(billingTransactionItemQuantity) as quantity", "sum(billingTransactionItemUnit) as unit" , "sum(billingTransactionItemPrice) as total");
		
		db::from("billing_transaction_item")->where("billingTransactionID = '$transactionID'");

		$row = db::get()->row();


		$data	= Array(

			"billingTransactionTotalQuantity"=> $row['quantity'],
			"billingTransactionTotalUnit"=>$row['unit'],
			"billingTransactionTotal"=>$row['total'],
			"billingTransactionUpdatedDate"=>now()

		);
	
		db::where("billingTransactionID = '$transactionID'");
		db::update("billing_transaction",$data);	
		
	}
}