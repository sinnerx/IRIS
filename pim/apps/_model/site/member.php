<?php
namespace model\site;
use db, model, pagination, session;
class Member
{
	public function getPaginatedList($siteID,$pgConf = null)
	{
		db::where("siteID",$siteID);

		if($pgConf)
		{
			pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
			pagination::initiate(Array(
							"totalRow"=>db::row(),
							"urlFormat"=>$pgConf['urlFormat'],
							"currentPage"=>$pgConf['currentPage']
										));
		}

		db::join("user","user.userID = site_member.userID");
		db::join("user_profile","user_profile.userID = site_member.userID");

		return db::get("site_member")->result("userID");
	}

	public function register($siteID,$ic,$password,$birthDate)
	{
		$data_user	= Array(
					"userIC"=>$ic,
					"userPassword"=>$password,
					"userProfileBirthDate"=>$birthDate
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

		## create transaction.
		$value	= model::load("config")->get("configMemberFee")?:3;

		model::load("account/transaction")->userToSite($userID,$siteID,$value,"registration");
	}
}

?>