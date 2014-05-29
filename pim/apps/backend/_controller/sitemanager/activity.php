<?php
class Controller_Activity
{
	public function overview()
	{
		view::render("sitemanager/activity/overview");
	}

	public function add()
	{
		$data['eventTypeR']		= model::load("activity/event")->type();
		$data['trainingTypeR']	= model::load("activity/training")->type();
		$row_site				= model::load("site/site")->getSiteByManager(session::get("userID"));
		$data['siteInfoAddress']	= $row_site['siteInfoAddress'];

		if(form::submitted())
		{
			$rules	= Array(
					"activityName,activityType,activityParticipation,activityDate"=>"required:Required",
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
				/*$rules['trainingType']	= Array(
						"required:Required"
							);*/
				break;
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got error in your form.","error");
			}

			$data	= input::get();

			## convert date range label.
			$dateR	= model::load("helper")->dateRangeLabel(input::get("activityDate"),true);
			$data['activityStartDate']	= $dateR[0];
			$data['activityEndDate']	= $dateR[1];

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
		$data['res_album']	= model::load("image/album")->getActivityAlbum($activityID);

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