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
					"Overview"=>"site/overview",
					"Site Management"=>Array(
									"Information"=>Array("site/edit"),
									"Slider"=>Array("site/slider","site/slider_add","site/slider_edit"),
									"Announcement"=>Array("site/announcement","site/editAnnouncement")
											),
					"Pages"=>"page/index",
					"Blog" =>Array("List of Articles"=>"site/article","Add Article"=>"site/addArticle"),
					"Albums"=>Array("Overview"=>Array("image/album","image/albumPhotos")),
					"Activities"=>Array(
									"Overview"=>Array("activity/overview","activity/add","activity/view"),
									"Events"=>"activity/event",
									"Training"=>"activity/training"
										),

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
							"Announcement"=>Array("site/announcement","site/announcement_add","site/editAnnouncement"),
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