<?php

class Model_Access
{
	/*
		sm = site manager (2)
		cl = cluster lead (3)
		om = operation manager (4)
		r  = admin (99)
	*/

	## access list based on array.
	public function accessList()
	{
		$access	= Array(
				"home/index"=>Array("sm"),
				"page/index"=>Array("sm","cl"),
				"slider/index"=>Array("sm"),
				"slider/add"=>Array("sm"),
				"site/index"=>Array("r"),
				"site/info"=>Array("sm","r"),
				"site/add"=>Array("r"),
				"site/edit"=>Array("sm"),
				"user/list"=>Array("r"),
				"user/manager"=>Array("r")
						);
	}

	public function accessLevel($id)
	{
		$levelR	= Array(
				2=>"sm",
				3=>"cl",
				4=>"om",
				99=>"r"
						);

		return $levelR[$id];
	}

	public function menu($level = null)
	{
		$level	= !$level?$this->accessLevel(session::get("userLevel")):$level;

		$menu['sm']	= Array(
					"Overview"=>"home/index",
					"Site Information"=>Array(
									"Basic Info."=>"site/info",
									"Edit site"=>"site/edit",
											),
					"Pages"=>"page/index",
					"Menu"=>"menu/index",
					"Activities"=>Array(
									"Events"=>"event/index",
									"Training"=>"training/index"
										),
							);

		$menu['r']	= Array(
					"Overview"=>"home/index",
					"Sites"=>Array(
							"Preview"=>"site/index",
							"Add new site"=>"site/add",
							"Manager"=>"manager/add",
									),
					"Activities"=>"activity/index",
					"Reports"=>"reports/index"
							);

		return $menu[$level];
	}
}




?>