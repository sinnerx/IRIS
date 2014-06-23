<?php
class Controller_Activity
{
	public function overview()
	{
		view::render("sitemanager/activity/overview");
	}

	public function edit($activityID)
	{
		$row						= model::load("activity/activity")->getActivity($activityID);

		$row['activityDateTime']	= Array(); ## to override to replaceData limits.
		$data['requestFlag']		= model::load("site/request")->replaceWithRequestData("activity.update",$activityID,$row);

		## check participation
		$data['hasParticipation']	= model::load("activity/activity")->getParticipant($activityID)?true:false;

		if(form::submitted())
		{
			$rules	= Array(
					"activityName,activityParticipation,activityDateTime"=>"required:Required",
							);

			if(!$data['hasParticipation'])
				$rules['activityAllDateAttendance'] = "required:Required";

			## rule based on type.
			switch($row['activityType'])
			{
				case 1:
				$rules['eventType']	= Array(
						"required:Required"
							);
				break;
				case 2:
				$rules['trainingType']	= Array(
						"required:Required"
							);
				break;
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got error in your form.","error");
			}

			$data	= input::get();

			## decode activityDateTime.
			$datetime	= json_decode($data['activityDateTime'],true);
			$data['activityStartDate']		= $datetime['startDate']." ".$datetime['timeList'][$datetime['startDate']]['start'];
			$data['activityEndDate']		= $datetime['endDate']." ".$datetime['timeList'][$datetime['endDate']]['end'];
			$data['activityDateTimeType']	= $datetime['dateTimeType'];

			if(!$data['hasParticipation']): ## if has participation already, deny this data.
			$data['activityDateTime']		= $datetime['timeList'];
			endif;

			## win
			model::load("activity/activity")->updateActivity($activityID,$row['activityType'],$data);

			redirect::to("","Activity has been updated");
		}

		$data['eventTypeR']		= model::load("activity/event")->type();
		$data['trainingTypeR']	= model::load("activity/training")->type();
		$data['row']			= $row;
		$row_site				= model::load("access/auth")->getAuthData("site");
		$data['siteInfoAddress']	= $row_site['siteInfoAddress'];

		## prepare activityDateTime input.
		$datetime['startDate']		= date("Y-m-d",strtotime($row['activityStartDate']));
		$datetime['endDate']		= date("Y-m-d",strtotime($row['activityEndDate']));
		$datetime['dateTimeType']	= $row['activityDateTimeType'];
		
		### timelist.
		if(!$data['requestFlag'])
		{
			$res_date	= model::load("activity/activity")->getDate($activityID);

			$datetime['timeList']		= Array();
			foreach($res_date as $row)
			{
				$datetime['timeList'][$row['activityDateValue']]['start']	= $row['activityDateStartTime'];
				$datetime['timeList'][$row['activityDateValue']]['end']		= $row['activityDateEndTime'];
			}
		}
		## re-use the stored one.
		else
		{
			$datetime['timeList']		= $row['activityDateTime'];
		}
		$data['datetime']			= json_encode($datetime);

		view::render("sitemanager/activity/edit",$data);
	}

	public function add()
	{
		$data['eventTypeR']		= model::load("activity/event")->type();
		$data['trainingTypeR']	= model::load("activity/training")->type();
		$row_site				= model::load("access/auth")->getAuthData("site");
		$data['siteInfoAddress']	= $row_site['siteInfoAddress'];

		if(form::submitted())
		{
			$rules	= Array(
					"activityName,activityType,activityParticipation,activityDateTime,activityAllDateAttendance"=>"required:Required",
							);

			## rule based on type.
			switch(input::get("activityType"))
			{
				case 1:
				$rules['eventType']	= Array(
						"required:Required"
							);
				break;
				case 2:
				$rules['trainingType']	= Array(
						"required:Required"
							);
				break;
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got error in your form.","error");
			}

			$data	= input::get();

			## decode activityDateTime.
			$datetime	= json_decode($data['activityDateTime'],true);
			$data['activityStartDate']		= $datetime['startDate']." ".$datetime['timeList'][$datetime['startDate']]['start'];
			$data['activityEndDate']		= $datetime['endDate']." ".$datetime['timeList'][$datetime['endDate']]['end'];
			$data['activityDateTimeType']	= $datetime['dateTimeType'];
			$data['activityDateTime']		= $datetime['timeList'];

			## win.
			model::load("activity/activity")->addActivity($row_site['siteID'],input::get("activityType"),$data);

			redirect::to("","Yes you win.");
		}

		view::render("sitemanager/activity/add",$data);
	}

	public function view($typeName,$activityID)
	{
		$typeR	= Array("event"=>1,"training"=>2);
		$type	= $typeR[$typeName];

		$data['activity']	= model::load("activity/activity");
		$data['row']		= $data['activity']->getActivity($activityID);

		$data['typeName']	= $typeName;

		## 
		$data['activityID']	= $activityID;

		## get album.
		$data['imageServices']	= model::load("image/services");
		$data['res_album']		= model::load("image/album")->getActivityAlbum($activityID);

		## get participants.
		$data['res_participant']	= model::load("activity/activity")->getParticipant($activityID);

		## get activity date.
		$data['activityDate']	= model::load("activity/activity")->getDate($activityID);

		view::render("sitemanager/activity/view",$data);
	}

	public function event($page = 1)
	{
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");
		$data['activity']	= model::load("activity/activity");
		$data['res_event']	= $data['activity']->getPaginatedActivityList($siteID,1,url::base("activity/event/{page}"),$page);

		view::render("sitemanager/activity/event",$data);
	}

	public function training($page = 1)
	{
		$siteID					= model::load("access/auth")->getAuthData("site","siteID");

		$data['activity']		= model::load("activity/activity");
		$data['res_training']	= $data['activity']->getPaginatedActivityList($siteID,2,url::base("activity/training/{page}"),$page);
		$data['training']		= model::load("activity/training");

		view::render("sitemanager/activity/training",$data);
	}
}