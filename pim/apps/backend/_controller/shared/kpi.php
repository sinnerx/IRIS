<?php
Class Controller_Kpi
{
	public function kpi_summary($year = null, $month = null, $cluster = null, $siteParam = null){
		// $siteID = site()->siteID;
		// $year = 2016;
		// $month = 8;
		// $site = 14;
		// var_dump(request::get());
		// die;

		$siteIDs = array();

		db::select("siteID, siteName");
		db::from('site');
		db::order_by('siteName');

		//1st time to detect whether cl or root
		if(authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD)
		{
			db::where('siteID IN (SELECT cluster_site.siteID FROM cluster_site WHERE cluster_site.clusterID IN (SELECT cluster_lead.clusterID FROM cluster_lead WHERE cluster_lead.userID = ?))', array(authData('user.userID')));	
			$sites = $data['sites'] = db::get()->result('siteID');	

			$userID = authData('user.userID');

			$res_site = model::load("site/site")->getSitesByClusterLead($userID)->result();
			// print_r($res_site);
			// die;
			foreach($res_site as $row)
			{
				$data['siteR'][$row['siteID']]	= $row['siteName'];
			}

			$clusterUser = model::load("site/cluster")->getClusterByUser(authData('user.userID'));
			$clusterDetails = model::load("site/cluster")->getClusterByID($clusterUser['clusterID']);	

			// var_dump($clusterUser);
			// die;
			

			$auditScore = $clusterDetails['clusterAuditScore'];						
		}
		else if (authData('user.userLevel') == \model\user\user::LEVEL_ROOT){
			$sites = $data['sites'] = db::get()->result('siteID');

			$allCluster = model::load("site/cluster")->lists();

			foreach ($allCluster as $clusteritem) {
				# code...
				$totalAuditScore += $clusteritem['clusterAuditScore'];
			}
			
				$auditScore = $totalAuditScore;
		}
		
		$siteIDs = array_keys($sites);

		//populate cluster list
		$res_cluster	= model::load("site/cluster")->lists();
		foreach($res_cluster as $row)
		{
			$data['clusterR'][$row['clusterID']]	= $row['clusterName'];
		}

		if($cluster != '')
		{
			// var_dump(request::get("cluster"));
			// die;
			$siteIDs = model::load("site/site")->getSitesByClusterID($cluster);
			// var_dump($siteIDs);
			// die;
			foreach($siteIDs as $row)
			{
				$data['siteR'][$row['siteID']]	= $row['siteName'];
			}			

			$data['site'] = '';

			$clusterDetails = model::load("site/cluster")->getClusterByID($cluster);	
			// var_dump($clusterDetails);
			$clusterDetails['clusterAuditScore'] === null ?  $auditScore = "N/A" : $auditScore = $clusterDetails['clusterAuditScore'];	
			// db::where("siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = '".request::get("cluster")."')");
		}

		if($siteParam != ''){
			$siteIDs = $siteParam;
		}

		if($cluster == null){
			if ($siteParam == null){
				$siteIDs = $siteIDs;
			}
			else {
				$siteIDs = $siteParam;
			}
		}	
		else {
			if($siteParam == null){
				$siteIDs = array_values(array_keys($siteIDs));
			}
			else{
				$siteIDs = $siteParam;
			}
		}
		// var_dump($siteIDs);
			//
		$data['year'] = $year = $year ? : date('Y');
		$data['month'] = $month = $month ? : date('n');


		$countSite = count($siteIDs);

		//concatinate into string in bracket
		//$siteIDs = implode (", ", $siteIDs);
		// $siteIDs = "(". $siteIDs . ")";

		$data['max'] = array(
			'event' => 2 * $countSite,
			'entrepreneurship_class' => 1 * $countSite,
			'entrepreneurship_sales' => 300 * $countSite,
			'training_hours' => 32 * $countSite,
			'active_member_percentage' => 80 * $countSite,
			'kdb_sessions' => 30 * $countSite,
			'kdb_pax' => 600 * $countSite,
			'auditScore' => $auditScore,
			'entre_article' => 1 * $countSite,
			);

		// event
		// activity : event
		// has at least 1 article
		/*$totalEvents = db::from('activity')
		->select('count(activity.activityID) as total')
		->where('siteID', $siteID)
		->where('activityType', 1)
		->where('activityApprovalStatus', 1)
		->where('activityID IN (SELECT activityID FROM activity_article WHERE activity_article.activityID = activity.activityID)')
		->where('MONTH(activityStartDate) = ? AND YEAR(activityStartDate) = ?', array($month, $year))
		->get()->row('total');*/

	
		db::select('SUM(noOfActivities) as noOfActivities');
		db::from('OLAP_articled_activities');
		db::where('siteID', $siteIDs);
		db::where('month = ? AND year = ?', array($month, $year));
		$totalEvents = db::get()->row('noOfActivities');

		if ($totalEvents == null) {
			$totalEvents = 0;
		}

		// var_dump($totalEvents);
		// die;
		// total entrepreneurship class
		// activity : training
		// has at least 1 article
		 
		
		db::select('count(activity.activityID) as total');
		db::from('activity');
		db::where('siteID', $siteIDs);
		db::where('activityType', 2);
		db::where('activityApprovalStatus', 1);
		db::where('activityStartDate <= NOW() - INTERVAL 1 DAY ');
		db::where('MONTH(activityStartDate) = ? AND YEAR(activityStartDate) = ?', array($month, $year));
		db::where('training_type.trainingTypeName LIKE ?', array('%Entrepreneurship%'));
		// ->join('activity_article', 'activity_article.activityID = activity.activityID', 'INNER JOIN')
		db::join('training', 'activity.activityID = training.activityID', 'INNER JOIN');
		db::join('training_type', 'training.trainingType = training_type.trainingTypeID', 'INNER JOIN');
		// db::group_by("siteID");
		$totalEntrepreneurship = db::get()->row('total');

		// var_dump($totalEntrepreneurship);
		// die;
		// entrepreneurship program
		// table : billing_transaction_item 
		// billingItemCode = lms_item
		
		db::select('SUM(billingTransactionItemPrice * billingTransactionItemQuantity) as total');
		db::from('billing_transaction_item');
		db::where('billing_transaction.siteID', $siteIDs);
		db::where('MONTH(billingTransactionDate) = ? AND YEAR(billingTransactionDate) = ?', array($month, $year));
		db::where('billing_transaction_item.billingItemID IN (SELECT billingItemID FROM billing_item WHERE billingItemCode = ?)', array('lms_item'));
		db::join('billing_transaction', 'billing_transaction.billingTransactionID = billing_transaction_item.billingTransactionID');
		$sales = db::get()->row('total') ? : 0;

		// var_dump($sales);
		// die;
		// total training hours
		// activity : training
		// has at least one rsvp
		/*$trainingHours = db::from('activity_date')
		->where('activityType', 2)
		->where('activityApprovalStatus', 1)
		->where('activity.siteID', $siteID)
		->where('activity_date.activityID IN (SELECT activityID FROM activity_user)') // rsvp
		->where('MONTH(activity.activityStartDate) = ? AND YEAR(activity.activityStartDate) = ?', array($month, $year))
		->join('activity', 'activity.activityID = activity_date.activityID', 'INNER JOIN')
		->get()->result();

		$time = 0;
		
		foreach($trainingHours as $activityDate)
			$time += strtotime($activityDate['activityDateEndTime']) - strtotime($activityDate['activityDateStartTime']);

		$hours = floor($time / 3600);*/

		//  sum(time_to_sec(timediff(endTime, startTime)) / 3600) as total from OLAP_site_activity_date_times
			// if($month >= date('m')){
			// 	echo "current month ". date('m');
			// }
			// die;
		
		db::select('sum(time_to_sec(timediff(endTime, startTime)) / 3600) as total');
		db::from('OLAP_site_activity_date_times');
		db::where('siteID', $siteIDs);

		db::where('activityDate <= NOW() - INTERVAL 1 DAY');
		db::where('MONTH(activityDate) = ? AND YEAR(activityDate) = ?', array($month, $year));
		$trainingHours = db::get()->row('total');
		
		$hours = 0;
		$hours += $trainingHours;

		/*$time = 0;
		
		foreach($trainingHours as $activityDate)
			$time += strtotime($activityDate['activityDateEndTime']) - strtotime($activityDate['activityDateStartTime']);

		$hours = floor($time / 3600);*/

		// active member percentage
		// based on at least having 1 login
		// active member / total member * 100
		$totalMembers = db::select('count(userID) as total')->where('siteID', $siteIDs)->get('site_member')->row('total');

		/*$activeMembers = db::from('site_member')
		->select('count(userID) as total')
		->where('siteID', $siteID)
		->where('siteMemberStatus',1)
		->where('userID IN (SELECT userID FROM log_login WHERE MONTH(logLoginCreatedDate) = ? AND YEAR(logLoginCreatedDate) = ?)', array($month, $year))
		->get()->row('total');*/

		db::select('count(distinct OUL.userID) as total');
		db::from('OLAP_user_logins OUL');
		db::innerjoin('site_member', 'site_member.userID = OUL.userID');
		db::where('OUL.siteID', $siteIDs);
		db::where('site_member.siteMemberStatus', 1);
		db::where('MONTH(loginDate) = ? AND YEAR(loginDate) = ?', array($month, $year));
		db::group_by('OUL.siteID');
		$activeMembers = db::get()->row('total');

		if ($totalMembers == 0) {
			$active_member_percentage = 0;
		} else {
			$active_member_percentage = $activeMembers / $totalMembers * 100;			
		}

		db::select('count(userID) as total');
		db::from('site_member');
		db::where('siteID', $siteIDs);
		db::where('siteMemberStatus',1);
		$noOfMembers = db::get('site_member')->row('total');
		// $noOfMembers = db::row('total');

		//entrepreneurship article
		//$totalEntArticle =db::query("SELECT COUNT(`articleID`) AS 'total' FROM `article_category` WHERE `categoryID` = 3 
		//				AND `articleID` IN (SELECT `articleID` FROM `article` WHERE `siteID` = $siteID AND MONTH(articlePublishedDate)=$month AND YEAR(articlePublishedDate)=$year)")->result();

		
		db::select("count(article.articleID) as total");
		// db::from("article");
		db::join("article_category", "article.articleID = article_category.articleID");
		db::where("categoryID", 4);
		db::where("siteID", $siteIDs);
		db::where("MONTH(articlePublishedDate)", $month);
		db::where("YEAR(articlePublishedDate)", $year);

		$totalEntArticle = db::get('article')->result();
		// var_dump($totalEntArticle);
		// die;
		// db::query("SELECT COUNT(article.articleID) AS 'total' from article, article_category 
		// 	where  article_category.articleID = article.articleID and categoryID = 4
		// 	AND siteID = '$siteID' AND MONTH(articlePublishedDate) = $month AND YEAR(articlePublishedDate) = $year")->result();
		//print_r($totalEntArticle);
		//die();

		//$totalKdbSession = db::query("SELECT COUNT(`activityID`) AS 'total' FROM `activity` WHERE `activityType` =2 AND `activityApprovalStatus`=1 
		//	AND YEAR(`activityStartDate`) = $year AND `activityID` IN (SELECT `activityID` FROM `training` WHERE `trainingType` = 7 AND `trainingSubType` = 14)  ")->result();

		//$totalKdbPax = db::query("SELECT SUM(`trainingMaxPax`) AS 'totalpax' FROM `training` WHERE `activityID` IN (SELECT `activityID` FROM `activity` WHERE `activityType` =2 
		//	AND `activityApprovalStatus`=1 AND YEAR(`activityStartDate`) = $year AND `activityID` IN (SELECT `activityID` FROM `training` WHERE `trainingType` = 7 AND `trainingSubType` = 14))")->result();
	
		$kdb_sessions = 0;
		$kdb_pax = 0;

		db::select("COUNT(distinct activity.activityID) as sessions, COUNT(activity.activityID) as pax");
		// db::from("article");
		db::join("activity", "activity.activityID = activity_user.activityID");
		db::join("training", "training.activityID = activity.activityID");
		db::where("activityStartDate <= NOW() - INTERVAL 1 DAY ");
		db::where("siteID", $siteIDs);
		db::where("trainingType", 7);
		db::where("trainingSubType", 14);
		db::where("MONTH(activityStartDate)", $month);
		db::where("YEAR(activityStartDate)", $year);	
			
		$kdbData = db::get('activity_user')->result();

		$kdb_sessions += $kdbData[0]['sessions'];
		$kdb_pax += $kdbData[0]['pax'];

		$data['kpi'] = array(
			'event' => $totalEvents,
			'entrepreneurship_class' => $totalEntrepreneurship,
			'entrepreneurship_sales' => $sales,
			'training_hours' => $hours,
			//'active_member_percentage' => $activeMembers / $totalMembers * 100
			'active_member_percentage' => $active_member_percentage,
			'total_members' => $noOfMembers,
			'totalEntArticle' => $totalEntArticle[0]['total'],
			'kdb_session'=>$kdb_sessions,
			'kdb_pax'=>$kdb_pax
			//'kdb_session'=>$totalKdbSession[0]['total'],
			//'kdb_pax'=>$totalKdbPax[0]['totalpax']
			);

		// var_dump($data);
		// die;

		## to check system speed (site_log)
		if ($_SESSION['email']) {
			db::select('MAX(start) as maxDate');
			db::from('site_log');
			db::where('email', $_SESSION['email']);
			$maxDate = db::get()->row('maxDate');

			db::where("email", $_SESSION['email']);
			db::where("start", $maxDate);
			db::update("site_log", Array('siteID'=>$siteID,"end"=>now()));

			$_SESSION['email'] = null;
		}

		if($clusterUser){
			$data['cluster'] = $clusterUser['clusterID'];
		}	
		else{
			$data['cluster'] = $cluster;
		}	
		
		$data['site'] = $siteParam;
		// var_dump($data['cluster']);
		return view::render('shared/kpi/summary', $data);
	}

	public function kpi_overview($page = null)
	{
		  //make it sort
		   // $sort = $this->uri->segment(4);

     //       if (!$sort) {
     //        $sort = 'asc';
     //       }

     //    $this->getList('name, user_group_id', $sort);

		$year = $data['year'] = request::get('year', date('Y'));
		$month = $data['month'] = request::get('month', date('n'));

		$res_cluster	= model::load("site/cluster")->lists();
		foreach($res_cluster as $row)
		{
			$data['clusterR'][$row['clusterID']]	= $row['clusterName'];
		}

		if(request::get("cluster"))
		{
			db::where("siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = '".request::get("cluster")."')");
		}

		// test

		$data['max'] = array(
			'event' => 2,
			'entrepreneurship_class' => 1,
			'entrepreneurship_sales' => 300,
			'training_hours' => 32,
			'active_member_percentage' => 80,
			'kdb_sessions' => 30,
			'kdb_pax' => 600			
			);

		db::from('site');
		db::order_by('siteName');

		if(authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD)
		{
			db::where('siteID IN (SELECT cluster_site.siteID FROM cluster_site WHERE cluster_site.clusterID IN (SELECT cluster_lead.clusterID FROM cluster_lead WHERE cluster_lead.userID = ?))', array(authData('user.userID')));
		}

		$sites = $data['sites'] = db::get()->result('siteID');
		$siteIDs = array_keys($sites);
		
		$clusterUser = model::load("site/cluster")->getClusterByUser(authData('user.userID'));
		$clusterDetails = model::load("site/cluster")->getClusterByID($clusterUser['clusterID']);	

		$auditScore = $clusterDetails['clusterAuditScore'];

		if(count($sites) > 0)
		{
			// 1. Total events. Get total event done

			db::select('siteID, noOfActivities');
			db::from('OLAP_articled_activities');
			db::where('siteID', $siteIDs);
			db::where('month = ? AND year = ?', array($month, $year));
			db::group_by('siteID');
			$groupedEvents = db::get()->result('siteID', true);			

			// 2. enterpreneuship class

			db::select('siteID, count(activity.activityID) as total');
			db::from('activity');
			db::where('siteID', $siteIDs);
			db::where('activityType', 2);
			db::where('activityApprovalStatus', 1);
			db::where('activityStartDate <= NOW() - INTERVAL 1 DAY ');
			db::where('MONTH(activityStartDate) = ? AND YEAR(activityStartDate) = ?', array($month, $year));
			db::where('training_type.trainingTypeName LIKE ?', array('%Entrepreneurship%'));
			// ->join('activity_article', 'activity_article.activityID = activity.activityID', 'INNER JOIN')
			db::join('training', 'activity.activityID = training.activityID', 'INNER JOIN');
			db::join('training_type', 'training.trainingType = training_type.trainingTypeID', 'INNER JOIN');
			db::group_by('siteID');
			$groupedEntrepreneurships = db::get()->result('siteID', true);

			// var_dump($groupedEntrepreneurships);
			// 3. entrepreneurship program (accumulated amounts of sales)

			db::select('siteID, (billingTransactionItemPrice) * (billingTransactionItemQuantity) as total');
			db::from('billing_transaction_item');
			db::where('billing_transaction.siteID', $siteIDs);
			db::where('MONTH(billingTransactionDate) = ? AND YEAR(billingTransactionDate) = ?', array($month, $year));
			db::where('billing_transaction_item.billingItemID IN (SELECT billingItemID FROM billing_item WHERE billingItemCode = ?)', array('lms_item'));
			db::join('billing_transaction', 'billing_transaction.billingTransactionID = billing_transaction_item.billingTransactionID');
			db::group_by('siteID');
			$groupedSales = db::get()->result('siteID', true) ? : 0;			
			// var_dump($groupedSales);
			// die;
			// 4. training hours

			db::select('siteID, sum(time_to_sec(timediff(endTime, startTime)) / 3600) as total');
			db::from('OLAP_site_activity_date_times');
			db::where('siteID', $siteIDs);

			db::where('activityDate <= NOW() - INTERVAL 1 DAY');
			db::where('MONTH(activityDate) = ? AND YEAR(activityDate) = ?', array($month, $year));
			db::group_by('siteID');
			$groupedTrainingHours = db::get()->result('siteID', true);
			
			// $groupedTrainingHours = 0;
			// $groupedTrainingHours += $trainingHours;

			// 5. active members

			db::select('OUL.siteID, count(distinct OUL.userID) as total');
			db::from('OLAP_user_logins OUL');
			db::innerjoin('site_member', 'site_member.userID = OUL.userID');
			db::where('OUL.siteID', $siteIDs);
			db::where('site_member.siteMemberStatus', 1);
			db::where('MONTH(loginDate) = ? AND YEAR(loginDate) = ?', array($month, $year));
			db::group_by('OUL.siteID');
			$activeMembers = db::get()->result('siteID', true);

			// var_dump($groupedMembers);
			// die;
			// 5.1 total members.
			$totalMembers = db::from('site_member')
			->select('siteID', 'count(userID) as total')
			->where('siteID', $siteIDs)
			->where('siteMemberStatus',1)
			->group_by('siteID')
			->get()->result('siteID');
			//print_r($totalMembers);

			//population
			$totalpopulation = db::from('site')
			->select('site.siteID','site_info.siteInfoPopulation')
			->join("site_info","site.siteID = site_info.siteID")
			->where('site_info.siteID', $siteIDs)
			//->group_by('site_member.siteID')
			//->get()->result('site_member.siteID');
			->get()->result('siteID', true);
			//print_r($totalpopulation);

			db::select("COUNT(distinct activity.activityID) as sessions, COUNT(activity.activityID) as pax");
			// db::from("article");
			db::join("activity", "activity.activityID = activity_user.activityID");
			db::join("training", "training.activityID = activity.activityID");
			db::where("activityStartDate <= NOW() - INTERVAL 1 DAY ");
			db::where("siteID", $siteIDs);
			db::where("trainingType", 7);
			db::where("trainingSubType", 14);
			db::where("MONTH(activityStartDate)", $month);
			db::where("YEAR(activityStartDate)", $year);	
			db::group_by("siteID");

			$kdbData = db::get('activity_user')->result('siteID', true);
			// var_dump($siteIDs);
			$data['total'] = array();
			$data['population'] = array();
			$data['total_members'] = array();

			// main site loop.
			foreach($sites as $row_site)
			{


				$siteID = $row_site['siteID'];
				// var_dump( $totalpopulation[$siteID][0]["siteInfoPopulation"]);
				// die;
				if(!isset($data['total'][$siteID]))
					$data['total'][$siteID] = array();

				$total = &$data['total'][$siteID];


				// 1. prepare total events
				//var_dump($siteID);
				// var_dump($groupedEvents[$siteID]);
				// die;
				$total['event'] = 0;
				if(isset($groupedEvents[$siteID]))
				{
					// var_dump($groupedEvents[$siteID]);
					foreach($groupedEvents[$siteID] as $event)
						$total['event'] = $event['noOfActivities'];
				}


				// 2. entrepreneurship class
				$total['entrepreneurship_class'] = 0;
				// var_dump($groupedEntrepreneurships[$siteID]);
				if(isset($groupedEntrepreneurships[$siteID]))
				{
					foreach($groupedEntrepreneurships[$siteID] as $entrepreneurship){
							$total['entrepreneurship_class'] = $entrepreneurship['total'];
					}
						
				}

				// 3. entrepreneurship sales
				// $total['entrepreneurship_sales'] = 0;
				$total['entrepreneurship_sales'] = 0;
				// var_dump($groupedSales);

				if(isset($groupedSales[$siteID]))
				{
					// var_dump($groupedSales[$siteID]);
					foreach($groupedSales[$siteID] as $billingItem)
					{
						// var_dump($billingItem);
						$total['entrepreneurship_sales'] = $billingItem['total'];

					}
				}

				// 4. training hours
				$total['training_hours'] = 0;
				// var_dump($groupedTrainingHours[$siteID]);
				if(isset($groupedTrainingHours[$siteID]))
				{
					foreach($groupedTrainingHours[$siteID] as $trainingHour)
					{
						//var_dump($trainingHour);
						$total['training_hours'] = $trainingHour['total'];
					}
				}

				$total['population'] = 0;
				if(isset($totalpopulation[$siteID]))
				{
					foreach($totalpopulation[$siteID] as $population)
						$total['population']++;
				}


				// 5. active members.
				// var_dump($activeMembers[$siteID][0]['total']);
				$siteActiveMember = $activeMembers[$siteID][0]['total'];

				if($siteActiveMember > 0 && $totalMembers[$siteID]['total'] > 0)
					$total['active_member_percentage'] = $siteActiveMember / $totalMembers[$siteID]['total'] * 100;
				else
					$total['active_member_percentage'] = 0;

				// 6. population
				$siteTotalPopulation = 0;
				if(isset($totalpopulation[$siteID][0]))
					$siteTotalPopulation =  $totalpopulation[$siteID][0]['siteInfoPopulation'];

				 if($siteTotalPopulation > 0 && $totalpopulation[$siteID][0]['siteInfoPopulation'] > 0)
				 	$total['population'] = $totalpopulation[$siteID][0]['siteInfoPopulation'];
				else
					$total['population'] = 0;

				// 7. members.
				$siteTotalMember = 0;
				if(isset($totalMembers[$siteID]))
					$siteTotalMember = $totalMembers[$siteID]['total'];

				if($siteTotalMember > 0 && $totalMembers[$siteID]['total'] > 0)
					$total['total_members'] = $totalMembers[$siteID]['total'];
				else
					$total['total_members'] = 0;


				$kdb_sessions = 0;
				$kdb_pax = 0;	
				// $total['kdbSession'] = 0;
				// $total['kdbPax'] = 0;
				if(isset($kdbData[$siteID])){
					// var_dump('aaa');
					foreach ($kdbData[$siteID] as $kdbDataSingle) {
						# code...
						// var_dump($kdbDataSingle['sessions']);
						$kdbDataSingle['sessions'] != '' ? 	$total['kdbSession'] = $kdbDataSingle['sessions'] : $total['kdbSession'] = $kdb_sessions;
						$kdbDataSingle['pax'] != '' ? 		$total['kdbPax'] 	 = $kdbDataSingle['pax'] : $total['kdbPax'] = $kdb_pax;
					}
				}
				//display same audit score for the cluster
				$total['auditScore'] = $auditScore;							
			}

			// die;
		}

		 
		view::render('shared/kpi/overview2', $data);
	}



	public function kpi_overview_old($page = 1, $month = null,$year = null)
	{
		$data['selectYear'] = $year = $year ? : date("Y");
		$data['selectMonth'] = $month = $month ? : date("n");

		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();
		
		} else {

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();
		}

		foreach($res_site as $key => $kpi)
		{		
			$siteID = $kpi['siteID'];

			$event['done'] = 0;

			$test = model::load("blog/article")->getReportBySiteID($siteID,$year,$month);		
			$totalEvent  = count($test);

			$event['maxEvent'] = 2;
		
			if ($totalEvent >= $event['maxEvent']) {
				$event['totalEvent'] = $totalEvent;	
				$event['done'] = 1;
			} else {
				
				if ($totalEvent == "") {
					$event['totalEvent'] = 0;		
				}	else {
					$event['totalEvent'] = $totalEvent;		
				}

			}
			
		//---------------------------------------------------------------------------
		## user login
		$login['done'] = 0;
		$active = db::where('month(logLoginCreatedDate) = ? AND year(logLoginCreatedDate) = ?', array($month, $year))
		->where('userID IN (SELECT userID FROM site_member WHERE siteID = ?)', array($siteID))
		->group_by('userID')->get('log_login')->num_rows();

		$all = db::where('siteID', $siteID)->get('site_member')->num_rows();
		
			$cond =	Array(			
				"year(userLastLogin)"=>$year,
				"month(userLastLogin)"=>$month			
					);
	
		if ($active == 0){ 

			$target = 0;
		} else {

			$target = ($active/$all)*100;
		}
		
 	

		if ($target >= 60)
		{ 
			$login['done'] = 1;	
		}

		$target = round((float)$target) . '%';
		$login['target'] = $target;

		
		//---------------------------------------------------------------------------
		## training hour

		$activitySlug = 2;
		$training['done'] = 0;	

		$activityID = model::load("activity/activity")->getActivityIDListPerSlug($siteID,$year,$month,2,$groupBy);
//print_r($activityID);
		foreach($activityID as $no=>$row)
		{
			$totalTime = 0;

				$start_time = strtotime($row['activityDateStartTime']); 	
				$end_time = strtotime($row['activityDateEndTime']);

			$totalTime = $end_time - $start_time;
			$totalTime1 = $totalTime1 + $totalTime;				
		}

		$trainingHour = floor($totalTime1/3600);
		$training['maxHour'] = 48;

		if($trainingHour >= $training['maxHour'])
		{ 
			$training['done'] = 1;	
			$training['hour'] = $trainingHour;	
		}
		else
		{
			$training['hour'] = $trainingHour;
		}

		$totalTime1 = 0;
			  
		//--------------------------------------------------------------------
		##  getEntrepreneurshipBySlug
		$entClass['done'] = 0;
		$entClass = model::load("activity/activity")->getEntrepreneurshipBySlug($siteID,$month,$year);

		$totalClass = count($entClass);
		$entClass['maxClass'] = 1;

		if ($totalClass >= $entClass['maxClass']) {
			$entClass['totalEvent'] = $totalClass;	
			$entClass['done'] = 1;
		}
		else
		{
			if ($totalClass == "")
			{
				$entClass['totalEvent'] = 0;		
			}
			else
			{
				$entClass['totalEvent'] = $totalClass;		
			}
		}
		
		//--------------------------------------------------------------------
		##  get sales
		$entProgram['done'] = 0;
		$sales = model::load("sales/sales")->getSales($siteID,$month,$year);

		$totalSale = $sales[0][totalSale];
		$entProgram['maxSale'] = 300;

		if ($totalSale >= $entProgram['maxSale']) {
			$entProgram['total'] = $totalSale;	
			$entProgram['done'] = 1;
		} else {
			
			if ($totalSale == "") {
				$entProgram['total'] = 0;		
			}	else {
				$entProgram['total'] = $totalSale;		
			}

		}

		$data['kpiAll'][$key] = array ( 

							"sitename"=>$kpi['siteName'],
							"login"=>$login,
							"training"=>$training,
							"entClass"=>$entClass,
							"entProgram"=>$entProgram,
							"event"=>$event

						);		
	}
			$show_per_page = 20;
 			$totalRow = count($data['kpiAll']);

			pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
			pagination::initiate(Array(
								"totalRow"=>$totalRow,
								"limit"=>$show_per_page,				
								"urlFormat"=>url::base("kpi/kpi_overview/{page}/$month/$year"),
								"currentPage"=>$page
										));
				
			$page = $page < 1 ? 1 : $page;
  			$start = ($page - 1) * ($show_per_page);
  			$offset = $show_per_page;

  			$data['kpiAll'] = array_slice($data['kpiAll'], $start, $offset);

	view::render("shared/kpi/overview", $data);
	
	}

}