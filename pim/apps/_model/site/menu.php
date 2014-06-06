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
		#db::join("component","menu.componentNo = component.componentNo");
		db::order_by("menuNo","asc");
		$menuR	= db::get()->result();

		## join component.
		$newMenuR	= Array();
		$component	= $this->getComponent();
		foreach($menuR as $row)
		{
			$no	= $row['componentNo'];
			$row['componentName']	= $component[$no]['componentName'];
			$row['componentRoute']	= $component[$no]['componentRoute'];
			$row['componentStatus']	= $component[$no]['componentStatus'];
			$newMenuR[]	= $row;
		}

		return $newMenuR;
	}

	## check component in menu.
	public function getTopMenu2()
	{
		
	}

	## get unused component.
	public function getUnusedComponent()
	{
		
	}

	## used in partial@header ## now we use array instead of db for storing this data. @ 2/june.
	public function getComponent()
	{
		$arr	= Array(
				1=>Array(
					"componentNo"=>1,
					"componentName"=>"Page",
					"componentRoute"=>"",
					"componentStatus"=>1
					),
				2=>Array(
					"componentNo"=>2,
					"componentName"=>"Utama",
					"componentRoute"=>"",
					"componentStatus"=>1
					),
				3=>Array(
					"componentNo"=>3,
					"componentName"=>"Aktiviti",
					"componentRoute"=>"aktiviti",
					"componentStatus"=>1
					),
				4=>Array(
					"componentNo"=>4,
					"componentName"=>"Ruangan Ahli",
					"componentRoute"=>"members",
					"componentStatus"=>1
					),
				5=>Array(
					"componentNo"=>5,
					"componentName"=>"Hubungi Kami",
					"componentRoute"=>"hubungi-kami",
					"componentStatus"=>1
					),
				6=>Array(
					"componentNo"=>6,
					"componentName"=>"Blog",
					"componentRoute"=>"blog",
					"componentStatus"=>1
					),
				99=>Array(
					"componentNo"=>99,
					"componentName"=>"Custom",
					"componentRoute"=>"",
					"componentStatus"=>1
					)
					);

		return $arr;
	}

	/*public function getComponent()
	{
		return db::get("component")->result("componentNo");
	}*/
}


?>