<?php 

namespace model\billing;
use db, session;

class finance extends \Origami
{ 
	protected $table = 'billing_finance_transaction';
	protected $primary = 'billingFinanceTransactionID';
	
	public function getList($userID)
	{

		$where	= Array(
				"userID"=>$userID,								
				"billingFinanceTransactionStatus" => 1
						);

		db::from("billing_finance_transaction");
		db::where($where);
		db::join("site", "site.siteID = billing_finance_transaction.siteID");
		db::order_by("billingFinanceTransactionDate","DESC");
		
		return db::get()->result();
	}
}	