<?php
namespace model\user;

class Services
{
	public function type($no = null)
	{
		$code	= Array(
				"registration",
				"topUp"
						);

		return $no?$code[$no]:$code;
	}

	private function createTransaction($type,$accSrc,$accDest,$value,$refID = 0,$remark = null,$status = 1)
	{
		## developer must know kind of transaction, based on code.
		if(!in_array($type,$this->type()))
		{
			return false;
		}

		## update balance.

		$data	= Array(
				"accountTransactionType"=>$type,
				"accountTransactionRefID"=>$refID,
				"accountTransactionSource"=>$accSrc,
				"accountTransactionDestination"=>$accDest,
				"accountTransactionValue"=>$value,
				"accountTransactionRemark"=>$remark,
				"accountTransactionCreatedDate"=>now(),
				"accountTransactionCreatedUser"=>session::get("userID")
						);

		db::insert("account_transaction",$data);
	}


}



?>