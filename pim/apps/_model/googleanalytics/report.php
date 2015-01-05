<?php
namespace model\googleanalytics;
use db, model;

class Report
{
	public function getGoogleReport($siteID,$start,$end)
	{


		db::select("*");
		db::where('siteID',$siteID);
		db::where('gaReportDate >=',$start);
		db::where('gaReportDate <=',$end);
		$result = db::get('ga_report')->result();

		return $result;
	}

	

	public function getGooglePage($siteID,$start,$end)
	{

		db::select("gaReportSitePage as page, SUM(`gaReportSitePageViews`) as views");
		db::where('siteID',$siteID);
		db::where('gaReportDate >=',$start);
		db::where('gaReportDate <=',$end);
		db::group_by('gaReportSitePage');
		db::order_by('views',DESC);

		$result = db::get('ga_report')->result();

echo $this->getLastSQL;


		return $result;
	}



	public function getGoogleViewPage($siteID,$page,$start,$end)
	{

		db::select("*");
		db::where('siteID',$siteID);
		db::where('gaReportSitePage',$page);
		db::where('gaReportDate >=',$start);
		db::where('gaReportDate <=',$end);
	//	db::group_by('gaReportDate');
		$result = db::get('ga_report')->result();


		return $result;
	}


	public function getLastDate()
	{

		db::select("gaReportDate ");
	
		db::limit(1);
		db::order_by('gaReportDate',DESC);
		
		$row = db::get('ga_report')->row();


		return $row;
	}


}


?>