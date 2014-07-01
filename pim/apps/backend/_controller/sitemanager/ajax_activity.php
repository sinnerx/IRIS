<?php
class Controller_Ajax_Activity
{
	public function incoming($articleID = 0){
		$type = 'incoming';
		$year = request::get('year',date("Y"));
		$month = request::get('month',date("n"));
		$siteID = model::load('access/auth')->getAuthData("site","siteID");

		//var_dump($type." ".$year." ".$month." ".$siteID);die;

		$data = model::load("activity/activity")->getUnlinkedActivity($siteID, $year, $month, $type, $articleID);

		if($month == 12){
			$data['nextyear'] = $year+1;
			$data['nextmonth'] = 1;
		}else if($month == 1){
			$data['previousyear'] = $year-1;
			$data['previousmonth'] = 12;
		}else{
			$data['nextyear'] = $year;
			$data['nextmonth'] = $month+1;
			$data['previousyear'] = $year;
			$data['previousmonth'] = $month-1;
		}
		$data['type'] = $type;
		$data['year'] = $year;
		$data['month'] = $month;
		$data['ref'] = 1;
		$data['articleID'] = $articleID;
		//echo '<pre>';print_r($data);die;
		view::render("sitemanager/activity/ajax/incoming", $data);
	}
	public function previous($articleID = 0){
		$type = 'previous';
		$ref = request::get('ref')?request::get('ref'):0;
		$year = request::get('year',date("Y"));
		$month = request::get('month',date("n"));
		$siteID = model::load('access/auth')->getAuthData("site","siteID");

		//var_dump($type." ".$year." ".$month." ".$siteID);die;

		$data = model::load("activity/activity")->getUnlinkedActivity($siteID, $year, $month, $type, $articleID);

		if($month == 12){
			$data['nextyear'] = $year+1;
			$data['nextmonth'] = 1;
		}else if($month == 1){
			$data['previousyear'] = $year-1;
			$data['previousmonth'] = 12;
		}else{
			$data['nextyear'] = $year;
			$data['nextmonth'] = $month+1;
			$data['previousyear'] = $year;
			$data['previousmonth'] = $month-1;
		}
		$data['type'] = $type;
		$data['year'] = $year;
		$data['month'] = $month;
		$data['ref'] = $ref;
		$data['articleID'] = $articleID;
		//echo '<pre>';print_r($data);die;
		view::render("sitemanager/activity/ajax/previous", $data);
	}

	public function datePicker()
	{
		view::render("sitemanager/activity/ajax/datePicker",$data);
	}

	//used by dateConfiguration().
	public function datePicker_calendar($year = null,$month = null)
	{
		$year	= !$year?date("Y"):$year;
		$month	= !$month?date("n"):$month;

		$calendar	= new Calendar();

		for($i=1;$i<=31;$i++)
		{
			$text	= Array();

			$text[]	= "<div class='day-label'>$i</div>";

			$date				= date("Y-m-d",strtotime("$year-$month-$i"));
			$dateR[$i]['text']	= implode("",$text);
			$dateR[$i]['attr']	= "data-date='".$date."' id='date-$date'";
		}

		$config['month']	= $month;
		$config['year']		= $year;
		$config['dateR']	= $dateR;
		$calendar->setConfig($config);

		$data['calendar']	= $calendar;
		$data['monthLabel']	= date("F",strtotime("2014-$month-01"));
		$data['yearLabel']	= $year;
		$data['month']		= $month;
		$data['year']		= $year;

		view::render("sitemanager/activity/ajax/datePicker_calendar",$data);
	}

	public function calendarGetDateList($year,$month)
	{
		## get current date list, based on month and year.
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");

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
			$typeEngR	= Array(1=>"event",2=>"training");

			foreach($activityR as $actID=>$row)
			{
				$data	= Array(
							"activityID"=>$row['activityID'],
							"activityTypeEng"=>$typeEngR[$row['activityType']],
							"activityType"=>$activity->type($row['activityType']),
							"activityName"=>$row['activityName'],
							"activityStartDate"=>date("j M Y",strtotime($row['activityStartDate'])),
							"activityEndDate"=>date("j M Y",strtotime($row['activityEndDate'])),
							"activityCreatedUser"=>$row['userProfileFullName']
									);

				foreach($activityDateR[$actID] as $row_date)
				{
					$d	= date("j",strtotime($row_date['activityDateValue']));

					$resultR[$d]	= !is_array($resultR)?Array():$resultR[$d];
					$resultR[$d][]	= $data;
				}
			}
		}

		return response::json($resultR);
	}
}
?>