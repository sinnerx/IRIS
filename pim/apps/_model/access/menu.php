<?php
namespace model\access;
use session;
class Menu extends Data
{
	### backend menu.
	public function get($level = null)
	{
		## convert num to notation.
		$level	= $this->accessLevelCode($level);

		## site manager.
		$menu['sm']	= Array(
					/*"Overview"=>"home/index",*/
					"Site Management"=>Array(
									"Information"=>Array("site/edit"),
									"Slider"=>Array("site/slider","site/slider_add","site/slider_edit"),
											),
					"Pages"=>"page/index",
					/*"Activities"=>Array(
									"Events"=>"event/index",
									"Training"=>"training/index"
										),*/
							);

		$menu['cl']	= Array(
					"Overview"=>Array(
								"Cluster Overview"=>Array("cluster/overview")
									)
							);

		## root.
		$menu['r']	= Array(
					/*"Overview"=>"home/index",*/
					"Sites"=>Array(
							"Preview"=>Array("site/index","site/edit","site/assignManager"),
							"Add new site"=>"site/add",
							#"Manager"=>Array("manager/lists","manager/add","manager/edit"),
							"General Slider"=>Array("site/slider","site/slider_edit"),
							"Cluster"=>Array("cluster/lists","cluster/assign"),
							"Message"=>Array("site/message","site/messageView")
									),/*
					"Cluster"=>Array(
							"Cluster List"=>Array("cluster/lists"),
							"Cluster Lead"=>Array("cluster/leadLists","cluster/leadAdd","cluster/sitelist","cluster/assign")
									),*/
					"Management"=>Array(
							"User"=>Array("user/lists","user/add","user/edit")
										)
					/*"Activities"=>"activity/index",
					"Reports"=>"reports/index"*/
							);

		return $menu[$level];
	}
}


?>