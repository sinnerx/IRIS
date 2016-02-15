<?php
class Controller_Ajax_Googleanalytics
{	
	public function detail(){


	
	
		$page = request::get("page");
		$start = request::get("start");
		$end = request::get("end");



		$site	= model::load("site/site");
		$data1['stateR']	= model::load("helper")->state();


			## role for site manager check.
			if(!model::load("access/services")->roleCheck("siteEdit"))
			{
				redirect::to("../404","Can be accessed by site manager only.");
			}

			## get site based on manager.
			$data1['row']	= $site->getSiteByManager();
			$data1['startDate']	= $start;
			$data1['endDate']	= $end;

			$siteID	= $data1['row']['siteID'];
			$slug = $data1['row']['siteSlug'];

		$reportG	= model::load("googleanalytics/report")->getGoogleViewPage($siteID,$page,$start,$end);
	

		$data2["chart"] = array("type" => "line"); 
		$data2["title"] = array("text" => " Page Views"); 
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
					$totalUsers += $rowX['gaReportSitePageViews'];
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
		$data2["yAxis"] = array("title" => array("text" => "Views"),"allowDecimals" => false, "min" => 0); 

					$data['data2'] = $data2;
					$data['data1'] = $data1;

		view::render('sitemanager/googleanalytics/ajax/detail',$data);
		
	
	}
}
?>