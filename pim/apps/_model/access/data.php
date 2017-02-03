<?php
namespace model\access;

class Data
{
	## used in backend main route. return true, if shared, else if otherwise.
	public function sharedController($controller = null,$method = null,$level = null)
	{
		if($controller != null && $method != null)
		{
			list($method) = explode("/",$method); ##get first puff.
		}

		$access	= Array(
					"auth/login"=>Array("sm","r","om","cl"),
					"auth/logout"=>Array("sm","r","om","cl"),
					"home/index"=>Array("sm","r","om"),
					"site/info"=>Array("sm","r","om"),
					"site/edit"=>Array("sm","r","om"),
					"site/slider"=>Array("sm","r","om"),
					"site/slider_edit"=>Array("sm","r","om"),
					"site/deleteSlider"=>Array("sm","r","om"),
					"site/announcement"=>Array("sm","r","om"),
					"site/article"=>Array("sm","r","om"),
					"site/addArticle"=>Array("sm","r","om"),
					"site/editArticle"=>Array("sm","r","om"),
					"site/deleteArticle"=>Array("sm","r","om"),
					"site/undeleteArticle"=>Array("sm","r","om"),
					"site/announcement_add"=>Array("sm","r","om"),
					"site/editAnnouncement"=>Array("sm","r","om"),
					"site/deleteAnnouncement"=>Array("sm","r","om"),
					"site/undeleteAnnouncement"=>Array("sm","r","om"),
					"manager/edit"=>Array("r","om","sm"),
					"user/profile"=>Array("r","om","sm","cl", "dv"),
                                        "user/changeProfileImage"=>Array("r","om","sm","cl", "dv"),
                                        "user/profileUploadAvatar"=>Array("r","om","sm","cl", "dv"),
					"user/changePassword"=>Array("r","om","sm","cl", "dv", 'fc', 'hq'),
					"site/message"=>Array("r","om"/*,"sm"*/,"cl"),
					"site/messageView"=>Array("r","om"/*,"sm"*/,"cl"),
					"file/index"=>Array("r","om", "sm"),
					"file/addFolder"=>Array("r","om", "sm"),
					"file/addFile"=>Array("r","om", "sm"),
					"billing/add"=>Array("r","om","cl", "sm"),
					"billing/addItem"=>Array("r","om", "sm"),
					"billing/addPoint"=>Array("r"),
					"billing/editPoint"=>Array("r"),
					"billing/deletePoint"=>Array("r"),
					"billing/editItem"=>Array("r","om", "sm"),
					"billing/addTransaction"=>Array("r","om","cl", "sm"),
					"billing/editTransaction"=>Array("r","om","cl", "sm"),
					"billing/unlockTransaction"=>Array("r","om","cl"),
					"billing/edit"=>Array("r","om", "cl", "sm"),
					"billing/dailyCashProcess"=>Array("r","om", "cl", "sm", "fc"),
                    "billing/dailyCashProcessSummary"=>Array("r","fc", "om", "cl"),
					"billing/dailyCashProcessOld"=>Array("r","om", "cl", "sm", "fc"),
					"billing/dailyJournal"=>Array("r","om", "sm"),
					"billing/transactionJournal"=>Array("r","om", "sm"),
					"billing/editForm"=>Array("r","om", "cl", "sm"),
					"billing/delete"=>Array("r","om", "sm", "cl"),
					"billing/settlement"=>Array("r","om", "sm"),
					"billing/deleteItem"=>Array("r","om"),
					"billing/dailyCashProcessRedesign" => array('sm'),
					"kpi/kpi_overview"=>Array("cl","r","om"),								
					"kpi/kpi_overview2"=>Array("cl","r","om"),								
					"kpi/kpi_summary"=>Array("cl","r","om"),								
					"expense/listStatus"=>Array("sm","cl","om","fc"),		
					"expense/listStatusRL"=>Array("sm","cl","om","fc"),
					"expense/getFormSuccess"=>Array("fc"),
					"expense/viewRListSuccess"=>Array("fc"),
					"expense/viewFile"=>Array("fc"),
					"expense/fileImage"=>Array("fc"),
					"expense/fcClose"=>Array("fc"),
					"expense/generateRLReport"=>Array("sm","cl","om","fc"),
					'exp/prList' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlList' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/prView' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/prEdit' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/prPrint' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlChangeMonth' => array('fc', 'r'),
					'exp/prEditCashAdvance' => array('sm', 'cl', 'fc', 'om', 'r'),
					'exp/prCashAdvancePrint' => array('sm', 'cl', 'fc', 'om', 'r'),
					'exp/prReject' => array('cl', 'om'),
					'exp/prDelete' => array('sm', 'cl', 'om'),
					'exp/prRejectForm' => array('cl', 'om'),
					'exp/prRejectionReason' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/listItem' => array('sm', 'cl', 'om', 'r', 'fc'),
					'exp/submitPrNumber' => array('r'),
					'exp/editPrNumber' => array('r'),
					'exp/rlEdit' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlPrint' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlFileUpload' => array('sm'),
					'exp/rlFile' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlApproval' => array('cl', 'om', 'fc'),
					'exp/rlDelete' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlDownload' => array('sm', 'cl', 'om', 'fc', 'r'),
					'exp/rlRejectForm' => array('cl', 'om', 'fc', 'r'),
					'exp/rlRejectionReason' => array('sm', 'cl', 'om', 'fc', 'r'),
					'expExcel/prCashAdvanceDownload' => array('fc', 'r'),
					'expExcel/rlSummaryGenerate' => array('sm', 'cl', 'om', 'fc', 'r'),
					'expExcel/getDailyCashProcess' => array('sm', 'cl', 'om', 'fc', 'r'),
					// 'exp/rlView' => array('sm', 'cl', 'om', 'fc'),
					'ajax_unlockTransaction/checkSite' => array('sm', 'cl', 'om', 'fc', 'r'),
							);

		if($level && isset($access["$controller/$method"]))
		{
			$level	= is_numeric($level)?$this->accessLevelCode($level):$level;

			return in_array($level,$access["$controller/$method"]);
		}

		return false;
	}

	public function publicBackend()
	{
		$list	= Array(
					"auth/login",
					"auth/resetPassword",
					"auth/authenticateToken"
						);

		return $list;
	}

	public function accessController($id)
	{
		$levelR	= Array(
				2=>"sitemanager",
				3=>"clusterlead",
				4=>"operationmanager",
				5=>"financialcontroller",
				6=>'hqadmin',
				99=>"root",
				999=>"developer"
						);

		return $levelR[$id];
	}

	public function firstLoginLocation($level)
	{
		$locR	= Array(
					2=>"site/overview",
					3=>"cluster/overview",
					4=>"exp/prList",
					5=>"exp/prList",
					6=>'overview/index',
					99=>"site/index",
					999=>'task/index'
							);

		return $locR[$level];
	}

	public function accessLevelCode($id)
	{
		$levelR	= Array(
				2=>"sm",
				3=>"cl",
				4=>"om",
				5=>'fc',
				6=>'hq',
				99=>"r",
				999=>'dv'
						);

		return $levelR[$id];
	}

	## list of role;
	public function roleList()
	{
		$role	= Array(
				"siteEditRoot"=>Array("r"), ## in site/edit
				"siteEdit"=>Array("sm")
						);

		return $role;
	}

	## obsolete, no longer used.
	public function accessList()
	{
		$access	= Array(
				"auth/login"=>Array("sm","r"),
				"auth/logout"=>Array("sm","r","cl"),
				"home/index"=>Array("sm","r"),
				"page/index"=>Array("sm","cl"),
				#"site/index"=>Array("r"),
				"site/info"=>Array("sm","r"),
				#"site/add"=>Array("r"),
				#"site/edit"=>Array("sm","r"),
				#"site/slider"=>Array("sm","r"),
				#"site/slider_edit"=>Array("sm","r"),
				#"user/list"=>Array("r"),
				#"manager/add"=>Array("r"),
				#"manager/lists"=>Array("r"),
				#"manager/edit"=>Array("r","sm"),
				"clusterlead/lists"=>Array("r"),
				"clusterlead/add"=>Array("r"),
				"clusterlead/sitelist"=>Array("r"),
				#"cluster/lists"=>Array("r"),
				#"cluster/assign"=>Array("r"),
				#"cluster/unassign"=>Array("r"),
						);

		return $access;
	}
}

?>