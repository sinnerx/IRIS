<?php
namespace model\site;
use db, model, pagination, session, url;
class Member extends \Origami
{
	/**
	 * ORM information
	 */
	protected $table = "site_member";
	protected $primary = "siteMemberID";

	/**
	 * ORM : Member is active.
	 */
	public function isActivated()
	{
		return $this->siteMemberStatus == 1;
	}

	/**
	 * ORM : Access
	 */
	public function hasAccessTo($accessName, $parameters = array())
	{
		switch($accessName)
		{
			// by default, all site/member has access to forum, except site-specific (2) category. (which require paid member)
			case "forum":
				if($parameters['forumCategoryAccess'] == 2)
				{
					if($this->isActivated())
						return true;
					else
						return false;
				}

				return true;
			break;
		}
	}

	public function getMemberListBySite($cols = "*",$cond = null,$join = null,$limit = null,$order = null,$pageConf = null,$siteID = null)
	{
		db::from("site_member");
		db::join("user_profile","user_profile.userID = site_member.userID");
		db::join("user","user.userID = site_member.userID");
		
		if($siteID)
		{
			db::where("siteID",$siteID);
		}

		if($join)
		{
			db::join($join,$join.".userID = site_member.userID");
		}

		db::select($cols);

		if($cond)
		{
			db::where($cond);
		}

		if($limit)
		{
			db::limit($limit);
		}

		if($order)
		{
			db::order_by($order);
		}

		$num = db::num_rows();

		if($pageConf)
		{
			pagination::initiate(Array(
								"urlFormat"=>$pageConf['urlFormat'],
								"totalRow"=>$num,
								"limit"=>$pageConf['limit'],
								"currentPage"=>$pageConf['currentPage']
										));

			db::limit($pageConf['limit'],pagination::recordNo()-1);
		
			return Array(db::get()->result(),$num);
		}
		else
		{
			return db::get()->result();
		}
	}

	public function checkMember($siteID,$userIC)
	{
		$userCheck	= db::where("userID IN (SELECT userID FROM user WHERE userIC = ?)",Array($userIC));

		if($userCheck)
		{
			return $userCheck;
		}

		## not-found. check at temporary_user
		$tempCheck	= db::where("temp_username",$userIC)->get("temp_user")->row();

		
	}

	public function getPaginatedList($siteID,$pgConf = null,$where = null)
	{
		if($where)
		{
			foreach($where as $k=>$v)
			{
				db::where($k,$v);
			}
		}

		//echo '<pre>';print_r($pgConf);die;
		db::where("siteID",$siteID);
		db::from("site_member");

		if($pgConf)
		{
			pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
			pagination::initiate(Array(
							"totalRow"=>db::num_rows(),
							"limit"=>10,				
							"urlFormat"=>$pgConf['urlFormat'],
							"currentPage"=>$pgConf['currentPage']
										));

			db::limit(10,pagination::recordNo()-1);
		}
		
		db::join("user","user.userID = site_member.userID");
		db::join("user_profile","user_profile.userID = site_member.userID");

		db::order_by("siteMemberStatus ASC, siteMemberID DESC");

		return db::get()->result("userID");
	}

	public function register($siteID,$ic,$password,$birthDate,$fullname,$lastname,$isOutsider,$gender,$occupationGroup)
	{
		$data_user	= Array(
					"userIC"=>$ic,
					"userPassword"=>$password,
					"userProfileDOB"=>$birthDate,
					"userProfileFullName"=>$fullname,
					"userProfileLastName"=>$lastname,
					"userProfileGender"=>$gender,
					"userProfileOccupationGroup"=>$occupationGroup
							);

		## register as level one (1) user..
		$row_user	= model::load("user/user")->add($data_user,1);

		## add.
		$this->add($row_user['userID'],$siteID,0,$isOutsider);

		## do celcom api user update, everytime user register.
		$siteRefID	= model::load("site/site")->getSiteRefIDBySiteID($siteID);
		$updated	= model::load("celcom/auth")->update_user($ic,$password,$siteRefID);

		## create user activity.
		model::load("user/activity")->create($siteID,$row_user['userID'],"member.register");
	}

	public function registerByImport($siteRefID,$ic,$data)
	{
		## find siteID by given old site_id.
		$siteID	= db::select("siteID")->where("siteRefID",$siteRefID)->get("site")->row("siteID");

		## return false, if not found. cancel registration.
		if(!$siteID)
			return false;

		## gender translate.
		$userParam	= model::load("user/param");
		$genderR	= array_flip($userParam->gender());
		$titleR		= array_flip($userParam->title());

		$data_user	= Array(
					"userIC"=>$ic,
					"userPassword"=>$data['temp_password'],
					"userEmail"=>$data['temp_email'],
					"userProfileDOB"=>$data['temp_DOB'],
					"userProfileTitle"=>$titleR[$data['temp_Title']]?:0,
					"userProfileFullName"=>$data['temp_name'],
					"userProfileLastName"=>"",
					"userProfileGender"=>$genderR[$data['temp_Gender']]?:0,
					"userProfileDOB"=>$data['temp_DOB'],
					"userProfilePhoneNo"=>$data['temp_contact_no'],
					"userProfileMobileNo"=>$data['temp_Tel'],
					"userProfileMailingAddress"=>$data['temp_Address'],
					"userProfileOccupation"=>$data['temp_Occupation'],
					"userProfileEducation"=>$data['temp_Education']
							);

		$row_user	= model::load("user/user")->add($data_user,1);

		## create site_member with an imported flag.
		$this->add($row_user['userID'],$siteID,1,0,1);
	}

	public function add($userID,$siteID,$status,$isOutsider = 0,$imported = 0)
	{
		## add as site_member
		$data_sitemember	= Array(
						"userID"=>$userID,
						"siteID"=>$siteID,
						"siteMemberStatus"=>$status, ## in-active member
						"siteMemberOutsider"=>$isOutsider,	## 1 is outsider, 0 is nope.
						"siteMemberImported"=>$imported
						);

		## WIN.
		db::insert("site_member",$data_sitemember);
	}

	## approve member. and also create a transaction.
	public function approveMember($userID)
	{
		## get siteID from user.
		$siteID	= db::select("siteID")->where("userID",$userID)->get("site_member")->row("siteID");

		db::where("userID",$userID);
		db::update("site_member",Array("siteMemberStatus"=>1));

		## pay registration.
		$this->payRegistration($userID,$siteID);
	}

	## method to pay registration.
	public function payRegistration($userID,$siteID)
	{
		$transaction	= model::load("account/transaction");

		## fees
		$fee	= model::load("config")->get("configMemberFee")?:5;
		
		## 1. top user up first.
		$transaction->topUpUser($userID,$fee); ## top up user
		$transaction->transactUserToSite($userID,$siteID,$fee,"registration"); ## and do transaction from user to site.
	}

	## get user member detail
	public function getUserMemberDetail($userID=null,$siteID=null){
		//echo '<pre>';print_r($pgConf);die;
		db::select("site_member.*,user_profile.*,user_profile_additional.*,user.*");
		db::where("site_member.siteID",$siteID);
		db::where("site_member.userID",$userID);
		db::from("site_member");
		db::join("site","site.siteID = site_member.siteID");
		db::join("user","user.userID = site_member.userID");
		db::join("user_profile","user_profile.userID = site_member.userID");
		db::join("user_profile_additional","user_profile_additional.userID = user.userID");

		return db::get()->row();
	}

	## return user's site.
	public function getMemberSite($userID)
	{
		if(!$userID)
		{
			return false;
		}

		db::from("site_member");
		db::select("site.*,site_member.userID");
		db::where("userID",$userID);
		db::join("site","site.siteID = site_member.siteID");

		if(is_array($userID))
		{
			return db::get()->result("userID");
		}
		else
		{
			return db::get()->row();
		}
	}

	public function importTemporaryUser($d)
	{
		## strip - from username

		## check if the record already exists. if it is, deny.
		if(db::select("tempUserID")->where("temp_username",$d['username'])->get("temp_user")->row())
		{
			return false;
		}

		$data	= Array(
		"temp_userid"=>$d['userid'],
		"temp_username"=>str_replace("-", "", $d['username']),
		"temp_usernameOriginal"=>$d['username'],
		"temp_password"=>$d['password'],
		"temp_totallogin"=>$d['totallogin'],
		"temp_lastlogin"=>$d['lastlogin'],
		"temp_userright"=>$d['userright'],
		"temp_user_status"=>$d['user_status'],
		"temp_name"=>$d['name'],
		"temp_ic_no"=>$d['ic_no'],
		"temp_contact_no"=>$d['contact_no'],
		"temp_Address"=>$d['Address'],
		"temp_datecreated"=>$d['datecreated'],
		"temp_DOB"=>$d['DOB'],
		"temp_POB"=>$d['POB'],
		"temp_Tel"=>$d['Tel'],
		"temp_CBC_Site"=>$d['CBC_Site'],
		"temp_email"=>$d['email'],
		"temp_Occupation"=>$d['Occupation'],
		"temp_Education"=>$d['Education'],
		"temp_Title"=>$d['Title'],
		"temp_activationCode"=>$d['activationCode'],
		"temp_Gender"=>$d['Gender'],
		"temp_mobile"=>$d['mobile'],
		"temp_cbc_siteRight"=>$d['cbc_siteRight']
							);

		db::insert("temp_user",$data);
	}

	public function getTemporaryUser($userIC,$userPassword = null)
	{
		if($userPassword)
			db::where("temp_password",$userPassword);

		return db::where("temp_username",$userIC)->get("temp_user")->row();
	}

	public function createProfileLink($userID,$siteSlug = null)
	{
		if(!$siteSlug)
			$siteSlug = db::where("siteID IN (SELECT siteID FROM site_member WHERE userID = ?)",Array($userID))->get("site")->row("siteSlug");

		if(!$siteSlug)
			return false;

		return url::createByRoute("profile",Array(
			"site-slug"=>$siteSlug,
			"userID"=>$userID
			),true);
	}

	/* A flag whether user has been synced with the AAA Server. */
	public function isSynced($userID)
	{
		$synced = db::where("userID",$userID)->get("site_member")->row("siteMemberSynced");

		return $synced == 1?true:false;
	}
}

?>