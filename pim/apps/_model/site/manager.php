<?php
namespace model\site;
use db, model;
class Manager extends \Origami
{
	protected $table = 'site_manager';
	protected $primary = 'siteManagerID';

	/**
	 * ORM : get site
	 */
	public function getSite()
	{
		return $this->getOne('site/site', 'siteID');
	}

	/**
	 * ORM : deactivate this record.
	 */
	public function deactivate()
	{
		$this->siteManagerStatus = 0;
		$this->save();
	}

	## if siteID is array, will group into siteID as key, else, wont use. both return result.
	public function getManagersBySite($siteID)
	{
		db::from("site_manager");
		db::where("siteManagerStatus",1);
		db::join("user","user.userID = site_manager.userID");
		db::join("user_profile","user_profile.userID = site_manager.userID");

		if(is_array($siteID))
		{
			db::where("siteID IN (".implode(",",$siteID).")");
			return db::get()->result("siteID",true); ## group into siteID.
		}
		else
		{
			db::where("siteID",$siteID);
			return db::get()->result();
		}
	}

	## get list of mnaager not yet assinged to any site.
	public function getAvailableManager($cols = "user.*,user_profile.*")
	{
		$row_user	= model::load("user/user")->getListOfUser($cols,Array(
												"userLevel"=>2,
												"user.userID NOT IN"=>"(SELECT userID FROM site_manager WHERE siteManagerStatus = 1)"));
		return $row_user;
	}
}



?>