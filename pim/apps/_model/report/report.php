<?php
namespace model\report;
use db, model, pagination;

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
		$siteR = db::select("*")
		->innerJoin('site_info', 'site_info.siteID = site.siteID')
		->get("site")->result("siteID");

		## total registered member.
		db::select("userID,siteID");
		## user registered in between the date.
		db::where("userID IN (SELECT userID FROM user WHERE userCreatedDate >= ? AND userCreatedDate <= ?)",Array($dateStart,$dateEnd));
		$registeredR	= db::get("site_member")->result("siteID",true);

		$trainingType = db::select("*")
		->get("training_type")->result("trainingTypeID");

		$trainingSubType = db::select("*")
		->where("trainingSubTypeName","KDB")
		->get("training_subtype")->result("trainingSubTypeID");


		//SUBTYPE TRAINING REPORT
		foreach ($trainingSubType as $trainingSubTypeID => $row) {
			$hourSubTraining = 0;
			# code...
			db::select("*");
			db::from("user");
			db::where("user.userID IN (SELECT userID FROM activity_user WHERE activityID IN (SELECT activityID FROM activity WHERE activityStartDate > ? AND activityEndDate < ? AND activityID IN (SELECT activityID FROM training WHERE trainingsubType = ?)) AND activityUserID IN (SELECT activityUserID FROM activity_user_date WHERE activityUserDateAttendance = ?))",Array($dateStart,$dateEnd,$trainingTypeID,1));
			db::join("user_profile","user.userID = user_profile.userID");
			db::join("user_profile_additional","user.userID = user_profile_additional.userID");
			$userTrainingSubType		= db::get()->result("userID");


			db::select("T.trainingSubType, SUM((time_to_sec(timediff(activityDateEndTime, activityDateStartTime )) / 3600)) as totalhours");
			db::from("activity_date AD");
			db::join("activity A", "A.activityID = AD.activityID");
			// db::join("activity_user AU", "AU.activityID = A.activityID");
			db::join("training T", "T.activityID = A.activityID");
			db::where("T.trainingSubType", $trainingSubTypeID);
			db::where("A.activityStartDate > $dateStart ");
			db::where("A.activityEndDate < '$dateEnd' ");
			db::where("EXISTS (SELECT 1 FROM activity_user WHERE activityID = AD.activityID)");

			//db::group_by("T.trainingType");
			$hour = db::get()->row();
			//var_dump($hour);
			//die;
			$hourTraining = $hour['totalhours'];

			$data['STrainingInfo'][$trainingSubTypeID] = Array(
							'trainingTypeName' => $row['trainingTypeName'],
							'trainingTypeID' => $trainingTypeID
			);
			## gender
			$data['STgender'][$trainingSubTypeID]['male'] = 0;
			$data['STgender'][$trainingSubTypeID]['female'] = 0;

			## bumis
			$data['STgroup'][$trainingSubTypeID]	= Array(
									"bumi"=>0,
									"non-bumi"=>0
											);

			## trained by gender.
			$data['STtotalTrained'][$trainingSubTypeID]	= Array(
									"male"=>0,
									"female"=>0
													);

			## age range.
			$data['STageRange'][$trainingSubTypeID] = Array(
									"under18"	=>0,
									"18-35"		=>0,
									"36-55"		=>0,
									"over55"	=>0
												);

			## occupataion : order should be correlated with helper::occupationGroup
			$data['SToccupation'][$trainingSubTypeID] = Array(
									"students" 		=>0,
									"housewives" 	=>0,
									"self-employed"	=>0,
									"employed"		=>0,
									"not-employed"	=>0,
									"retiree"		=>0,
									"no-group"		=>0
												);	

			$data['STclassHour'][$trainingSubTypeID] = 0;

			if($trainingSubTypeID){
				foreach($userTrainingSubType as $row_user)
				{
					// $data['TrainingInfo'][$trainingTypeID] = Array(
					// 				'trainingTypeName' => $row['trainingTypeName'],
					// 				'trainingTypeID' => $trainingTypeID
					// );					
					## find gender.
					if($row_user['userProfileGender'] == 1) # boy
					{
						$data['STgender'][$trainingSubTypeID]['male']++;
					} 
					else if($row_user['userProfileGender'] == 2)# girl
					{
						$data['STgender'][$trainingSubTypeID]['female']++;
					}
					else
					{
						$data['STgender'][$trainingSubTypeID]['unsure']++;
					}

					## bumi. not yet.

					## total trained. no data yet.

					## age group.
					### get user age based on userProfileDOB
					$age	= date("Y")-date("Y",strtotime($row_user['userProfileDOB']));

					
					if($age < 18)
					{
						$data['STageRange'][$trainingSubTypeID]['under18']++;
					}
					else if($age >= 18 && $age <= 35)
					{
						$data['STageRange'][$trainingSubTypeID]['18-35']++;
					}
					else if($age > 35 && $age <= 55)
					{
						$data['STageRange'][$trainingSubTypeID]['36-55']++;
					}
					else ## > 55
					{
						$data['STageRange'][$trainingSubTypeID]['over55']++;
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
						$data['SToccupation'][$trainingSubTypeID][$occupationGroupMap[$row_user['userProfileOccupationGroup']]]++;
					}
					## save it as ungrouped, yet.
					else
					{
						$data['SToccupation'][$trainingSubTypeID]['no-group']++;
					}
				}//foreach
				$data['STclassHour'][$trainingSubTypeID] = $hourTraining;				
			}//if trainingTypeID
		}

		//TRAINING REPORT
		foreach ($trainingType as $trainingTypeID => $row) {
			$hourTraining = 0;
			# code...
			db::select("*");
			db::from("user");
			db::where("user.userID IN (SELECT userID FROM activity_user WHERE activityID IN (SELECT activityID FROM activity WHERE activityStartDate > ? AND activityEndDate < ? AND activityID IN (SELECT activityID FROM training WHERE trainingType = ?)) AND activityUserID IN (SELECT activityUserID FROM activity_user_date WHERE activityUserDateAttendance = ?))",Array($dateStart,$dateEnd,$trainingTypeID,1));
			db::join("user_profile","user.userID = user_profile.userID");
			db::join("user_profile_additional","user.userID = user_profile_additional.userID");
			$userTrainingType		= db::get()->result("userID");
			
			//var_dump($userTrainingType);
			// die;

			db::select("T.trainingType, SUM((time_to_sec(timediff(activityDateEndTime, activityDateStartTime )) / 3600)) as totalhours");
			db::from("activity_date AD");
			db::join("activity A", "A.activityID = AD.activityID");
			db::join("training T", "T.activityID = A.activityID");
			db::where("T.trainingType", $trainingTypeID);
			db::where("A.activityStartDate > $dateStart ");
			db::where("A.activityEndDate < '$dateEnd' ");
			db::where("EXISTS (SELECT userID FROM activity_user WHERE activity_user.activityID = A.activityID)");			
			//db::group_by("T.trainingType");
			$hour = db::get()->row();

			$hourTraining = $hour['totalhours'];

			// db::select("*");
			// db::from("activity");
			// db::where("activity.activityID IN (SELECT activityID FROM training T WHERE T.trainingType = $trainingTypeID)");
			// $listActivity = db::get()->result("activityID");

			// foreach ($listActivity as $activityID => $rowActivity) {
			// 	# code...
			// 	db::select("activityID, SUM((time_to_sec(timediff(activityDateEndTime, activityDateStartTime )) / 3600)) as totalhours");
			// 	db::from("activity_date");
			// 	db::where("activityID = $activityID");
			// 	db::group_by("activityID");
			// 	$hour = db::get()->row();
			// 	//var_dump($hour['totalhours']);
			// 	//die;
			// 	$hourTraining += $hour['totalhours'];

			// }
			//var_dump($row);
			//die;
			$data['TrainingInfo'][$trainingTypeID] = Array(
							'trainingTypeName' => $row['trainingTypeName'],
							'trainingTypeID' => $trainingTypeID
			);
			## gender
			$data['Tgender'][$trainingTypeID]['male'] = 0;
			$data['Tgender'][$trainingTypeID]['female'] = 0;

			## bumis
			$data['Tgroup'][$trainingTypeID]	= Array(
									"bumi"=>0,
									"non-bumi"=>0
											);

			## trained by gender.
			$data['TtotalTrained'][$trainingTypeID]	= Array(
									"male"=>0,
									"female"=>0
													);

			## age range.
			$data['TageRange'][$trainingTypeID] = Array(
									"under18"	=>0,
									"18-35"		=>0,
									"36-55"		=>0,
									"over55"	=>0
												);

			## occupataion : order should be correlated with helper::occupationGroup
			$data['Toccupation'][$trainingTypeID] = Array(
									"students" 		=>0,
									"housewives" 	=>0,
									"self-employed"	=>0,
									"employed"		=>0,
									"not-employed"	=>0,
									"retiree"		=>0,
									"no-group"		=>0
												);	

			$data['TclassHour'][$trainingTypeID] = 0;

			if($trainingTypeID){
				foreach($userTrainingType as $row_user)
				{
					// $data['TrainingInfo'][$trainingTypeID] = Array(
					// 				'trainingTypeName' => $row['trainingTypeName'],
					// 				'trainingTypeID' => $trainingTypeID
					// );					
					## find gender.
					if($row_user['userProfileGender'] == 1) # boy
					{
						$data['Tgender'][$trainingTypeID]['male']++;
					} 
					else if($row_user['userProfileGender'] == 2)# girl
					{
						$data['Tgender'][$trainingTypeID]['female']++;
					}
					else
					{
						$data['Tgender'][$trainingTypeID]['unsure']++;
					}

					## bumi. not yet.

					## total trained. no data yet.

					## age group.
					### get user age based on userProfileDOB
					$age	= date("Y")-date("Y",strtotime($row_user['userProfileDOB']));

					
					if($age < 18)
					{
						$data['TageRange'][$trainingTypeID]['under18']++;
					}
					else if($age >= 18 && $age <= 35)
					{
						$data['TageRange'][$trainingTypeID]['18-35']++;
					}
					else if($age > 35 && $age <= 55)
					{
						$data['TageRange'][$trainingTypeID]['36-55']++;
					}
					else ## > 55
					{
						$data['TageRange'][$trainingTypeID]['over55']++;
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
						$data['Toccupation'][$trainingTypeID][$occupationGroupMap[$row_user['userProfileOccupationGroup']]]++;
					}
					## save it as ungrouped, yet.
					else
					{
						$data['Toccupation'][$trainingTypeID]['no-group']++;
					}
				}//foreach
				$data['TclassHour'][$trainingTypeID] = $hourTraining;				
			}//if trainingTypeID
		}
		//var_dump($data);
		//die;		

		//MAIN MASTER LISTING
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

			$data['siteInfo'][$siteID] = $row;

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

			//ENTREPRENUERSHIP
			db::select("*");
			db::from("user");
			db::where("user.userID IN (SELECT userID FROM activity_user WHERE activityID IN (SELECT activityID FROM activity WHERE siteID = ? AND activityStartDate > ? AND activityEndDate < ? AND activityID IN (SELECT activityID FROM training WHERE trainingType = 12)) AND activityUserID IN (SELECT activityUserID FROM activity_user_date WHERE activityUserDateAttendance = ?))",Array($siteID,$dateStart,$dateEnd,1));
			db::join("user_profile","user.userID = user_profile.userID");
			db::join("user_profile_additional","user.userID = user_profile_additional.userID");
			$userEntre		= db::get()->result("userID");

			//calculate total hours per site
			db::select("A.siteID, SUM((time_to_sec(timediff(activityDateEndTime, activityDateStartTime )) / 3600)) as totalhours");
			db::from("activity_date AD");
			db::join("activity A", "A.activityID = AD.activityID");
			db::join("training T", "T.activityID = A.activityID");
			db::where("T.trainingType", 12);
			db::where("A.activityStartDate > '$dateStart' ");
			db::where("A.activityEndDate < '$dateEnd' ");
			db::where("A.siteID", $siteID);
			db::where("EXISTS (SELECT userID FROM activity_user WHERE activity_user.activityID = A.activityID)");			
			//db::group_by("T.trainingType");
			$hourEntre = db::get()->row();
			//var_dump($hourEntre);
			//die;
			$hourEntre['totalhours'] ? $hourTrainingEntre = $hourEntre['totalhours'] : $hourTrainingEntre = 0;
			## site data.
			$data['EsiteData'][$siteID]			= Array(
											"siteName"=>$row['siteName'],
											"stateName"=>$stateR[$row['stateID']]
														);

			$data['EsiteInfo'][$siteID] = $row;

			## gender
			$data['Egender'][$siteID]['male'] = 0;
			$data['Egender'][$siteID]['female'] = 0;

			## bumis
			$data['Egroup'][$siteID]	= Array(
									"bumi"=>0,
									"non-bumi"=>0
											);

			## age range.
			$data['EageRange'][$siteID] = Array(
									"under18"	=>0,
									"18-35"		=>0,
									"36-55"		=>0,
									"over55"	=>0
												);

			## occupataion : order should be correlated with helper::occupationGroup
			$data['Eoccupation'][$siteID] = Array(
									"students" 		=>0,
									"housewives" 	=>0,
									"self-employed"	=>0,
									"employed"		=>0,
									"not-employed"	=>0,
									"retiree"		=>0,
									"no-group"		=>0
												);

			$data['EclassHour'][$siteID] = 0;

			if($userR)
			{

				foreach($userR as $row_user)
				{
					## find gender.
					if($row_user['userProfileGender'] == 1) # boy
					{
						$data['Egender'][$siteID]['male']++;
					} 
					else if($row_user['userProfileGender'] == 2)# girl
					{
						$data['Egender'][$siteID]['female']++;
					}
					else
					{
						$data['Egender'][$siteID]['unsure']++;
					}

					## bumi. not yet.

					## total trained. no data yet.

					## age group.
					### get user age based on userProfileDOB
					$age	= date("Y")-date("Y",strtotime($row_user['userProfileDOB']));

					
					if($age < 18)
					{
						$data['EageRange'][$siteID]['under18']++;
					}
					else if($age >= 18 && $age <= 35)
					{
						$data['EageRange'][$siteID]['18-35']++;
					}
					else if($age > 35 && $age <= 55)
					{
						$data['EageRange'][$siteID]['36-55']++;
					}
					else ## > 55
					{
						$data['EageRange'][$siteID]['over55']++;
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
						$data['Eoccupation'][$siteID][$occupationGroupMap[$row_user['userProfileOccupationGroup']]]++;
					}
					## save it as ungrouped, yet.
					else
					{
						$data['Eoccupation'][$siteID]['no-group']++;
					}
				}

			}
			$data['EclassHour'][$siteID] = $hourTrainingEntre;	

		}//END LOOP SITE ENTREPRENUERSHIP
		//var_dump($data);
		//die;
		return $data;
		
	}

	public function getQuarterlyReport($siteID = null, $year = null, $quarter = null){
		## select all site.
		//$year = 2016;
		//$quarter = 2;
		switch ($quarter) {
			case '1':
				# code...
				$month = "(1,2,3)";
				break;
			case '2':
				# code...
				$month = "(4,5,6)";
				break;			
			case '3':
				# code...
				$month = "(7,8,9)";
				break;			
			case '4':
				# code...
				$month = "(10,11,12)";
				break;
			
			default:
				# code...
				break;
		}

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
			db::where("YEAR(activityStartDate) ", $year);
			db::where("MONTH(activityStartDate) IN ", $month);

			db::where("activity.siteID",$keySite['siteID']);

			//db::where("year(activityStartDate) = ? AND month(activityStartDate) = ?",Array($year,$month));
			$trainingR	= db::get("activity")->result();
			//var_dump(db::lastQuery());
			//die;
			//var_dump($trainingR);

			$hourTraining = 0;
			$totalHourTraining = 0;
			$totalAttendeesTraining = 0;
			$totalDaysEvent = 0;
			$totalAttendeesEvent = 0;
			$countTraining = 0;
			$countEvent = 0;
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
					 	$totalHourTraining += $resultHourTraining[0]['totalhours'];
					// }

						//$tempActivityID = 	$keyTraining['activityID'];

					$arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['HourTraining'] = $hourTraining;
					//var_dump($resultHourTraining[0]['totalhours']);
					//var_dump($keyTraining['activityID'] . db::lastQuery());
					//die;
					//db::join("activity_user ", " activity.activityID = activity_user.activityID");

					db::select("count(UserID) as attendees");
					db::where("activity_user.activityID", $keyTraining['activityID']);
					$resultAttendees = db::get('activity_user')->result();
					//var_dump($keyTraining['activityID']);
					//var_dump($resultAttendees);
					$arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['attendees'] = $resultAttendees[0]['attendees'];
					$totalAttendeesTraining += $resultAttendees[0]['attendees'];

					$countTraining++;					
					//$album = db::lastQuery();
					//var_dump($album);	
				}//end if

			}//end-foreach training
			//$arrayActivitiesOnSite['HourTraining'] = $hourTraining;
			$arrayActivitiesOnSite['totalHourTraining'] = $totalHourTraining;
			$arrayActivitiesOnSite['totalAttendeesraining'] = $totalAttendeesTraining;
			$arrayActivitiesOnSite['countTraining'] = $countTraining;



			## select event only
			db::select("*, DATE(activityStartDate) as startDate");
			db::where("activityType",1);
			db::where("YEAR(activityStartDate) ", $year);
			db::where("MONTH(activityStartDate) IN ", $month);			
			db::where("siteID",$keySite['siteID']);
			//db::where("year(activityStartDate) = ? AND month(activityStartDate) = ?",Array($year,$month));
			$eventR		= db::get("activity")->result();		
			//var_dump($eventR);
			$dayEvent = 0;
			//var_dump($keySite['siteID']);
			//var_dump($eventR);
			//die;
			foreach ($eventR as $keyEvent) {

				# code...
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']] = $keyEvent;

				db::select("count(activityID) as totaldays");
				db::where("activity_date.activityID", $keyEvent['activityID']);
				$resultDayEvent = db::get("activity_date ")->result();
				//var_dump($keyEvent['activityID']);
				
				//die;
				$dayEvent = $resultDayEvent[0]['totaldays'];
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['dayEvent'] = $dayEvent;	
				$totalDaysEvent += $dayEvent;
				// db::select("count(UserID) as attendees");
				// db::where("activity_user.activityID", $keyEvent['activityID']);
				// $resultAttendeesEvent = db::get('activity_user')->result();
				// //var_dump($keyEvent['activityID']);
				// //var_dump($resultAttendeesEvent);				
				// //var_dump($resultAttendeesEvent);
				// $arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['attendees'] = $resultAttendeesEvent[0]['attendees'];
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['attendees'] = 0;
				$totalAttendeesEvent += 0;

				$countEvent++;

			}//end foreach event
			//$arrayActivitiesOnSite['DayEvent'] = $dayEvent;
			$arrayActivitiesOnSite['totalDaysEvent'] = $totalDaysEvent;
			$arrayActivitiesOnSite['totalAttendeesEVent'] = $totalAttendeesEvent;
			$arrayActivitiesOnSite['countEvent'] = $countEvent;			



			## select AJK Pi1M photo
			db::where('siteID', $keySite['siteID']);
			db::where('pageDefaultType', 3);
			$ajkresult = db::get('page')->result();

			$arrayActivitiesOnSite['ajk']= $ajkresult[0]['pagePhoto'];

			## select photo gallery
			//db::select('activityID');
			//db::where("activity.siteID",$keySite['siteID']);
			//$activityAlbum	= db::get("activity")->result();
			

			//var_dump($keySite['siteID']);
			//$countImage = 0;
			//foreach ($activityAlbum as $keyAlbum) {
				# code...
				// db::select("photoName, photoDescription, DATE(photoCreatedDate) as photoDate");
				
				// db::join("site_photo SP", " SP.siteID = ". $keySite['siteID']);				
				// db::where("photo.photoID = SP.photoID");
				// db::where("YEAR(photo.photoCreatedDate)", $year);
				// db::where("MONTH(photo.photoCreatedDate) IN ", $month);
				//$album = db::get("album")->result();

				db::select("photoName, albumName, DATE(photoCreatedDate) as photoDate");
				//db::from("album");
				db::join("site_album", "site_album.albumID = album.albumID");
				db::join("site_photo", "site_photo.siteAlbumID = site_album.siteAlbumID");
				db::join("photo", "photo.photoID = site_photo.photoID");
				db::where("site_photo.siteID ", $keySite['siteID']);
				db::where("YEAR(photo.photoCreatedDate)", $year);
				db::where("MONTH(photo.photoCreatedDate) IN ", $month);				

				$album = db::get("album")->result();
				//db::where("photo.photoDescription ", " NOT NULL");

				
				//var_dump($album);
				//die;
				if($album) {
					//count($album) > 5 ? $countAlbum = 5 : $countAlbum = count($album);
					if(count($album) < 16)
						$random_image = $album;
					else {
						$random_keys = array_rand($album, 16);
						$random_image = array();
						foreach ($random_keys as $key) {
							# code...
							$random_image[$key] = $album[$key]; 
						}
						//$random_image = array_rand($album, 5);
					}
				//var_dump($keySite['siteID']);
					//var_dump($random_image);
					//die;
					$imagecount = 0;
					foreach ($random_image as $keyRandom) {
						# code...
						//var_dump($album[$keyRandom]);
						$arrayActivitiesOnSite['album'][$imagecount]['albumCoverImageName'] =  $keyRandom['photoName'];
						$arrayActivitiesOnSite['album'][$imagecount]['albumDate'] =  $keyRandom['photoDate'];
						$arrayActivitiesOnSite['album'][$imagecount]['albumName'] =  $keyRandom['albumName'];	
						$imagecount++;
					}
					//var_dump($arrayActivitiesOnSite['album']);
					//die;
				}
				//var_dump($album[$random_image]);
				//var_dump(db::lastQuery());
				//die;



				

				// if($countImage > 2)
				// 	break;

				// $countImage++;

			//}//end foreach activityAlbum
					

			//cash flow - revenue
			db::select("siteName, YEAR(billingTransactionDate) AS yr, MONTH(billingTransactionDate) AS mn,
SUM(billingTransactionItemPrice * billingTransactionItemQuantity) AS revenue");
			db::join("billing_transaction_item BTI", "BTI.billingTransactionID = BT.billingTransactionID");
			db::join("site S", "S.siteID = BT.siteID");
			db::where("billingTransactionItemPrice >", 0);
			db::where("BT.siteID", $keySite['siteID']);
			db::where("YEAR(billingTransactionDate)", $year);
			db::where("MONTH(billingTransactionDate) IN ", $month);
			db::group_by("siteName, yr, mn");
			$cashflow = db::get("billing_transaction BT")->result();
			//var_dump($cashflow);
			//var_dump(db::lastQuery());
			//die;

			foreach ($cashflow as $keyCashFlow) {
				# code...
				$arrayActivitiesOnSite['revenue'][$keyCashFlow['mn']] = number_format((float)$keyCashFlow['revenue'], 2, '.', '');
			}

			//take up - total member
			db::select("siteName, YEAR(userCreatedDate) AS yr, MONTH(userCreatedDate) AS mn,
COUNT(*) AS members");
			db::join("site_member SM", "U.userID = SM.userID");
			db::join("site S", "S.siteID = SM.siteID");
			db::where("YEAR(userCreatedDate)", $year);
			db::where("MONTH(userCreatedDate) IN ", $month);
			db::where("S.siteID", $keySite['siteID']);
			db::group_by("siteName, yr, mn");
			$totalmember = db::get("user U")->result();
			//var_dump($totalmember);
			//die;

			foreach ($totalmember as $keyTotalMember) {
				# code...
				$arrayActivitiesOnSite['totalmember'][$keyTotalMember['mn']] = $keyTotalMember['members'];
			}			

// 			//pc usage day total
			db::select("siteName, YEAR(billingTransactionDate) AS yr, MONTH(billingTransactionDate) AS mn,
COUNT(DISTINCT billingTransactionUser) AS members");
			db::join("billing_transaction_item BTI", "BTI.billingTransactionID = BT.billingTransactionID");
			db::join("billing_transaction_user BTU", "BTU.billingTransactionID = BT.billingTransactionID");
			db::join("site S", "S.siteID = BT.siteID");
			db::where("BTI.billingItemID", 3);
			db::where("YEAR(billingTransactionDate)", $year);
			db::where("MONTH(billingTransactionDate) IN ", $month);
			db::where("BT.siteID", $keySite['siteID']);
			db::group_by("siteName, yr, mn");
			$usagetotal = db::get("billing_transaction BT")->result();
			// var_dump($usagetotal);
			// die;

			foreach ($usagetotal as $keyUsagetotal) {
				# code...
				$arrayActivitiesOnSite['usagetotal'][$keyUsagetotal['mn']] = $keyUsagetotal['members'];
			}			

// 			//day pc usage hour
			db::select("siteName, YEAR(billingTransactionDate) AS yr, MONTH(billingTransactionDate) AS mn,
SUM(billingTransactionItemQuantity) AS hours");
			db::join("billing_transaction_item BTI", "BTI.billingTransactionID = BT.billingTransactionID");
			db::join("site S", "S.siteID = BT.siteID");
			db::where("BTI.billingItemID", 3);
			db::where("YEAR(billingTransactionDate)", $year);
			db::where("MONTH(billingTransactionDate) IN ", $month);
			db::where("BT.siteID", $keySite['siteID']);
			db::group_by("siteName, yr, mn");
			$usagehour  = db::get("billing_transaction BT")->result();
			//var_dump($usagehour);
			//die;
			foreach ($usagehour as $keyUsageHour) {
				# code...
				$arrayActivitiesOnSite['usagehour'][$keyUsageHour['mn']] = number_format((float)$keyUsageHour['hours'], 2, '.', '');
				//$arrayActivitiesOnSite['usagehour'][$keyUsageHour['mn']] = $keyUsageHour['hours'];
			}	

			## put result into array
			
			//$arrayActivitiesOnSite['siteID'][$trainingR['trID']] = $trainingR; 
			//$arrayActivitiesOnSite['siteID'][$activityR['evID']] = $activityR; 
	//}//end foreach site
		##end loop site

		##return array
		return $arrayActivitiesOnSite;


	}

	public function getListReport($where, $pagination){
		//db::select("reportsID, reportsName, reportsDesc, reportsURL")->from("reports");
		db::select("*")->from("reports_form");
		//var_dump($reportlist);
		if($where)
			{
				$where	= !is_array($where)?Array($where):$where;
				foreach($where as $key => $wher)
				{
					if(is_string($key))
						where($key, $wher);
					else
						db::where($wher);
				}
			}
			//echo $pagination;
			if($pagination)
			{
				$totalRows = db::num_rows();
				
				## required property : totalRow, currentPage, limit urlFormat
				pagination::initiate(Array(
					"totalRow"=>$totalRows,
					"currentPage"=>$pagination['currentPage'],
					"urlFormat"=>$pagination['urlFormat']
									));
				
				//var_dump($pagination['urlFormat']);

				db::limit(pagination::get("limit"), pagination::recordNo()-1);
			}
			//var_dump('');
			$reportlist = db::get()->result('reportsFormID');
			return $reportlist;
	}

	public function getReportFormField($idReport){
		db::select("*");
		db::from("report_fields");
		db::where("report_fieldsReportID", $idReport);
		db::order_by("report_fieldsID");
		$result = db::get()->result();

		return $result;
	}

	public function getReportForm($idReport){
		db::select("*");
		db::from("reports_form");
		db::where("reportsFormID", $idReport);
		$result = db::get()->row();

		return $result;		
	}
}


?>