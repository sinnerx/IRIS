<?php
Class Controller_Site
{
	public function overview($year = null,$month = null)
	{
		$data['year'] = $year = $year ? : date("Y");
		$data['month'] = $month = $month ? : date("n");

		$siteID	= authData('site.siteID');


		//---------------------------------------------------------------------------
		## event

		$test	= model::load("blog/article")->getReportBySiteID($siteID,$year,$month);
		$totalEvent  = count($test);


		$event['maxEvent'] = 2;
		
			if ($totalEvent >= $event['maxEvent']) {
				$event['totalEvent'] = $event['maxEvent'];	
				$event['done'] = 1;
			} else {
				
				if ($totalEvent == "") {
					$event['totalEvent'] = 0;		
				}	else {
					$event['totalEvent'] = $totalEvent;		
				}

			}
			
		$data['event'] = $event;

		//---------------------------------------------------------------------------
		## user login
		
		$all = model::load("site/member")->getMemberListBySite($cols,null,null,null,null,null,$siteID);		
		$all = count($all);
		
			$cond =	Array(			
				"year(userLastLogin)"=>$year,
				"month(userLastLogin)"=>$month			
					);

		$active = model::load("site/member")->getMemberListBySite($cols,$cond,$join,$limit,$order,$pageConf,$siteID);		
		$active = count($active);

		$target = ($active/$all)*100;

		if ($target >= 60) { 
			$login['done'] = 1;	
		}

		$target = round((float)$target) . '%';
		$login['target'] = $target;
		

		$data['login'] = $login;

		//---------------------------------------------------------------------------

		## training hour

		$activitySlug = 2;

		$activityID = model::load("activity/activity")->getActivityIDListPerSlug($siteID,$year,$month,2,$groupBy);

		foreach($activityID as $no=>$row)
			{
				$totalTime = 0;

 				$start_time = strtotime($row['activityDateStartTime']); 	
 				$end_time = strtotime($row['activityDateEndTime']);

				$totalTime = $end_time - $start_time;
				$totalTime1 = $totalTime1 + $totalTime;				
			 	
			}

			$trainingHour = floor($totalTime1/3600);
			$training['maxHour'] = 48;

			if ($trainingHour >= $training['maxHour']) { 
				$training['done'] = 1;	
				$training['hour'] = 48;	
			} else {

					$training['hour'] = $trainingHour;
				
			}

			$data['training'] = $training;			
			  


		//--------------------------------------------------------------------
		##  getEntrepreneurshipBySlug


			$entClass = model::load("activity/activity")->getEntrepreneurshipBySlug($siteID,$month,$year);

			$totalClass = count($entClass);


			$entClass['maxClass'] = 1;

		

			if ($totalClass >= $entClass['maxClass']) {
				$entClass['totalEvent'] = $entClass['maxClass'];	
				$entClass['done'] = 1;
			} else {
				
				if ($totalClass == "") {
					$entClass['totalEvent'] = 0;		
				}	else {
					$entClass['totalEvent'] = $totalClass;		
				}

			}
			
			$data['entClass'] = $entClass;

		//--------------------------------------------------------------------
		##  get sales


			$sales = model::load("sales/sales")->getSales($siteID,$month,$year);

			$totalSale = $sales[0][totalSale];
			
			


			$entProgram['maxSale'] = 300;

		

			if ($totalSale >= $entProgram['maxSale']) {
				$entProgram['total'] = $entProgram['maxSale'];	
				$entProgram['done'] = 1;
			} else {
				
				if ($totalSale == "") {
					$entProgram['total'] = 0;		
				}	else {
					$entProgram['total'] = $totalSale;		
				}

			}
			
			$data['entProgram'] = $entProgram;




			//--------------------------------------------------------------------
			

		view::render("sitemanager/site/overview",$data);
	}
}