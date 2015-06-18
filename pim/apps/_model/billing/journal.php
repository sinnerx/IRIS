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

		db::select("siteID","billingTransactionDate","billing_item.billingItemName","billing_item.billingItemID",
				"sum(`billingTransactionQuantity`) as quantity", "sum(`billingTransactionUnit`) as unit","sum(`billingTransactionTotal`) as total");
		db::from("billing_transaction")
			->where("siteID = '$siteID' AND billingTransactionDate between '$todayDateStart' AND '$todayDateEnd' AND billingTransactionStatus = 1");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID");
		db::group_by("date(billingTransactionDate), billing_transaction.billingItemID");			
		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}


	public function getListTotal($siteID,$todayDateStart,$todayDateEnd)
	{
		$todayDateEnd = $todayDateEnd." 23:59:59";

		db::select("siteID","billingTransactionDate","billing_item.billingItemName","billing_item.billingItemID",
				"sum(`billingTransactionQuantity`) as quantity", "sum(`billingTransactionUnit`) as unit","sum(`billingTransactionTotal`) as total");
		db::from("billing_transaction")
			->where("siteID = '$siteID' AND billingTransactionDate between '$todayDateStart' AND '$todayDateEnd' AND billingTransactionStatus = 1");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID");
		db::group_by("date(billingTransactionDate)");			
		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}

	public function checkTransaction($itemID)
	{
		db::from("billing_transaction")->where("billingItemID = '$itemID'");
	
		return db::get()->result();
	}
}