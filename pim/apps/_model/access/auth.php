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
				"userPassword"=>model::load("helper")->hashPassword($userPassword),
				"userLevel"=>1, # member.
						));

		db::from("user");

		$row	= db::get()->row();
		
		## not found.
		if(!$row)
		{
			return false;
		}

		return $row;
	}

	public function login($userID,$userLevel)
	{
		## set session : userLevel and userID
		session::set("userLevel",$userLevel);
		session::set("userID",$userID);
	}

	## used in backend : auth controller, and save data for later use.
	public function authUser($userID)
	{
		$row_user	= model::load("user/user")->get($userID);
		## nope.
		if(!$row_user)
			return false;

		## point.
		$data		= &$this->authData;

		$data['user']	= $row_user;

		## level specific 
		$level = $row_user['userLevel'];

		switch($row_user['userLevel'])
		{
			case 1: # site-member.
			$data['site']	= model::load("site/site")->getSiteByMember($userID);

			## prepare flag for member authentication.
			# 1. isMember

			# 2. is active
			$data['user']['isActive']	= true;


			break;
			case 2: # site-manager.
				## get site info.
			$data['site']	= model::load("site/site")->getSiteByManager($userID);
			break;
		}

		return $data;
	}

	## used in frontend : auth controller, authenticate site and save it for later use as 'current_site' in authData.
	public function authSite($slug)
	{
		$row_site	= model::load("site/site")->getSiteBySlug($slug);

		if(!$row_site)
			return false;

		$this->authData['current_site']	= $row_site;

		## save isMember flag.
		$this->authData['current_site']['isMember']	= false;
		if($this->authData['current_site']['siteID'] == $this->authData['site']['siteID'])
		{
			$this->authData['current_site']['isMember']	= true;
		}

		return $row_site;
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