<?php
Class Controller_GoogleAnalytics
{
	public function index()
	{
		$siteID					= model::load("site/site")->getSiteByManager(null,"siteID");
		$data['res_page']		= model::load("page/page")->listBySite($siteID);
		$data['pageDefault']	= model::load("page/page")->getDefault();

		view::render("sitemanager/page/index",$data);
	}



	public function report($siteID = null)
	{
		
		$start = input::get("startDate");
		$end = input::get("endDate");


		if ($end == "") {	$end = date('Y-m-d');	} 
			 
		if ($start == "") {	$start = date('Y-m-d',(strtotime ( '-5 day' , strtotime (date('Y-m-d')) ) )); }


		$site	= model::load("site/site");
		$data1['stateR']	= model::load("helper")->state();

		## root only.
		if($siteID)
		{
			## Access:roleCheck
			if(!model::load("access/services")->roleCheck("siteEditRoot"))
			{
				redirect::to("../404","Only root is permitted.");
			}

			$data1['row']	= $site->getSite($siteID);
		}
		else
		{
			## role for site manager check.
			if(!model::load("access/services")->roleCheck("siteEdit"))
			{
				redirect::to("../404","Can be accessed by site manager only.");
			}

			## get site based on manager.
			$data1['row']	= $site->getSiteByManager();
			$data1['startDate']	= $start;
			$data1['endDate']	= $end;

			$siteID	= authData('site.siteID');
			$slug = $data1['row']['siteSlug'];
				
		$reportG	= model::load("googleanalytics/report")->getGoogleReport($siteID,$start,$end);
		$reportP	= model::load("googleanalytics/report")->getGooglePage($siteID,$start,$end);



		$data2["chart"] = array("type" => "line"); 
		$data2["title"] = array("text" => " User Visit"); 
		$data2["credits"] = array("enabled" => false); 
		$data2["navigation"] = array("buttonOptions" => array("align" => "right")); 
		$data2["series"] = array("data" => array()); 
		$data2["xAxis"] = array("categories" => array()); 

		foreach($reportG as $no=>$rowX)
		{
			$date = date("Y-m-d", strtotime($rowX['gaReportDate']));

			$reps[$date][] = $rowX;
		}

		$categoryArray = array();
		$seriesArray = array();

		$i = 0;
		for($date = $start; $date <= $end;)
		{

			$categoryArray[] = $date;
			$totalUsers = 0;
			if(isset($reps[$date]))
			{
				foreach($reps[$date] as $rowX)
				{
					$totalUsers += $rowX['gaReportSiteUsers'];
				}
			}

			$seriesArray[] = $totalUsers;
			$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
		$i++;
		}

		$data2["series"] = array(array("name" => "Visits","data" => $seriesArray)); 
		$data2["xAxis"] = array("categories" => $categoryArray); 
		
		if ( $i >= 5) {
		$data2["xAxis"]["labels"]["enabled"] = false;
		}
		
		$data2["yAxis"] = array("title" => array("text" => "User"),"allowDecimals" => false, "min" => 0); 
}

		## param for non-root..
		$data['disabled']	= model::load("access/services")->roleCheck("siteEditRoot")?"":"disabled";

					$data['data3'] = $reportP;
					$data['data2'] = $data2;
					$data['data1'] = $data1;

		view::render("sitemanager/googleanalytics/report",$data);
		
	}

}	
?>