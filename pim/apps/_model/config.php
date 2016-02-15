<?php
namespace model;
use db;

class Config
{
	var $id	= 1;
	public function get($column = null,$default = null)
	{
		db::where("configID",$this->id);

		$val	= db::get("config")->row($column);
		return !$val?(!$default?$default:false):$val;
	}

	public function set($column,$val = null)
	{
		db::where("configID",$this->id)
		->update("config",Array($column=>$val));
	}

	## create config table.
	public function createConfig()
	{
		if(db::get("config")->row())
			return false;

		## create new config and insert default data.
		db::insert("config",Array(
					"configID"=>1,
					"configMemberFee"=>5,
					"configResetPassFee"=>5,
					"configAllSiteMenu"=>1,
					"configNewsCategoryID"=>1,
					"configManagerSiteID"=>0
					));
	}

	### Name
	public function allSiteMenu($no = null)
	{
		$arrR	= Array(
				1=>"Individual site configured menu",
				2=>"Default configured menu"
						);

		return $no?$arrR[$no]:$arrR;
	}
}


?>