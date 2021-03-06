<?php

## an ajax controller.
Class Controller_Ajax_Partial
{
	public function calendarGetDateList($year,$month)
	{
		## get current date list, based on month and year.
		$siteID	= model::load("access/auth")->getAuthData("current_site","siteID");

		## total date of current month/year.
		$total	= date("t",strtotime("$year-$month-1"));

		## get activities and group it by date.
		$activity	= model::load("activity/activity");
		$activityR	= $activity->getActivityList($siteID,$year,$month,null,false);
		$resultR	= Array();

		if($activityR)
		{
			$activityDateR	= $activity->getDate(array_keys($activityR));

			$helper	= model::load("helper");
			$base_url	= url::base("{site-slug}/activity");

			foreach($activityR as $actID=>$row)
			{
				$data	= Array(
							"activityName"=>$row['activityName'],
							"activityUrl"=>$helper->buildDateBasedUrl($row['activitySlug'],$row['activityStartDate'],$base_url),
							"activityStartDate"=>date("j M Y",strtotime($row['activityStartDate'])),
							"activityEndDate"=>date("j M Y",strtotime($row['activityEndDate']))
									);

				foreach($activityDateR[$actID] as $row_date)
				{
					$d	= date("j",strtotime($row_date['activityDateValue']));

					$resultR[$d]	= !is_array($resultR)?Array():$resultR[$d];
					$resultR[$d][]	= $data;
				}
			}
		}

		/*$dateR	= Array();
		
		$resultR	= Array();

		$base_url	= url::base("{site-slug}/activity");

		foreach($activityR as $row)
		{
			$range	= Array($row['activityStartDate'],$row['activityEndDate']);

			$startD	= date("j F Y",strtotime($row['activityStartDate']));
			$endD	= date("j F Y",strtotime($row['activityEndDate']));
			$url	= $helper->buildDateBasedUrl($row['activitySlug'],$row['activityStartDate'],$base_url);

			$data	= Array(
					"activityName"=>$row['activityName'],
					"activityUrl"=>$url,
					"activityStartDate"=>$startD,
					"activityEndDate"=>$endD
							);

			## extract date range into list date that hold item on every date.
			$helper->extractDateRange($resultR,$data,$range);
		}*/


		/*for($i=1;$i<=$total;$i++)
		{
			$dateR[$i]	= 0;

			## return total event for each day.
			if(isset($activityR[$i]))
			{
				$dateR[$i]	= count($activityR[$i]);
			}
		}*/

		return response::json($resultR);
	}

	/*public function calendarGetDayActivity($year,$month,$day)
	{
		$siteID		= model::load("access/auth")->getAuthData("current_site","siteID");
		$activityR	= model::load("activity/activity")->getActivityByDate($siteID,"$year-$month-$day");

		return response::json($activityR);
	}*/
}


?>