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
		db::order_by("menuNo","asc");
		$menuR	= db::get()->result("menuID");

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

	## get full menu.
	private function findMenuRow($id,$menuR)
	{
		return $menuR[$id];
	}

	public function parentChildSort_r($idField, $parentField, $els, $parentID = 0, &$result = array(), &$depth = 0){
    foreach ($els as $key => $value):
        if ($value[$parentField] == $parentID){
            $value['depth'] = $depth;
            array_push($result, $value);
            unset($els[$key]);
            $oldParent = $parentID; 
            $parentID = $value[$idField];
            $depth++;
            $this->parentChildSort_r($idField,$parentField, $els, $parentID, $result, $depth);
            $parentID = $oldParent;
            $depth--;
        }
    endforeach;
    return $result;
}

	public function getTopMenu3($siteID)
	{
		db::from("menu")->where("siteID",$siteID)->where("menuType",1);
		db::order_by("menuNo","asc");
		$menuR	= db::get()->result();

		$res	= $this->parentChildSort_r("menuID","menuParentID",$menuR);

	}



	public function getTopMenu2($siteID)
	{
		db::from("menu")->where("siteID",$siteID)->where("menuType",1);
		db::order_by("menuNo","asc");
		$menuR	= db::get()->result();

		## Cannot brain this anymore.
		## this logic credited to : 
		## http://blog.ideashower.com/post/15147134343/create-a-parent-child-array-structure-in-one-pass
		$refs = array();
		$list = array();

		foreach($menuR as $row)
		{
			$thisref = &$refs[$row['menuID']];

			$thisref['menuParentID'] = $row['menuParentID'];
			$thisref['row'] = $row;

			if ($row['menuParentID'] == 0) {
			$list[ $row['menuID'] ] = &$thisref;
			} else {
			$refs[ $row['menuParentID'] ]['child'][$row['menuID']] = &$thisref;
			}
		}

		return $list;
	}

	## get unused component.
	public function getUnusedComponent()
	{

	}

	public function updateTopMenu($siteID,$dataR)
	{
		## 1. delete existing.
		db::delete("menu",Array("siteID"=>$siteID));

		## 2. re-create menu back.
		foreach($dataR as $data)
		{
			$this->create($siteID,$data['componentNo'],$data);
		}
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
					"componentRoute"=>"#",
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

	public function componentChild($no = null)
	{
		$arr	= Array(
				4=>Array(
					"Galeri Media"=>Array(
							Array("Galeri Foto","galeri"),
							Array("Galeri Video","video"),
							Array("Forum","forum")
							#Array("Galeri Video","#"),
							#Array("Galeri Muat Turun","#")
								),
					"Ruangan Ahli"=>Array(
							Array("Direktori Ahli","ahli")
										)
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