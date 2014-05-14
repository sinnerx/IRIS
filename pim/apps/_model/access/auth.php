<?php
namespace model\access;
use db, model, session;
Class Auth
{
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
}

?>