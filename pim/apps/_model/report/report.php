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
		}

		return $data;
		
	}

	public function getQuarterlyReport($siteID = null, $quarter = null){
		## select all site.
		$year = 2016;
		$quarter = 1;
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

					db::select("count(UserID) as attendees");
					db::where("activity_user.activityID", $keyTraining['activityID']);
					$resultAttendees = db::get('activity_user')->result();
					//var_dump($keyTraining['activityID']);
					//var_dump($resultAttendees);
					$arrayActivitiesOnSite['Training'][$keyTraining['activityID']]['attendees'] = $resultAttendees[0]['attendees'];

					
					//$album = db::lastQuery();
					//var_dump($album);	
				}//end if

			}//end-foreach training
			//$arrayActivitiesOnSite['HourTraining'] = $hourTraining;




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

				// db::select("count(UserID) as attendees");
				// db::where("activity_user.activityID", $keyEvent['activityID']);
				// $resultAttendeesEvent = db::get('activity_user')->result();
				// //var_dump($keyEvent['activityID']);
				// //var_dump($resultAttendeesEvent);				
				// //var_dump($resultAttendeesEvent);
				// $arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['attendees'] = $resultAttendeesEvent[0]['attendees'];
				$arrayActivitiesOnSite['Event'][$keyEvent['activityID']]['attendees'] = 0;


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
			

			//var_dump($keySite['siteID']);
			//$countImage = 0;
			//foreach ($activityAlbum as $keyAlbum) {
				# code...
				db::select("photoName, photoDescription, DATE(photoCreatedDate) as photoDate");
				
				db::join("site_photo SP", " SP.siteID = ". $keySite['siteID']);				
				db::where("photo.photoID = SP.photoID");
				db::where("YEAR(photo.photoCreatedDate)", $year);
				db::where("MONTH(photo.photoCreatedDate) IN ", $month);
				//db::where("photo.photoDescription ", " NOT NULL");

				$album = db::get("photo")->result();
				//var_dump($album);
				//die;
				if($album) {
					//count($album) > 5 ? $countAlbum = 5 : $countAlbum = count($album);
					if(count($album) < 5)
						$random_image = $album;
					else {
						$random_keys = array_rand($album, 5);
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
						$arrayActivitiesOnSite['album'][$imagecount]['albumName'] =  $keyRandom['photoDescription'];	
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
			db::group_by("siteName, yr, mn");
			$usagehour  = db::get("billing_transaction BT")->result();
			//var_dump($usagehour);
			//die;
			foreach ($usagehour as $keyUsageHour) {
				# code...
				$arrayActivitiesOnSite['usagehour'][$keyUsageHour['mn']] = $keyUsageHour['hours'];
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
		db::select("*")->from("reports");
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
			$reportlist = db::get()->result('reportsID');
			return $reportlist;
	}

	public function getReportForm($idReport){
		db::select("*");
		db::from("report_fields");
		db::where("report_fieldsReportID", $idReport);
		db::order_by("report_fieldsID");
		$result = db::get()->result();

		return $result;
	}
}


?>