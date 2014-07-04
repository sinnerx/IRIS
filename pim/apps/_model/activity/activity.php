<?php
namespace model\activity;
use model, db, session, pagination;
class Activity
{
	public function type($no = null)
	{
		$arr	= Array(
				1=>"Program",
				2=>"Latihan"
						);

		return $no?$arr[$no]:$arr;
	}

	public function dateObligation($no = null)
	{
		$arrR	= Array(1=>"All date required",2=>"Participant may choose date");

		return $no?$arrR[$no]:$arrR;
	}

	public function getActivityByDate($siteID,$date)
	{
		db::where("siteID",$siteID);
		#db::where("activityApprovalStatus",1);
		db::where("date(activityStartDate)",$date);

		db::join("event","activityType = '1' AND activity.activityID = event.activityID");
		db::join("training","activityType = '2' AND activity.activityID = training.activityID");

		return db::get("activity")->result();
	}

	public function getActivityList($siteID,$year = null,$month = null,$type = null,$groupBy = null)
	{
		db::where("siteID",$siteID);
		db::where("activityApprovalStatus",1); # approved.

		## if got type parameter.
		if($type)
		{
			db::where("activityType",$type);
		}

		if($year)
		{
			//select month for later grouping..
			db::select("*,activity.*,month(activityStartDate) as month, day(activityStartDate) as day");
			db::where("year(activityStartDate)",$year);
		}

		if($groupBy === null)
		{
			if($month)
			{
				$groupBy	= "activityType";
				$secondGroupBy = true;
			}
			else
			{
				$groupBy		= "month";
				$secondGroupBy	= true;
			}
		}
		else if($groupBy === false)
		{
			$groupBy		= "activityID";
			$secondGroupBy	= null;
		}
		else
		{
			## set.
			$secondGroupBy = true;
		}

		if($month)
		{
			db::where("month(activityStartDate)",$month);
		}
		

		db::order_by("activityStartDate","desc");
		db::join("event","activityType = '1' AND activity.activityID = event.activityID");
		db::join("training","activityType = '2' AND activity.activityID = training.activityID");
		db::join("user_profile","user_profile.userID = activity.activityCreatedUser");

		## return and grouped type key.
		return db::get("activity")->result($groupBy,$secondGroupBy);
	}

	## get activity.
	public function getActivityBySlug($siteID,$activitySlug,$year,$month)
	{
		db::select("*,activity.*");
		db::where(Array(
				"siteID"=>$siteID,
				"year(activityStartDate)"=>$year,
				"month(activityStartDate)"=>$month,
				"activitySlug"=>$activitySlug
						));

		db::join("event","activityType = '1' AND activity.activityID = event.activityID");
		db::join("training","activityType = '2' AND activity.activityID = training.activityID");

		return db::get("activity")->row();
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

	public function getDate($activityID)
	{
		db::where("activityID",$activityID);
		db::order_by("activityDateValue","asc");
		db::get("activity_date");

		if(!is_array($activityID))
			return db::result();
		else ## if is array, return while grouped by activityID.
			return db::result("activityID",true);
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

	public function updateActivity($activityID,$type,$data)
	{
		$siteID	= $this->getActivity($activityID,"siteID");

		## check active activity.add request.
		if(model::load("site/request")->checkRequest("activity.add",$siteID,$activityID))
		{
			## just update the current.
			$this->_updateActivity($activityID,$type,$data);
		}
		else
		{
			## create request.## create request
			model::load("site/request")->create("activity.update",$siteID,$activityID,$data);
		}
	}

	## set to public so site/request can read this method for update.
	public function _updateActivity($activityID,$type,$data)
	{
		$originalSlug	= model::load("helper")->slugify($data['activityName']);

		## recreate slug, with exception to activityID.
		$activitySlug	= $this->refineActivitySlug($originalSlug,$data['activityStartDate'],$activityID);

		db::where("activityID",$activityID);
		db::update("activity",Array(
						"activityName"=>$data['activityName'],
						"activityAddressFlag"=>$data['activityAddressFlag'],
						"activityAddress"=>$data['activityAddress'],
						"activityDescription"=>$data['activityDescription'],
						"activityParticipation"=>$data['activityParticipation'],
						"activityStartDate"=>$data['activityStartDate'],
						"activityEndDate"=>$data['activityEndDate'],
						"activityUpdatedDate"=>now(),
						"activityCreatedUser"=>session::get("userID"),
						"activitySlug"=>$activitySlug,
						"activitySlugOriginal"=>$originalSlug,
						"activityAllDateAttendance"=>$data['activityAllDateAttendance']
									));

		## update datetime. only if there's no participation yet.
		$participation	= $this->getParticipant($activityID);
		if(!$participation)
			$this->updateDateTime($activityID,$data['activityDateTime']);

		switch($type)
		{
			case 1:
			db::where('activityID',$activityID);
			db::update("event",Array(
						"eventType"=>$data['eventType']
									));
			break;
			case 2:
			db::where("activityID",$activityID);
			db::update("training",Array(
						"trainingType"=>$data['trainingType'],
						"trainingMaxPax"=>$data['trainingMaxPax']
									));
			break;
		}
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
					"activityAllDateAttendance"=>$data['activityAllDateAttendance']
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

		## add datetime.
		$this->updateDateTime($activityID,$data['activityDateTime']);

		## create request
		model::load("site/request")->create("activity.add",$siteID,$activityID,Array());
	}

	public function updateDateTime($activityID,$datetime)
	{
		//first delete the old.
		db::delete("activity_date",Array("activityID"=>$activityID));

		//then insert new.
		foreach($datetime as $date=>$row)
		{
			db::insert("activity_date",Array(
							"activityID"=>$activityID,
							"activityDateValue"=>$date,
							"activityDateStartTime"=>$row['start'],
							"activityDateEndTime"=>$row['end']
										));
		}
	}

	public function participationName($no = null)
	{
		$arr	= Array(
				1=>"Terbuka kepada semua ahli",
				2=>"Hanya untuk ahli",
				3=>"Terbuka kepada penyertaan umum"
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

		return db::get()->result("activityID");
	}

	public function getParticipant($activityID)
	{
		db::select("userIC,userProfileFullName,user.userID,activityUserCreatedDate");
		db::where("activityID",$activityID);
		db::join("user","user.userID = activity_user.userID");
		db::join("user_profile","user_profile.userID = activity_user.userID");
		db::order_by("userProfileFullName","ASC");

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

	public function getRelatedArticle($activityID)
	{
		db::where("activityID",$activityID);
		db::join("article","article.articleID = activity_article.articleID");

		return db::get("activity_article")->result();
	}

	## used in overview. select only all the approved activities.
	public function getRecentIncomingActivity($siteID,$paginationConf = null,$type = "upcoming",$where = null)
	{
		db::from('activity');

		db::where("siteID",$siteID);

		if($type == "upcoming")
		{
			db::where("activityStartDate >",now());
		}
		else if($type == "recent")
		{
			db::where("activityStartDate <",now());
		}

		db::where("activityApprovalStatus",1); ## only approved one.

		if($where)
		{
			foreach($where as $k=>$v)
			{
				db::where($k,$v);
			}
		}

		if($paginationConf)
		{
			pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
			pagination::initiate(Array(
						"urlFormat"=>$paginationConf['urlFormat'],
						"currentPage"=>$paginationConf['currentPage'],
						"totalRow"=>db::num_rows(),
						"limit"=>10
										));
			db::limit(10,pagination::recordNo()-1);
		}

		db::join("user_profile","user_profile.userID = activity.activityCreatedUser");
		return db::get()->result();
	}

	## return list of incoming and previous activity
	public function getUnlinkedActivity($siteID,$year,$month,$flag = "both",$articleID = 0)
	{
		$currDate	= date("Y-m-d H:i:s");

		$where	= Array();
		db::from("activity");
		db::where("siteID",$siteID);

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
		db::where("activityID NOT IN (SELECT activityID FROM activity_article WHERE activityArticleType != 1 OR activityID != activity.activityID OR articleID = ".$articleID.")");

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

	## all selected now is the attending one.
	public function getParticipantList($activityID)
	{
		if(!is_array($activityID))
		{
			db::select("user.*,userProfileAvatarPhoto");
			db::where("activityID",$activityID);
			db::join("user","activity_user.userID = user.userID");
			db::join("user_profile","user_profile.userID = activity_user.userID");

			$res = db::get("activity_user")->result("userID");

			## get participant's site. joining for site-slug building later.
			if($res)
				$siteR	= model::load("site/member")->getMemberSite(array_keys($res));

			$newRes	= Array();
			foreach($res as $uID=>$row)
			{
				## and join.
				$newRes['attending'][$uID]	= array_merge($row,$siteR[$uID]);
			}

			return $newRes;
		}
		else ## if it's list of activity.
		{
			db::select("userID,activityID");
			db::where("activityID",$activityID);
			
			## return while grouped with activityID.
			return db::get("activity_user")->result("activityID",true);
		}

	}

	## use only userID, activityID, and date. (actUserID is for recursive)
	public function join($userID,$activityID,$date = null,$actUserID = false)
	{
		$recursive = $actUserID === false?false:true;

		## create activity_user if not exist.
		$where	= Array(
				"activityID"=>$activityID,
				"userID"=>$userID
						);

		$actUserID	= $actUserID?:db::select("activityUserID")->where($where)->get("activity_user")->row("activityUserID");

		if(!$actUserID)
		{
			$where['activityUserCreatedDate']	= now();
			$where['activityUserCreatedUser']	= session::get("userID");

			db::insert("activity_user",$where);

			$actUserID	= db::getLastID("activity_user","activityUserID");
		}

		## if date wasnt inputted.
		if(!$date)
		{
			## the user join all date.
			#1. get all date off the activity.
			$dateList	= Array();
			$date	= db::where("activityID",$activityID)->get("activity_date")->result("activityDateValue");

			if(!$date)
				return Array(false,"no-date");

			$dateList	= array_keys($date);

			#2. check if user has joined in any.
			$check	= $this->checkUserJoin($activityID,$userID);

			if($check)
			{
				return Array(false,"has-joined");
			}

			## recursive.
			foreach($dateList as $val)
			{
				$res = $this->join($userID,$activityID,$val,$actUserID);
				if(!$res[0])
					return $res;
			}

			return Array(true);
		}

		## recursived just recently. no need to do this check.
		if(!$recursive)
		{
			## validate if the selected activity got the date first, to deny any bad entry
			$check	= db::select("activityID")
			->where("activityID",$activityID)
			->where("activityDateValue",$date)
			->get("activity_date")->row();

			if(!$check)
				return Array(false,"date-invalid");

			## if user has joined.
			$userJoined	= $this->checkUserJoin($activityID,$userID,$date);

			if($userJoined)
				return Array(false,"has-joined");
		}

		## win. and join.
		db::insert("activity_user_date",Array(
								"activityUserID"=>$actUserID,
								"activityUserDateValue"=>$date,
								"activityUserDateAttendance"=>0,
								"activityUserDateCreatedDate"=>now(),
								"activityUserDateCreatedUser"=>session::get("userID"),
											));

		return Array(true);
	}

	public function checkUserJoin($activityID,$userID,$date = null)
	{
		db::select("activityUserDateID")
		->where("activityUserID IN (SELECT activityUserID FROM activity_user WHERE activityID = ? AND userID = ?)",Array($activityID,$userID));

		if($date)
			db::where("activityUserDateValue",$date);

		return db::get("activity_user_date")->row();
	}

	public function getJoinedDate($activityID,$userID)
	{
		db::from("activity_user_date");
		db::where("activityUserID IN (SELECT activityUserID FROM activity_user WHERE activityID = ? AND userID = ?)",Array($activityID,$userID));
		
		return db::get()->result("activityUserDateValue");
	}

	public function joinActivity($userID,$activityID,$join = true)
	{
		$data = Array(
			"userID"=>$userID,
			"activityID"=>$activityID,
			"activityUserValue"=>$join?1:2,
			"activityUserCreatedDate"=>now()
					);

		db::insert("activity_user",$data);
	}
}


?>