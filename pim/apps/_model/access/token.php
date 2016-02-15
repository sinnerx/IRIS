<?php
namespace model\access;
use model, db, url;
class Token
{
	public function tokenType()
	{
		$typeR	= Array(
				1=>"Reset Password"
						);
	}

	## prepare access link by type.
	public function accessLink($tokenName)
	{
		return url::base("authenticateToken/$tokenName");
		$accessLink	= Array(
				1=>"resetPassword"
							);

		return !$type?$accessLink:($tokenName?$accessLink[$type]."?token=$tokenName":$accessLink[$type]);
	}

	private function _createTokenName()
	{
		## last id.
		$lasttokenID	= db::getLastID("token","tokenID");
		$lasttokenID	= !$lasttokenID?1:$lasttokenID+1; ## increment 1 for hashing.

		## create hash by last tokenID.
		$hashed			= model::load("helper")->hashPassword($lasttokenID,md5($lasttokenID.$lasttokenID));

		return $hashed;
	}

	public function expireToken($token)
	{
		## token.
		db::where("tokenName",$token);
		db::update("token",Array("tokenStatus"=>2));
	}

	public function expireLastToken($type,$data)
	{
		switch($type)
		{
			## reset password.
			case 1:
			db::where("tokenID IN","(SELECT tokenID FROM token_resetpass WHERE userID = '$data[userID]')");
			db::where("tokenStatus",1);

			if($res = db::get("token")->result())
			{
				## set token to expire.
				foreach($res as $row)
				{
					db::where("tokenID",$row['tokenID'])->update("token",Array("tokenStatus"=>2));
				}
			}
			break;
		}
	}

	public function createToken($type,$data)
	{
		## expire last token.
		$this->expireLastToken($type,$data);

		$tokenName	= $this->_createTokenName();

		db::insert("token",Array(
						"tokenType"=>$type,
						"tokenName"=>$tokenName,
						"tokenCreatedDate"=>now(),
						"tokenStatus"=>1 ## active.
								));
		$tokenID	= db::getLastID("token","tokenID");
		switch($type)
		{
			case 1:
				db::insert("token_resetpass",Array(
						"tokenID"=>$tokenID,
						"userID"=>$data['userID'],
						"tokenResetpassStatus"=>0  ## in-active.
											));


			break;
		}

		return $this->getTokenInfo($tokenName);
	}

	public function getTokenInfo($tokenName)
	{
		db::from("token");
		db::where("tokenName",$tokenName);
		db::where("tokenStatus",1);

		## join if tokenType = 1
		db::join("token_resetpass","token.tokenType = 1 AND token_resetpass.tokenID = token.tokenID");

		return db::get()->row();
	}

	public function activateResetpass($userID,$tokenID)
	{
		db::where("tokenID",$tokenID);
		db::update("token_resetpass",Array("tokenResetpassStatus"=>1)); ## 1 is active.
	}

	public function useResetpassToken($token)
	{
		db::where("tokenID IN (SELECT tokenID FROM token WHERE tokenName = '$token')");
		db::update("token_resetpass",Array("tokenResetpassStatus"=>2));

		## expire token.
		$this->expireToken($token);
	}
}