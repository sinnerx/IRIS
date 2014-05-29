<?php
namespace model\access;
use db, model, session;
Class Auth
{
	var $authData	= Array();

	public function backendLoginCheck($email,$password)
	{
		db::from("user");
		db::where(Array(
				"userEmail"=>$email,
				"userPassword"=>model::load("helper")->hashPassword($password)
						));

		$row = db::get()->row();

		if($row)
		{
			return $row;
		}

		return false;
	}

	## member login. used in frontend:main/login
	public function checkMemberLogin($userIC,$userPassword)
	{
		## 
		db::where(Array(
				"userIC"=>$userIC,
				"userPassword"=>model::load("helper")->hashPassword($userPassword)
						));

		db::from("user");

		return db::get()->row()?true:false;
	}

	public function login($userID,$userLevel)
	{
		## set session : userLevel and userID
		session::set("userLevel",$userLevel);
		session::set("userID",$userID);
	}

	## used in auth controller, and save data for later use.
	public function authCheck($userID)
	{
		$row_user	= model::load("user/user")->get($userID);

		## nope.
		if(!$row_user)
			return false;

		$data['user']	= $row_user;

		## level specific 
		$level = $row_user['userLevel'];

		switch($row_user['userLevel'])
		{
			case 2: # site-manager.
				## get site info.
			$data['site']	= model::load("site/site")->getSiteByManager($userID);
			break;
		}

		## save authenticated data.
		$this->authData	= $data;

		return $data;
	}

	## used in every authentication
	public function getAuthData($key = null,$secondKey = null)
	{
		$authData	= $this->authData;
		if($authData)
			return !$key?$authData:(!$secondKey?$authData[$key]:$authData[$key][$secondKey]);

		return false;
	}
}

?>