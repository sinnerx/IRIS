<?php
namespace model\account;
use db, session;
class Account
{
	## 1 = user, 2 = site.
	public function createAccount($type,$refID)
	{
		db::insert("account",Array(
						"accountType"=>$type,
						"accountRefID"=>$refID,
						"accountBalance"=>0,
						"accountCreatedDate"=>now(),
						"accountCreatedUser"=>session::get("userID")
						));

		## return row (not last id)
		return db::getLastID("account","accountID",true);
	}

	public function getAccount($type,$refID)
	{
		db::where("accountRefID",$refID);
		$row	= db::get("account")->row();

		## account no exists, create.
		if(!$row)
		{
			$row	= $this->createAccount($type,$refID);
		}

		return $row;
	}

	public function updateBalance($accID,$value,$type)
	{
		db::where("accountID",$accID);

		if($type == "deduct")
		{
			db::update("account",Array("accountBalance = accountBalance - ?"=>Array($value)));
		}
		else if($type == "add")
		{
			db::update("account",Array("accountBalance = accountBalance + ?"=>Array($value)));
		}

	}
}

?>