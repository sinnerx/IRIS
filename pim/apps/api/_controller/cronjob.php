<?php
class Controller_Cronjob
{
	public function downloadga()
	{

		$datetoday = date('Y-m-d');
	    $check	= model::load("googleanalytics/report")->getLastDate();
		
		if ($check['gaReportDate'] == null) {

			$startdate = null;
			// model::load("api/ga")->downloadReport(null,$datetoday);    //    arini-1bulan, arini 

		} else {
			$startdate = date('Y-m-d',(strtotime ( '+1 day' , strtotime ($check['gaReportDate']) ) ));

			if (date('Y-m-d',(strtotime ( '+1 day' , strtotime ($startdate)))) >= $datetoday)
				die("report already taken");
    	  	// model::load("api/ga")->downloadReport($lastplus1,$datetoday);    //    arini-1, arini 

		}

		model::load("api/ga")->downloadReport($startdate,$datetoday);    //    arini-1, arini 

	}
}