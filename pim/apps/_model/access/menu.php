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
									"Facebook"=>array("facebook/checkPageId", 'facebook/getPageId'),
									"Announcement"=>Array("site/announcement","site/editAnnouncement"),
									"Site Menu"=>"menu/index",
									"Sales"=>array("sales/add", "sales/edit"),
									'Message'=> array('site/message', 'site/messageView')
									#"Transaction"=>"account/transaction"
											),
					"Newsletter"=>Array(
						"Push"=>"newsletter/push",
						"Template"=>"newsletter/template",
						"Subscribers"=>"newsletter/subscribers"
						),
					"Pages"=>"page/index",
					"Blog" =>Array(
							"List of Articles"=>Array("site/article","site/editArticle"),
							"Add Article"=>"site/addArticle"),
					"Albums"=>Array("Overview"=>Array("image/album","image/albumPhotos")),
					"Video Gallery"=>Array("Overview"=>"video/album"),
					"Activities"=>Array(
									"Overview"=>Array("activity/overview","activity/add","activity/view","activity/edit"),
									"Events"=>"activity/event",
									"Training"=>"activity/training",
									"Others"=>"activity/other",
									"RSVP"=>Array("activity/rsvp"),
										),
					"Forum"=>Array(
							"Forum Management"=>Array("forum/index","forum/addCategory","forum/updateCategory","forum/category","forum/thread")
							),
					"File Manager"=>Array(
							"File Manager"=>Array("file/index")
						),
					"Member's Management"=>Array(
									"List of Member"=>Array("member/lists"),
									"Change Member Password"=>Array("member/changePassword")
												),
							
					"Report "=>Array(
									
									"Google Analytics"=>Array("googleanalytics/test"),
									/*"Monthly Activity Report"=>Array("report/activityReport")*/
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
							"Message"=>Array("site/message","site/messageView"),
							"Newsletter"=>"newsletter/index"
									),/*
					"Cluster"=>Array(
							"Cluster List"=>Array("cluster/lists"),
							"Cluster Lead"=>Array("cluster/leadLists","cluster/leadAdd","cluster/sitelist","cluster/assign")
									),*/
					"Management"=>Array(
							"User"=>Array("user/lists","user/add","user/edit")
										),
					"Configuration"=>Array(
								"Parameters"=>Array("config/index"),
								"Article Category"=>Array("article/category"),
											),
					"Activities"=>Array(
							"Training"=>Array("activity/training","activity/trainingTypeAdd","activity/trainingTypeEdit")
						),
					"Forum"=>Array(
								"Category"=>Array("forum/category","forum/addCategory","forum/updateCategory")
									),
					"File Manager"=>Array(
							"File Manager"=>Array("file/index")
						),
					"Report"=>Array(
							"Monthly USP Project"=>"report/monthlyActivity",
							"Master Listing"=>"report/masterListing",
							"Google Analytics"=>"googleanalytics/report",
							"Monthly Activity Report"=>"report/getallActivityReport"
									),
					/*"Activities"=>"activity/index",
					"Reports"=>"reports/index"*/
							);

		return $menu[$level];
	}
}


?>