<?php
namespace model\account;
use model, db, session;
class Transaction extends Account
{
	private function checkType($type)
	{
		$typeR	= Array(
				"registration",
				"topUp"
						);

		return in_array($type,$typeR);
	}

	/* main function for transaction */
	private function createTransaction($accSrc,$accDest,$value,$type,$data = null)
	{
		if(!$this->checkType($type))
			return false;

		$refID	= $data['accountTransactionRefID'];
		$remark	= $data['accountTransactionRemark'];

		$data	= Array(
				"accountTransactionSource"=>$accSrc,
				"accountTransactionDestination"=>$accDest,
				"accountTransactionStatus"=>!$data['accountTransactionStatus']?1:$data['accountTransactionStatus'],
				"accountTransactionType"=>$type,
				"accountTransactionRefID"=>$data['accountTransactionRemark'],
				"accountTransactionValue"=>$value,
				"accountTransactionCreatedDate"=>now(),
				"accountTransactionCreatedUser"=>session::get("userID")
						);

		db::insert("account_transaction",$data);

		## update both account balance.
		$this->updateBalance($accSrc,$value,"deduct");
		$this->updateBalance($accDest,$value,"add");
	}

	/* below is utility functions */
	public function topUpUser($userID,$value,$data = null)
	{
		## use accountID.
		$accID	= $this->getAccount(1,$userID,"accountID");

		## top up.
		$this->topUp($accID,$value,$data);
	}

	public function topUp($accDest,$value,$data = null)
	{
		$this->createTransaction(0,$accDest,$value,"topUp",$data);
	}

	## this one uses userID and siteID.
	public function transactUserToSite($userID,$siteID,$value,$type,$data=array())
	{
		$accSrc		= $this->getAccount(1,$userID,"accountID");
		$accDest	= $this->getAccount(2,$siteID,"accountID");

		## create transaction.
		return $this->createTransaction($accSrc,$accDest,$value,$type,$data);
	}

	public function transactSiteToUser($siteID,$userID,$value,$type,$data=array())
	{
		$accDest	= $this->getAccount(1,$userID,"accountID");
		$accSrc		= $this->getAccount(2,$siteID,"accountID");

		## create transaction.
		return $this->createTransaction($accSrc,$accDest,$value,$type,$data);
	}
}