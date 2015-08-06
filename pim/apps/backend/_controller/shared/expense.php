<?php
class Controller_Expense
{
	public function __construct()
	{
	}


	public function listStatus()
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
		$data['listPR'] = model::load('expense/transaction')->getPRList($allSiteID,$level);
		$data['listRL'] = model::load('expense/transaction')->getRLList($allSiteID,$level);

		
		/*print_r("<pre>");
		print_r($data['listPR']);*/

		view::render("shared/expense/list",$data);		
	}

}


?>