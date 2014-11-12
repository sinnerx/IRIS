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
			## check temp_user
			$temp	= model::load("site/member")->getTemporaryUser($userIC,$userPassword);

			## if got in temporary, register him based on temporary data.
			if($temp)
			{
				#echo $userIC." ".model::load("helper")->hashPassword($userPassword);
				#die;
				model::load("site/member")->registerByImport($temp['temp_CBC_Site'],$userIC,$temp);
			}

			return false;
		}

		return $row;
	}

	public function checkManagerSiteLogin($email,$password,$siteID)
	{
		db::where(Array(
				"userEmail"=>$email,
				"userPassword"=>model::load("helper")->hashPassword($password),
				"userLevel"=>2
						));

		db::from("user");
		$row	= db::get()->row();
		if(!$row)
		{
			return false;
		}

		## check if he already got site_member table.
		/*db::where("userID",$row['userID']);

		if(!db::get("site_member")->row())
		{
			## add site_member
			model::load("site/member")->add($row['userID'],$siteID,1,0);
		}*/

		return $row;
	}

	public function login($userID,$userLevel)
	{
		## set session : userLevel and userID
		session::set("userLevel",$userLevel);
		session::set("userID",$userID);

		## create a log, everytime user login.
		model::load("log/login")->createLog($userID);

		## update lastLogin for easier login.
		db::where("userID",$userID)->update("user",Array("userLastLogin"=>now()));
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

			# 2. is active/inactive status
			$data['user']['memberStatus']	= $data['site']['siteMemberStatus'] == 1?"active":"inactive";
			$data['user']['isMember']	= true;

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

		## user role.
		

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