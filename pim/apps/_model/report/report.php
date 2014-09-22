<?php
namespace model\report;
use db, model;

class Report
{
	public function getMonthlyReport($year,$month)
	{
		## select all site.
		$siteR = db::select("siteID,stateID,siteName")->get("site")->result("siteID");

		## get total members by site.
		$siteMemberR = db::select("userID,siteID")->get("site_member")->result("siteID",true);

		## get member registered by the chosen month/year.
		db::select("userID,siteID")->where("userID IN (SELECT userID FROM user WHERE userLevel = 1 AND month(userCreatedDate) = ? AND year(userCreatedDate) = ?)",Array($month,$year));
		$registeredR	= db::get("site_member")->result("siteID",true);

		## select all training, by the selected month.
		db::where("activityType",2);
		db::where("year(activityStartDate) = ? AND month(activityStartDate) = ?",Array($year,$month));
		$trainingR	= db::get("activity")->result("activityID");

		## get all activityID.
		if($trainingR)
		{
			$trainingActivityID = array_keys($trainingR);

			## get all activity_date by the activityID and group by activityID.
			db::where("activityID",$trainingActivityID);
			$trainingDateR = db::get("activity_date")->result("activityID",true);

			## get all training user grouped by activityID.
			db::where("activityID",$trainingActivityID);
			$trainingUserR	= db::get("activity_user")->result("activityID",true);
		}

		## get total events based on site. (activityType = 1)
		db::where("activityType",1);
		db::where("year(activityStartDate) = ? AND month(activityStartDate) = ?",Array($year,$month));
		$activityR		= db::get("activity")->result("siteID",true);

		### prepare required data.
		$stateR	= model::load("helper")->state();
		foreach($siteR as $row)
		{
			$siteID	= $row['siteID'];
			$data['siteData'][$siteID]				= Array(
											"siteName"=>$row['siteName'],
											"stateName"=>$stateR[$row['stateID']]
															);
			$data['totalMember'][$siteID] 		= isset($siteMemberR[$siteID])?count($siteMemberR[$siteID]):0;
			$data['monthlyRegistered'][$siteID] = isset($registeredR[$siteID])?count($registeredR[$siteID]):0;
			$data['totalEvents'][$siteID]		= isset($activityR[$siteID])?count($activityR[$siteID]):0;
		}

		## training data
		foreach($trainingR as $row_activity)
		{
			$activityID	= $row_activity['activityID'];
			$siteID		= $row_activity['siteID'];

			$name	= $row_activity['activityName'];
			$data['training'][$siteID]['name'][]	= $name;

			## get hours.
			$totalHours	= 0;
			foreach($trainingDateR[$activityID] as $row_date)
			{
				$hours	= $row_date['activityDateEndTime'] - $row_date['activityDateStartTime'];
				$totalHours	+= $hours;
			}

			## get total user of this training.
			$totalUser	= isset($trainingUserR[$activityID])?count($trainingUserR[$activityID]):0;

			$data['training'][$siteID]['hours'] = $totalHours + (!isset($data['activityTraining'][$siteID]['hours'])?0:$data['activityTraining'][$siteID]['hours']);
			$data['training'][$siteID]['users'] = $totalUser + (!isset($data['activityTraining'][$siteID]['users'])?0:$data['activityTraining'][$siteID]['users']);
		}

		return $data;
	}

	public function getMasterListing($dateStart,$dateEnd)
	{
		## select all site.
		$siteR = db::select("siteID,stateID,siteName")->get("site")->result("siteID");

		## total registered member.
		db::select("userID,siteID");
		$registeredR	= db::get("site_member")->result("siteID",true);


		## get get all member based on activities on the given date.
		## argh just loop oh yea. and prepare data.
		$stateR	= model::load("helper")->state();
		foreach($siteR as $siteID=>$row)
		{
			db::select("*");
			db::from("user");
			db::where("user.userID IN (SELECT userID FROM activity_user WHERE activityID IN (SELECT activityID FROM activity WHERE siteID = '$siteID' AND activityStartDate > '$dateStart' AND activityEndDate < '$dateEnd'))");
			db::join("user_profile","user.userID = user_profile.userID");
			$userR		= db::get()->result("userID");

			## total registered.
			$data['totalRegistered'][$siteID]	= isset($registeredR[$siteID])?count($registeredR[$siteID]):0;

			## site data.
			$data['siteData'][$siteID]			= Array(
											"siteName"=>$row['siteName'],
											"stateName"=>$stateR[$row['stateID']]
														);

			## initiate datas.
			$data['totalActive'][$siteID]	= count($userR);

			## gender
			$data['gender'][$siteID]['male'] = 0;
			$data['gender'][$siteID]['female'] = 0;

			## bumis
			$data['group'][$siteID]	= Array(
									"bumi"=>0,
									"non-bumi"=>0
											);

			## trained by gender.
			$data['totalTrained'][$siteID]	= Array(
									"male"=>0,
									"female"=>0
													);

			## age range.
			$data['ageRange'][$siteID] = Array(
									"under18"	=>0,
									"18-35"		=>0,
									"36-55"		=>0,
									"over55"	=>0
												);

			## occupataion.
			$data['occupation'][$siteID] = Array(
									"students" 		=>0,
									"housewives" 	=>0,
									"self-employed"	=>0,
									"employed"		=>0,
									"not-employed"	=>0,
									"retiree"		=>0
												);

			if($userR)
			{

				foreach($userR as $row_user)
				{
					## find gender.
					if($row_user['userProfileGender'] == 1) # boy
					{
						$data['gender'][$siteID]['male']++;
					} 
					else if($row_user['userProfileGender'] == 2)# girl
					{
						$data['gender'][$siteID]['female']++;
					}
					else
					{
						$data['gender'][$siteID]['unsure']++;
					}

					## bumi. not yet.

					## total trained. no data yet.

					## age group.
					### get user age based on userProfileDOB
					$age	= date("Y")-date("Y",strtotime($row_user['userProfileDOB']));

					
					if($age < 18)
					{
						$data['ageRange'][$siteID]['under18']++;
					}
					else if($age >= 18 && $age <= 35)
					{
						$data['ageRange'][$siteID]['18-35']++;
					}
					else if($age > 35 && $age <= 55)
					{
						$data['ageRange'][$siteID]['36-55']++;
					}
					else ## > 55
					{
						$data['ageRange'][$siteID]['over55']++;
					}


				}

			}
		}

		return $data;
		
	}
}


?>