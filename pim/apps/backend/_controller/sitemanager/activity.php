<?php
class Controller_Activity
{
	public function overview($page = 1)
	{
		$paginConf	= Array(
				"urlFormat"=>url::base("activity/overview/{page}"),
				"currentPage"=>$page
							);

		## listing type. default : upcoming.
		$type	= request::get("list","upcoming");
		$data['res_activity']	= model::load("activity/activity")->getRecentIncomingActivity(authData("site.siteID"),$paginConf,$type);
		view::render("sitemanager/activity/overview",$data);
	}

	public function edit($activityID)
	{
		$row						= model::load("activity/activity")->getActivity($activityID);

		$row['activityDateTime']	= Array(); ## to override to replaceData limits.
		$data['requestFlag']		= model::load("site/request")->replaceWithRequestData("activity.update",$activityID,$row);

		## check participation
		$data['hasParticipation']	= model::load("activity/activity")->getParticipant($activityID)?true:false;

		$Training					= model::load("activity/training")->getTrainingModule($row['trainingID']);
		$data['learningPackage'] 	= model::load("activity/learning")->package();
		
		//print_r($data['learningModule']);
		//print_r($Training);
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
				redirect::to("","Please correct the error highlighted.","error");
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

		if($Training[0]['packageID'] != ""){
			$row['learningSelection'] 	= 2;
			$row['package']				= $Training[0]['packageID'];
			$data['learningModule']		= model::load("activity/learning")->module(null,$row['package']);
			$row['module']				= $Training[0]['moduleID'];
		}
		else{
			$row['learningSelection'] = 1;
		}
		$data['learningSelection'] = model::load("activity/training")->getLearningSelection();

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
		$data['learningPackage'] = model::load("activity/learning")->package();
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
				redirect::to("","Please correct the error highlighted.","error");
			}

			$data	= input::get();

			## decode activityDateTime.
			$datetime	= json_decode($data['activityDateTime'],true);
			$data['activityStartDate']		= $datetime['startDate']." ".$datetime['timeList'][$datetime['startDate']]['start'];
			$data['activityEndDate']		= $datetime['endDate']." ".$datetime['timeList'][$datetime['endDate']]['end'];
			$data['activityDateTimeType']	= $datetime['dateTimeType'];
			$data['activityDateTime']		= $datetime['timeList'];

			## win.
			model::load("activity/activity")->addActivity($row_site['siteID'],input::get("activityType"),$data, input::get("learningSelect"));

			redirect::to("","New activity has been submitted, and is pending for cluster lead approval.");
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

		## get related article.
		$data['res_article']	= model::load("activity/activity")->getRelatedArticle($activityID);

		view::render("sitemanager/activity/view",$data);
	}

	public function delete($id)
	{
		// db::where('activityID', $id)->update('activity', array('activityDeleted', 1));
		$activity = model::orm('activity/activity')->find($id);

		// save this record first.
		$type = $activity->activityType;
		$name = $activity->activityName;
		$pages = array(1 => 'event', 2 => 'training');
		
		// if has already been approved.
		if($activity->activityApprovalStatus == 1)
			redirect::to('activity/'.$pages[$type], 'Unable to delete an already approved activity.', 'error');
		
		// delete.
		$activity->delete();

		redirect::to('activity/'.$pages[$type], 'Activity \''.$name.'\' has been deleted.');
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

	public function other($page = 1)
	{
		$siteID	= authData("site.siteID");

		$data['activity']	= model::load("activity/activity");
		$data['res_other']	=$data['activity']->getPaginatedActivityList($siteID,99,url::base("activity/other/{page}"),$page);

		view::render("sitemanager/activity/other",$data);
	}

	public function rsvp()
	{
		$data['res_occured_activity']	= model::load("activity/activity")->getOccuredActivities(authData("site.siteID"));

		view::render("sitemanager/activity/rsvp",$data);
	}
}