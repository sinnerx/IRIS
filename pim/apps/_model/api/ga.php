<?php
namespace model\api;
use db,gapi;

// google analytic class
class Ga
{
	private $email = "hello@digitalgaia.com";
	private $password = "fireinth";
	private $profileid = "95746416";

	public function downloadReport($startDate, $endDate)
	{
		$email 		= $this->email;
		$password 	= $this->password;
		$profileid 	= $this->profileid;

		// apps/_library/gapi.php
		$ga = new gapi($email, $password);

		$reportTypes = array('pageviews','users');

		$ga->requestReportData($profileid, array('dateHour', 'pagePath'), $reportTypes, 'dateHour', null, $startDate, $endDate);


		foreach($ga->getResults() as $result)
		{
			$date = $result->getDateHour();
			$dates = array(substr($date, 0, 4), substr($date, 4, 2), substr($date, 6, 2));
			$date = implode("-", $dates)." ".substr($date, 8, 2);

			$siteSlug =  $this->getSegment("slug",$result->getPagePath());
			$siteID = db::where("siteSlug", $siteSlug)->get("site")->row('siteID');

			db::insert("ga_report", array(
				'siteID'=> $siteID,
				'gaReportDate'=> $date,
				'gaReportSiteSlug'=> $siteSlug),
				'gaReportSitePage'=> $this->getSegment("page",$result->getPagePath()),
				'gaReportSitePageViews'=> $result->getPageviews(),
				'gaReportSiteUsers'=> $result->getusers(),
				));
		}
	}

	private function getSegment($type,$path){

	

		list($segment1, $segment2, $segment3, $segment4, $segment5) = explode("/", $path);




		switch($type)
			{
				case "slug":
				
					if ($segment2 == "digitalgaia") {	$path = $segment4; 	} else {	$path = $segment2;	}

				break;
				case "page":
				

					if ($segment2 == "digitalgaia") {	$path = $segment5;	} else {	$path = $segment3;	}


				break;
			}
	 return $path;
	}

	
}