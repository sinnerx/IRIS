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

	public function blastNewsletter()
	{
		$result = model::orm('site/site')->execute();

		$time = microtime(true);

		$date = date('Y-m-d');
		if(model::load('site/newsletter/push')->hasPushed($date))
			die('Today`s newsletter has been pushed.');

		// initiate push
		$push = model::orm('site/newsletter/push')->create();
		$push->siteNewsletterPushStatus = 0;
		$push->siteNewsletterPushDate = date("Y-m-d H:i:s");
		$push->save();

		$total = array();
		$total['samecontent'] = 0;
		$total['empty'] = 0;
		$total['totalsite'] = 0;

		foreach($result as $site)
		{
			db::select('siteMemberID');
			$members = $site->getMailedMembers();

			if(count($members) == 0)
				continue;

			$siteNL = $site->getSiteNewsletter();

			if($siteNL->isConnected())
			{
				if($siteNL->siteNewsletterEdited == 0)
					$total['samecontent']++;

				if($siteNL->siteNewsletterTemplate == '' || $siteNL->siteNewsletterSubject == '')
				{
					$total['empty']++;
					continue;
				}

				$total['totalsite']++;
				$site->getSiteNewsletter()->mail(true);
			}
		}

		// update the record.
		$push->siteNewsletterPushStatus = 1;
		$push->siteNewsletterPushSameContent = $total['samecontent'];
		$push->siteNewsletterPushEmpty = $total['empty'];
		$push->siteNewsletterPushTotalSite = $total['totalsite'];
		$push->save();

		echo 'pushed.';
	}
}