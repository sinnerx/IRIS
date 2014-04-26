<?php
namespace model\site;
use db, model;
class Menu
{
	## used by model_site@createSite
	## data_menu : menuName, menuRefID, menuNo, menuType (optional)
	public function create($siteID,$componentNo,$data_menu)
	{
		$data	= Array(
				"siteID"=>$siteID,
				"menuType"=>$data_menu['menuType']?$data_menu['menuType']:1, #default top.
				"menuName"=>$data_menu['menuName'],
				"menuRefID"=>$data_menu['menuRefID']?$data_menu['menuRefID']:0,
				"menuNo"=>$data_menu['menuNo'],
				"componentNo"=>$componentNo
						);

		db::insert("menu",$data);
	}

	## used in partial@header
	public function getTopMenu($siteID = null)
	{
		$siteID	= !$siteID?model::load("site/site")->getSiteIDBySlug():$siteID;

		## get menu from db.
		db::from("menu")->where("siteID",$siteID)->where("menuType",1);
		db::join("component","menu.componentNo = component.componentNo");
		db::order_by("menuNo","asc");
		$menuR	= db::get()->result();
		return $menuR;
	}

	## used in partial@header
	public function getComponent()
	{
		return db::get("component")->result("componentNo");
	}
}


?>