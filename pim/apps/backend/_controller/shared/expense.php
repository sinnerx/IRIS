<?php
class Controller_Expense
{
	public function __construct()
	{
	}


	public function listStatus($page=1)
	{	
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();

			foreach($res_site as $key => $row)
			{
				$allSiteID[] = $row['siteID'];			
			}
			$level = \model\user\user::LEVEL_CLUSTERLEAD;

		} elseif (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER){

			$allSiteID = authData('site.siteID');
			$level = \model\user\user::LEVEL_SITEMANAGER;			

		} else {

			$allSiteID = "";			
		}
				

		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
		$data['listPR'] = model::load('expense/transaction')->getPRList($allSiteID,$level,$page);
				
		view::render("shared/expense/list",$data);		
	}


		public function listStatusRL()
	{	
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();

			foreach($res_site as $key => $row)
			{
				$allSiteID[] = $row['siteID'];			
			}
			$level = \model\user\user::LEVEL_CLUSTERLEAD;

		} elseif (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER){

			$allSiteID = authData('site.siteID');
			$level = \model\user\user::LEVEL_SITEMANAGER;			

		} else {

			$allSiteID = "";			
		}
				

		if(request::get("search"))
		{
			
			$data['listRL'] = model::load('expense/transaction')->getRLList($allSiteID,$level,$page,request::get("search"));
		} else {

			$data['listRL'] = model::load('expense/transaction')->getRLList($allSiteID,$level,$page);
		}

		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
				
		view::render("shared/expense/listRL",$data);		
	}

}


?>