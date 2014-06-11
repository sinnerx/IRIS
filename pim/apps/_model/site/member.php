<?php
namespace model\site;
use db, model, pagination, session;
class Member
{
	public function getPaginatedList($siteID,$pgConf = null)
	{
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

	public function register($siteID,$ic,$password,$birthDate,$fullname)
	{
		$data_user	= Array(
					"userIC"=>$ic,
					"userPassword"=>$password,
					"userProfileBirthDate"=>$birthDate,
					"userProfileFullName"=>$fullname
							);

		## register as level one (1) user..
		$row_user	= model::load("user/user")->add($data_user,1);

		## add as site_member
		$data_sitemember	= Array(
						"userID"=>$row_user['userID'],
						"siteID"=>$siteID,
						"siteMemberStatus"=>0 ## in-active member.
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
		$fee	= model::load("config")->get("configMemberFee")?:3;
		
		## 1. top user up first.
		$transaction->topUpUser($userID,$fee); ## top up user
		$transaction->transactUserToSite($userID,$siteID,$fee,"registration"); ## and do transaction from user to site.
	}

	## get user member detail
	public function getUserMemberDetail($siteMemberID=null,$userID=null,$siteID=null){
		//echo '<pre>';print_r($pgConf);die;
		db::where("site_member.siteID",$siteID);
		db::where("site_member.userID",$userID);
		db::where("site_member.siteMemberID",$siteMemberID);
		db::from("site_member");

		db::join("user","user.userID = site_member.userID");
		db::join("user_profile","user_profile.userID = site_member.userID");

		return db::get()->row();
	}
}

?>