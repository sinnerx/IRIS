<?php
namespace model\activity;
use model, db, session, pagination;
class Activity
{
	public function type($no = null)
	{
		$arr	= Array(
				1=>"Event",
				2=>"Training"
						);

		return $no?$arr[$no]:$arr;
	}

	public function getActivity($activityID,$col = null)
	{
		db::where("activity.activityID",$activityID);

		db::select($col);

		## if got siteID, must be based on siteID check
		if($siteID = model::load("access/auth")->getAuthData("site","siteID"))
		{
			db::where("siteID",$siteID);
		}

		db::join("event","activityType = '1' AND activity.activityID = event.activityID");
		db::join("training","activityType = '2' AND activity.activityID = training.activityID");

		return db::get("activity")->row($col);
	}

	## get all activity by date.
	public function getAllActivity($siteID = null,$year = null,$month = null)
	{
		$year	= 2012;
		$month	= 6;

		db::or_where(Array(
				"year(activityStartDate) = ? AND month(activityStartDate) = ?"=>Array($year,$month),
				"year(activityEndDate) = ? AND month(activityEndDate) = ?"=>Array($year,$month)
						));
		db::get("activity")->result();
	}

	## if got activityID, use the date of that activityID instead.
	public function refineActivitySlug($slug,$date,$activityID = null)
	{
		$year	= date("Y",strtotime($date));
		$month	= date("n",strtotime($date));

		## check original slug.
		## if already exists.

		db::select("activityID");
		db::where("year(activityStartDate)",$year);
		db::where("month(activityStartDate)",$month);
		db::where("activitySlugOriginal",$slug);

		if($activityID)
		{
			db::where("activityID !=",$activityID);
		}

		db::get("activity");
		$res_slug	= db::result();

		if(!$res_slug)
			return $slug;

		return $slug."-".(count($res_slug)+1);
	}

	public function addActivity($siteID,$type,$data)
	{
		## create slug by activity name.
		$originalSlug	= model::load("helper")->slugify($data['activityName']);
		$activitySlug	= $this->refineActivitySlug($originalSlug,$data['activityStartDate']);

		$data_activity	= Array(
					"activityType"=>$type,
					"siteID"=>$siteID,
					"activityName"=>$data['activityName'],
					"activityAddressFlag"=>$data['activityAddressFlag'],
					"activityAddress"=>$data['activityAddress'],
					"activityDescription"=>$data['activityDescription'],
					"activityParticipation"=>$data['activityParticipation'],
					"activityStartDate"=>$data['activityStartDate'],
					"activityEndDate"=>$data['activityEndDate'],
					"activityApprovalStatus"=>0,
					"activityCreatedUser"=>session::get("userID"),
					"activityCreatedDate"=>now(),
					"activitySlug"=>$activitySlug,
					"activitySlugOriginal"=>$originalSlug,
								);

		db::insert("activity",$data_activity);
		$activityID	= db::getLastID("activity","activityID");

		switch($type)
		{
			case 1: # event.
				$data_event	= Array(
						"activityID"=>$activityID,
						"eventType"=>1,
						"eventCreatedUser"=>session::get("userID"),
						"eventCreatedDate"=>now()
									);

				db::insert("event",$data_event);
			break;
			case 2: # training.
				$data_training	= Array(
						"activityID"=>$activityID,
						"trainingType"=>$data['trainingType'],
						"trainingMaxPax"=>$data['trainingMaxPax']
										);

				db::insert("training",$data_training);
			break;
		}

		## create request
		model::load("site/request")->create("activity.add",$siteID,$activityID,Array());
	}

	public function participationName($no = null)
	{
		$arr	= Array(
				1=>"Open For All",
				2=>"Only to members"
						);

		return $no?$arr[$no]:$arr;
	}

	public function getPaginatedActivityList($siteID,$type,$urlFormat,$page)
	{
		db::from("activity")
		->where("siteID",$siteID)
		->where("activityType",$type);

		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
		pagination::initiate(Array(
				"urlFormat"=>$urlFormat,
				"currentPage"=>$page,
				"limit"=>10,
				"totalRow"=>db::num_rows()
					));

		switch($type)
		{
			case 1: ## event
				db::join("event","activity.activityID = event.activityID");
			break;
			case 2:
				db::join("training","activity.activityID = training.activityID");
			break;
		}

		db::order_by("activityCreatedDate","desc");
		db::limit(10,pagination::recordNo()-1);

		return db::get()->result();
	}
}


?>