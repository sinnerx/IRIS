<?php
namespace model\api;

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

		$ga->requestReportData($profileid, array('date', 'pagePathLevel3', 'pagePathLevel4'), $reportTypes, 'date', null, $startDate, $endDate);

		foreach($ga->getResults() as $result)
		{
			$date = $result->getDate();
			$dates = array(substr($date, 0, 4), substr($date, 4, 2), substr($date, 6, 2));
			$date = implode("-", $dates);

			db::insert("ga_report", array(
				'siteID'=> $siteID,
				'gaReportDate'=> $date,
				'gaReportSiteSlug'=> $slug,
				'gaReportSitePage'=> $this->buildPage($result),
				'gaReportSitePageViews'=> $result->getPageviews(),
				'gaReportSiteUsers'=> $result->getusers(),
				));
		}
	}

	private function buildPage($result)
	{
		return $result->getPagePathLevel4();
	}
}