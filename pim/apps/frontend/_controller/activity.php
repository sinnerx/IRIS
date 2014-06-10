<?php
class Controller_Activity
{
	public function index()
	{
		$siteID	= model::load("access/auth")->getAuthData("current_site","siteID");
		$this->month	= request::named("month");
		$this->year		= request::named("year",date("Y")); ## 2014 as default, if date wasn't provided.
		
		## load instance.
		$activity		= model::load("activity/activity");

		## if no month parameter passed.
		if(!$this->month)
		{
			$this->res_activity	= $activity->getActivityList($siteID,$this->year,null,request::get("t"));
		}
		else
		{
			$this->res_activity	= $activity->getActivityList($siteID,$this->year,$this->month);
		}

		## typeR.
		$this->activityType	= $activity->type();

		## load month list.
		$this->monthR		= model::load("helper")->monthYear("month");
		
		if($this->month)
			return $this->index_month();
		else
			return $this->index_year();
	}

		## both index_month and index_year was loaded in activity/index, depending on flag.
		## you can literally pass the $this->res_activity, since both still use the same controller instance of index().
		## only, if the method is public.
		public function index_month()
		{
			$monthR	= $this->monthR;

			$data['res_activity']	= $this->res_activity;
			$data['monthLabel']		= $monthR[(int)$this->month];
			$data['activityType']	= $this->activityType;

			view::render("activity/index_month",$data);
		}

		public function index_year()
		{
			$data['typeR']			= $this->activityType;
			$data['typeLabel']		= $type = request::get("t")?$data['typeR'][request::get("t")]:"SEMUA";
			$data['monthR']			= $this->monthR;
			$data['year']			= $this->year;
			$data['res_activity']	= $this->res_activity;

			## get list of activityID.
			foreach($this->res_activity as $y=>$rowR)
			{
			}

			view::render("activity/index_year",$data);
		}

	## activity specific action.
	public function view($activitySlug)
	{
		$year		= request::named("year");
		$month		= request::named("month");
		$siteID		= model::load("access/auth")->getAuthData("current_site","siteID");
		$activity	= model::load("activity/activity");

		## get activity, based on siteID, slug, year and month
		$row_act	= $activity->getActivityBySlug($siteID,$activitySlug,$year,$month);

		## not found.
		if(!$row_act)
		{
			die;
		}

		## equate
		$data	= $row_act;

		//get activity specific data.
		switch($row_act['activityType'])
		{
			case 1:# event.
				$data['activityType']	= model::load("activity/event")->type($row_act['eventType']);
			break;
			case 2:# training.
				$data['activityType']	= model::load("activity/training")->type($row_act['trainingType']);
			break;
		}

		$data['activityTypeLabel']		= $activity->type($row_act['activityType']);

		## general data.
		# 1. if use pusat internet.
		$data['location']				= $row_act['activityAddressFlag'] == 1?"Pusat Internet":$row_act['activityAddress'];
		$data['activityParticipation']	= $activity->participationName($row_act['activityParticipation']);

		# 2. participation list.
		$data['participantList']	= model::load("activity/activity")->getParticipantList($row_act['activityID']);

		view::render("activity/view",$data);
	}

	private function calendar()
	{
		view::render("activity/partial/calendar");
	}


}



?>