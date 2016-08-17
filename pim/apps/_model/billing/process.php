<?php 

namespace model\billing;
use db, session;

class process 
{ 
	
	public function getBalanceDebit($siteID,$month,$year)
	{

		db::select("sum(billing_transaction_item.billingTransactionItemPrice) as balance");
		db::from("billing_transaction")
		->where("siteID = '$siteID' AND year(billingTransactionDate) = '$year' AND (month(billingTransactionDate) > '1' AND month(billingTransactionDate) <= '$month') 
				 AND billingTransactionStatus = 1 AND ( billing_item.billingItemType = 1)");					
	
		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");	


		db::order_by("billingTransactionDate", "ASC");
		db::limit(1);

		return db::get()->row();
	}

	public function getBalanceCredit($siteID,$month,$year)
	{

		db::select("sum(billing_transaction_item.billingTransactionItemPrice) as balance");
		db::from("billing_transaction")
		->where("siteID = '$siteID' AND year(billingTransactionDate) = '$year' AND (month(billingTransactionDate) > '1' AND month(billingTransactionDate) <= '$month')   
				AND billingTransactionStatus = 1 AND ( billing_item.billingItemType = 2)");					

		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");	

		db::order_by("billingTransactionDate", "ASC");
		db::limit(1);

		return db::get()->row();
	}

	public function getdateList($siteID,$month,$year)
	{
		//echo $siteID . "." . $month . "." . $year;

		db::select("billingTransactionDate");
		db::from("billing_transaction")
		->where("siteID = '$siteID' AND year(billingTransactionDate) = '$year' AND month(billingTransactionDate) = '$month'  AND billingTransactionStatus = 1 AND ( billing_transaction_item.billingItemID <> 14 AND billing_transaction_item.billingItemID <> 15 )");			
	
		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");	

		db::group_by("billingTransactionDate");				
		db::order_by("billingTransactionDate", "ASC");

		//var_dump(db::get());

		return db::get()->result();
	}


	public function getList($siteID,$date)
	{

		db::select("siteID","billingTransactionDate","billingTransactionDescription","billing_item.billingItemName","billing_item.billingItemID",
			"sum(billing_transaction_item.billingTransactionItemQuantity) as quantity", 
			"sum(billing_transaction_item.billingTransactionItemUnit) as unit",
			"sum(billing_transaction_item.billingTransactionItemPrice) as total");

		db::from("billing_transaction")
			->where("siteID = '$siteID' AND billingTransactionDate LIKE '$date%'  AND billingTransactionStatus = 1 AND 
					( billing_transaction_item.billingItemID <> 14 AND billing_transaction_item.billingItemID <> 15 )");
		
		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");

		db::group_by("billingItemName");				
		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}


	public function getTransferList($siteID,$date) // not yet
	{
		$date = date('m', strtotime($date)); 	

		db::select("siteID","date(billingTransactionDate) as date","billingTransactionDescription as description",
			"billingTransactionTotal as total","billingTransactionBalance as balance");

		db::from("billing_transaction")
			->where("siteID = '$siteID' AND month(billingTransactionDate) = '$date' AND 
				( billing_transaction_item.billingItemID = 14 OR billing_transaction_item.billingItemID = 15 ) AND billingTransactionStatus = 1 ");
						
		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");
		
		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}

	public function getListTotal($siteID,$month,$year)
	{
		db::select("siteID","date(billingTransactionDate) as transactionDate","billing_item.billingItemName","billing_item.billingItemID",
			"sum(billing_transaction_item.billingTransactionItemQuantity) as quantity", 
			"sum(billing_transaction_item.billingTransactionItemUnit) as unit",
			"sum(billing_transaction_item.billingTransactionItemPrice) as total");


		db::from("billing_transaction")
			->where("siteID = '$siteID' AND year(billingTransactionDate) = '$year' AND month(billingTransactionDate) = '$month' AND 
				billingTransactionStatus = 1 AND ( billing_transaction_item.billingItemID <> 14 AND billing_transaction_item.billingItemID <> 15 )");

		db::join("billing_transaction_item", "billing_transaction_item.billingTransactionID = billing_transaction.billingTransactionID");
		db::join("billing_item", "billing_item.billingItemID = billing_transaction_item.billingItemID");
		
		db::group_by("date(billingTransactionDate)");			
		db::order_by("billingTransactionDate", "ASC");

		return db::get()->result();
	}

	public function getCurrentCollection($siteID,$startDate,$lastDate)
	{ 
		db::select("sum('billingTransactionTotal') as total");
		db::from("billing_transaction")
			->where("siteID = '$siteID' AND billingTransactionDate between '$startDate' AND '$lastDate' AND billingTransactionStatus = 1");

		return db::get()->row();
	}
}