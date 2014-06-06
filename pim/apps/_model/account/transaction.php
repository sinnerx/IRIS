<?php
namespace model\account;

class Transaction extends Account
{
	private function checkType($type)
	{
		$typeR	= Array(
				"registration"
						);

		return in_array($type,$typeR);
	}

	/* main function for transaction */
	private function createTransaction($accSrc,$accDest,$value,$type,$data)
	{
		$type	= $data['accountTransactionType'];

		if(!$this->checkType($type))
			return false;

		$refID	= $data['accountTransactionRefID'];
		$remark	= $data['accountTransactionRemark'];

		$data	= Array(
				"accountTransactionSource"=>$accSrc,
				"accountTransactionDestination"=>$accDest,
				"accountTransactionStatus"=>!$data['accountTransactionStatus']?1:$data['accountTransactionStatus'],
				"accountTransactionType"=>$data['accountTransactionType'],
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
	public function topUp($accID,$value,$data)
	{
		$this->createTransaction(0,$accDest,$value,"topUp",$data)
	}

	public function userToSite($userID,$siteID,$value,$type,$data)
	{
		$accSrc		= $this->getAccount(1,$userID);
		$accDest	= $this->getAccount(2,$siteID);

		## create transaction.
		return $this->createTransaction($accSrc,$accDest);
	}

	public function siteToUser($siteID,$userID,$value,$type,$data)
	{
		$accSrc		= $this->getAccount(1,$siteID);
		$accDest	= $this->getAccount(2,$userID);

		## create transaction.
		return $this->createTransaction($accSrc,$accDest,$value,$data);
	}
}