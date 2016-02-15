<?php namespace model\billing;
use db, session, model;

class Verify extends \Origami
{
	protected $table = 'billing_verification';
	protected $primary = 'billingVerificationID';

	public function getVerify($todayDate)
	{

		$where	= Array(
						
				"billingTransactionDate" => $todayDate

						);

		db::from("billing_verification");
		db::where($where);
				
		return db::get()->result();
	}

	public function getAutoVerify($siteID,$todayDate)
	{
		//$siteID = 62;    // TESTING

		$where	= Array(
						
				"siteID" => $siteID,						
				"billingTransactionDate" => $todayDate

						);

		db::from("billing_verification");
		db::where($where);
				
		return db::get()->result();
	}

	public function insertDailyVerify($siteID,$todayDate)
	{
		//$siteID = 62;    // TESTING
		$userID = 0;		

		$data	= Array(
			
			"userID" => $userID,
			"siteID" => $siteID,
			"billingTransactionDate" => date('Y-m-d', strtotime($todayDate)),
			"billingVerificationDate" => now()			
		
						);

		db::insert("billing_verification",$data);						
	}

	public function updateDailyVerify($siteID,$todayDate)
	{
		//$siteID = 62;    // TESTING
		$userID = 0;		

		$data	= Array(
			
			"userID" => $userID,
			"siteID" => $siteID,
			"billingTransactionDate" => date('Y-m-d', strtotime($todayDate)),
			"billingVerificationDate" => now()			
		
						);

		db::where("billingTransactionDate",date('Y-m-d', strtotime($todayDate)));
		db::update("billing_verification",$data);					
	}

}