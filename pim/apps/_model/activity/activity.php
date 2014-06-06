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

	public function getParticipant($activityID)
	{
		db::select("userIC,userProfileFullName");
		db::where("activityID",$activityID);
		db::join("user","user.userID = activity_user.userID");
		db::join("user_profile","user_profile.userID = activity_user.userID");

		return db::get("activity_user")->result();
	}

	public function createLinkWithArticle($activityID,$articleID)
	{
		## check if already linked.
		$check	= db::where("articleID",$articleID)
		->where("activityID",$activityID)
		->get("activity_article")->row();

		if($check)
			return false;

		## create links.
		db::insert("activity_article",Array(
								"activityID"=>$activityID,
								"articleID"=>$articleID,
								"activityArticleCreatedDate"=>now(),
								"activityArticleCreatedUser"=>session::get("userID")
											));
	}

	## return list of incoming and previous activity
	public function getUnlinkedActivity($siteID,$year,$month,$flag = "both")
	{
		$currDate	= date("Y-m-d H:i:s");

		$where	= Array();
		db::from("activity");
		db::where("siteID",62);

		##### 1.1. select by month, and year.
		#db::where("year(activityStartDate)",$year);
		#db::where('year(activityEndDate)')

		## 1.2. previous activity
		## where.
		if($flag == "previous")
		{
			db::where("activityEndDate <",$currDate);
		}

		## 1.3. incoming activity
		if($flag == "incoming")
		{
			db::where("activityStartDate >",$currDate);
		}

		## 1.4. no report about this activity yet, or not linked yet.
		db::where("activityID NOT IN (SELECT activityID FROM activity_article WHERE activityArticleType != 1 OR activityID != activity.activityID)");

		##+execute+##
		$res	= db::get()->result("activityID");

		//rebuild.
		$newRes		= Array();
		$next		= false;
		$previous 	= false;

		foreach($res as $row)
		{
			$rYear	= date("Y",strtotime($row['activityStartDate']));
			$rMonth	= date("n",strtotime($row['activityStartDate']));

			## current month result.
			if($rYear == $year && $rMonth  == $month)
			{
				$newRes[]	= $row;
			}
			else
			{
				if(($rYear == $year && $rMonth > $month) || ($rYear > $year))
				{
					$next = true;
				}

				if(($rYear == $year && $rMonth < $month) || ($rYear < $year))
				{
					$previous = true;
				}
			}
		}

		return Array("result"=>$newRes,"previous"=>$previous,"next"=>$next);
	}

	## return previous month, and next month.
	public function checkByMonth($siteID,$year,$month)
	{
	}
}


?>