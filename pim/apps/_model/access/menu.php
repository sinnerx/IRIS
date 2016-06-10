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
					"Billing"=>Array(
						"Local Billing"=>"http://localhost/cafe",
						// "Edit Form"=>"billing/edit",
						"Daily Cash Process"=>"billing/dailyCashProcess",
						// "Daily Cash Process Redesign" => "billing/dailyCashProcessRedesign",
						"Daily Journal"=>"billing/dailyJournal",
						"Transaction Journal"=>"billing/transactionJournal"
						),
					"PI1M Expense"=>Array(
						// "List of PR"=>"expense/listStatus",
						// "List of RL"=>"expense/listStatusRL",
						'PR List' => array('exp/prList', 'exp/prEdit', 'exp/prEditCashAdvance'),
						"RL List" => array('exp/rlList', 'exp/rlEdit'),
						'PR Add' => 'exp/prAdd',
						// "Purchase Requisition"=>"expense/add"
						/*"Reconciliation List"=>"expense/reconciliation"			*/
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
									
									"Google Analytics"=>Array("googleanalytics/report"),
									/*"Monthly Activity Report"=>Array("report/activityReport")*/
												),
					"Learning" => Array(
									"Learning Page " => Array("../lms/"),
						),					
					"Attendance" => Array(
									"Attendance Page" => Array("../attendance/")
						),						
					"Asset" => Array(
									"Asset Management Page" => Array("../assetmgmt/public")
						)	
							);

					

		$menu['cl']	= Array(
					"Overview"=>Array(
								"KPI Overview"=>"kpi/kpi_overview/1",
								"Cluster Overview"=>Array("cluster/overview")
									),
					
					"Billing"=>Array(
						// "Input Form"=>"billing/add",
						// "Edit Form"=>"billing/edit",
						"Daily Cash Process"=>"billing/dailyCashProcess"
						),
					"PI1M Expense"=>Array(
						// "List of PR"=>"expense/listStatus",
						// "List of RL"=>"expense/listStatusRL",
						'PR List' => array('exp/prList', 'exp/prEdit', 'exp/prEditCashAdvance'),
						"RL List" => array('exp/rlList', 'exp/rlEdit')
						),
					//"Report"=>Array(
						// "Input Form"=>"billing/add",
						// "Edit Form"=>"billing/edit",
					//	"Reporting"=>"./attendance/reporting/"
						//),
					"Learning" => Array(
									"Learning Page " => Array("../lms/"),
						),								
					"Attendance" => Array(
									"Attendance Page" => Array("../attendance/")
						)				
							);

		$menu['fc']	= Array(					
					
					"Billing"=>Array(
						// "Input Form"=>"billing/add",
						"Daily Cash Process"=>"billing/dailyCashProcess"
						),

					"PI1M Expense"=>Array(
						// "List of PR"=>"expense/listStatus",
						// "List of RL"=>"expense/listStatusRL",
						'PR Cash Advance' => array('exp/prList', 'exp/prEdit', 'exp/prEditCashAdvance'),
						'RL List' => array('exp/rlList', 'exp/rlEdit')
					//	"Add new Category / Item"=>"expense/addCategory"
						),
					"Learning" => Array(
									"Learning Page " => Array("../lms/"),
						),					
					"Attendance" => Array(
									"Attendance Page" => Array("../attendance/")
						),
					"Asset" => Array(
									"Asset Management Page" => Array("../assetmgmt/public")
						)								
							);

		## root.
		$menu['r']	= Array(
					/*"Overview"=>"home/index",*/
					"Sites"=>Array(
							"KPI Overview"=>"kpi/kpi_overview/1",
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
							"User"=>Array("user/lists","user/add","user/edit"),
							"User Upgrade" => array('user/upgradeUser')
										),
					"Configuration"=>Array(
								"Parameters"=>Array("config/index"),
								"Article Category"=>Array("article/category"),
											),
					"Billing"=>Array(
							"Billing Items"=>"billing/add",
							// "Edit Form"=>"billing/edit",
							"Daily Cash Process"=>"billing/dailyCashProcess",
							"Daily Journal"=>"billing/dailyJournal",
							"Transaction Journal"=>"billing/transactionJournal"
						),
					
					"PI1M Expense"=>Array(
						// "List of PR"=>"expense/listStatus",
						'PR List' => 'exp/prList',
						'RL List' => 'exp/rlList'
						// "Add New Item"=>"expense/addNewItem",
						// "Edit PR Expenditure"=>"expense/editExpenditure"
						//"List of RL"=>"expense/listStatusRL"
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
							"Monthly Activity Report"=>"report/monthlyActivityReport"
							// "Quarterly Report"=>"report/showQuarterlyReportPage",
									),
					"Learning" => Array(
									"Learning Page " => Array("../lms/"),
						),
					"Attendance" => Array(
									"Attendance Page" => Array("../attendance/")
						),
					"Asset" => Array(
									"Asset Management Page" => Array("../assetmgmt/public")
						)					
					/*"Activities"=>"activity/index",
					"Reports"=>"reports/index"*/
							);

	## operation manager.
		$menu['om']	= Array(
					/*"Overview"=>"home/index",*/
			

					"PI1M Expense"=>Array(
						// "List of PR"=>"expense/listStatus",
						// "List of RL"=>"expense/listStatusRL",
						'PR List' => array('exp/prList', 'exp/prEdit', 'exp/prEditCashAdvance'),
						'RL List' => array('exp/rlList', 'exp/rlEdit')
					//	"Add new Category / Item"=>"expense/addCategory"
						),
					"Learning" => Array(
									"Learning Page " => Array("../lms/"),
						),					
					"Attendance" => Array(
									"Attendance Page" => Array("../attendance/")
						)	
							);

		$menu['dv'] = array(
			'Tasks' => 'task/index',
			'Assumption' => 'task/takeOver',
			'Billing' => array(
				'Codes' => array('billing/code')
				)
			);


		return $menu[$level];
	}
}


?>