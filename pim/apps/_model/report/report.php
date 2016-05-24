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
		## user registered in between the date.
		db::where("userID IN (SELECT userID FROM user WHERE userCreatedDate >= ? AND userCreatedDate <= ?)",Array($dateStart,$dateEnd));
		$registeredR	= db::get("site_member")->result("siteID",true);


		## get get all member based on activities on the given date.
		## argh just loop oh yea. and prepare data.
		$stateR	= model::load("helper")->state();
		foreach($siteR as $siteID=>$row)
		{
			## select only user whose attend (activityUserDateAttendance =1) events by the given date.
			db::select("*");
			db::from("user");
			db::where("user.userID IN (SELECT userID FROM activity_user WHERE activityID IN (SELECT activityID FROM activity WHERE siteID = ? AND activityStartDate > ? AND activityEndDate < ?) AND activityUserID IN (SELECT activityUserID FROM activity_user_date WHERE activityUserDateAttendance = ?))",Array($siteID,$dateStart,$dateEnd,1));
			db::join("user_profile","user.userID = user_profile.userID");
			db::join("user_profile_additional","user.userID = user_profile_additional.userID");
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

			## occupataion : order should be correlated with helper::occupationGroup
			$data['occupation'][$siteID] = Array(
									"students" 		=>0,
									"housewives" 	=>0,
									"self-employed"	=>0,
									"employed"		=>0,
									"not-employed"	=>0,
									"retiree"		=>0,
									"no-group"		=>0
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

					## map oocupationGroup with the key set above.
					$occupationGroupMap = Array(
						1=>"students",
						2=>"housewives",
						3=>"self-employed",
						4=>"employed",
						5=>"not-employed",
						6=>"retiree"
						);

					if(isset($occupationGroupMap[$row_user['userProfileOccupationGroup']]))
					{
						$data['occupation'][$siteID][$occupationGroupMap[$row_user['userProfileOccupationGroup']]]++;
					}
					## save it as ungrouped, yet.
					else
					{
						$data['occupation'][$siteID]['no-group']++;
					}
				}

			}
		}

		return $data;
		
	}

	public function getQuarterlyReport($siteID = null, $quarter = null){
		## select all site.
		$keySite = db::select("siteID,stateID,siteName")->where("siteID", $siteID)->get("site")->result();
		$keySite = $keySite[0];
		//var_dump($keySite);
		//die;
		$arrayActivitiesOnSite = array();
		$arrayActivitiesOnSite = $keySite;
		##loop in site
		//foreach ($siteR as $keySite) {
			# code...
			//$arrayActivitiesOnSite = $keySite;
			//echo $key['siteID'];
			## select training only
			$training =  
			//db::select("*, count(activity_date.activityDateID) as duration, count(activity_user.activityUserID) as attendees ");
			// db::join("activity_date ", " activity.activityID = activity_date.activityID");
			// db::join("activity_user ", " activity.activityID = activity_user.activityID");
			db::select("*, DATE(activityStartDate) as startDate");
			db::where("activityType",2);
			db::where("YEAR(activityStartDate) ", "2016");
			db::where("MONTH(activityStartDate) IN ", "(1,2,3)");

			db::where("activity.siteID",$keySite['siteID']);

			//db::where("year(activityStartDate) = ? AND month(activityStartDate) = ?",Array($year,$month));
			$trainingR	= db::get("activity")->result();
			//var_dump(db::lastQuery());
			//die;
			//var_dump($trainingR);

			$hourTraining = 0;
			foreach ($trainingR as $keyTraining) {
				# code...
				if ($keyTraining['activityID']){
					$arrayActivitiesOnSite['Training'][$keyTraining['activityID']] = $keyTraining;
					//var_dump($arrayActivitiesOnSite);
					//var_dump($keyTraining['activityID']);

					##calculate total hours
					//db::select("activityID, TIMESTAMPDIFF(HOUR, activityDateStartTime, activityDateEndTime) AS totalhours");
					db::select("activityID, SUM(Hour(TIMEDIFF(activityDateEndTime, activityDateStartTime))) AS totalhours");
					db::group_by("activityID");
					db::where("activity_date.activityID", $keyTraining['activityID']);
					$resultHourTraining = db::get("activity_date ")->result();
					//var_dump($resultHourTraining);
					//var_dump($keyTraining['activityID'] . "======" . $arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['activityID']);
					// if($keyTraining['activityID'] == $arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['activityID']){
					// 	$hourTraining += $resultHourTraining[0]['totalhours'];
					// }
					// else{
					 	$hourTraining = $resultHourTraining[0]['totalhours'];
					// }

						//$tempActivityID = 	$keyTraining['activityID'];

					$arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['HourTraining'] = $hourTraining;
					//var_dump($resultHourTraining[0]['totalhours']);
					//var_dump($keyTraining['activityID'] . db::lastQuery());
					//die;
					//db::join("activity_user ", " activity.activityID = activity_user.activityID");

					db::select("trainingMaxPax");
					db::where("training.activityID", $keyTraining['activityID']);
					$resultAttendees = db::get('training')->result();
					//var_dump($resultAttendees);
					$arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['attendees'] = $resultAttendees[0]['trainingMaxPax'];

					
					//$album = db::lastQuery();
					//var_dump($album);	
				}//end if

			}//end-foreach training
			//$arrayActivitiesOnSite['HourTraining'] = $hourTraining;




			## select event only
			db::select("*, DATE(activityStartDate) as startDate");
			db::where("activityType",1);
			db::where("YEAR(activityStartDate) ", "2016");
			db::where("MONTH(activityStartDate) IN ", "(1,2,3)");			
			db::where("siteID",$keySite['siteID']);
			//db::where("year(activityStartDate) = ? AND month(activityStartDate) = ?",Array($year,$month));
			$eventR		= db::get("activity")->result();		
			//var_dump($eventR);
			$dayEvent = 0;
			foreach ($eventR as $keyEvent) {
				# code...
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']] = $keyEvent;

				db::select("count(activityID) as totaldays");
				db::where("activity_date.activityID", $keyTraining['activityID']);
				$resultDayEvent = db::get("activity_date ")->result();

				$dayEvent = $resultDayEvent[0]['totaldays'];
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['dayEvent'] = $dayEvent;	

				db::select("trainingMaxPax");
				db::where("training.activityID", $keyEvent['activityID']);
				$resultAttendees = db::get('training')->result();
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['attendees'] = $resultAttendees[0]['trainingMaxPax'];				


			}//end foreach event
			//$arrayActivitiesOnSite['DayEvent'] = $dayEvent;
			



			## select AJK Pi1M photo
			db::where('siteID', $keySite['siteID']);
			db::where('pageDefaultType', 3);
			$ajkresult = db::get('page')->result();

			$arrayActivitiesOnSite['ajk']= $ajkresult[0]['pagePhoto'];

			## select photo gallery
			db::select('activityID');
			db::where("activity.siteID",$keySite['siteID']);
			$activityAlbum	= db::get("activity")->result();

			foreach ($activityAlbum as $keyAlbum) {
				# code...
				db::select("albumCoverImageName, DATE(albumCreatedDate) as albumDate");
				db::join("activity_album AA", "AA.activityID =  " . $keyAlbum['activityID']);
				db::join("site_album SA", " SA.siteID =  AA.siteAlbumID");
				db::where("album.albumID = SA.albumID");
				//db::limit(1);
				$album = db::get("album")->result();


				$arrayActivitiesOnSite['album'][$keyAlbum['activityID']]['albumName'] =  $album[0]['albumCoverImageName'];
				$arrayActivitiesOnSite['album'][$keyAlbum['activityID']]['albumDate'] =  $album[0]['albumDate'];
			}//end foreach activityAlbum
					
			## put result into array
			
			//$arrayActivitiesOnSite['siteID'][$trainingR['trID']] = $trainingR; 
			//$arrayActivitiesOnSite['siteID'][$activityR['evID']] = $activityR; 
	//}//end foreach site
		##end loop site

		##return array
		return $arrayActivitiesOnSite;


	}
}


?>