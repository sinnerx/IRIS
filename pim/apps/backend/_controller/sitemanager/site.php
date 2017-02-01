<?php
Class Controller_Site
{
	public function overview($year = null, $month = null)
	{

		$siteID = site()->siteID;

		$data['year'] = $year = $year ? : date('Y');
		$data['month'] = $month = $month ? : date('n');

		$data['max'] = array(
			'event' => 2,
			'entrepreneurship_class' => 1,
			'entrepreneurship_sales' => 300,
			'training_hours' => 32,
			'active_member_percentage' => 80,
			'kdb_sessions' => 30,
			'kdb_pax' => 600
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

	
		db::select('noOfActivities');
		db::from('OLAP_articled_activities');
		db::where('siteID', $siteID);
		db::where('month = ? AND year = ?', array($month, $year));
		$totalEvents = db::get()->row('noOfActivities');

		if ($totalEvents == null) {
			$totalEvents = 0;
		}

		// total entrepreneurship class
		// activity : training
		// has at least 1 article
		 
		
		db::select('count(activity.activityID) as total');
		db::from('activity');
		db::where('siteID', $siteID);
		db::where('activityType', 2);
		db::where('activityApprovalStatus', 1);
		db::where('activityStartDate <= NOW() - INTERVAL 1 DAY ');
		db::where('MONTH(activityStartDate) = ? AND YEAR(activityStartDate) = ?', array($month, $year));
		db::where('training_type.trainingTypeName LIKE ?', array('%Entrepreneurship%'));
		// ->join('activity_article', 'activity_article.activityID = activity.activityID', 'INNER JOIN')
		db::join('training', 'activity.activityID = training.activityID', 'INNER JOIN');
		db::join('training_type', 'training.trainingType = training_type.trainingTypeID', 'INNER JOIN');
		$totalEntrepreneurship = db::get()->row('total');

		// entrepreneurship program
		// table : billing_transaction_item 
		// billingItemCode = lms_item
		
		db::select('SUM(billingTransactionItemPrice * billingTransactionItemQuantity) as total');
		db::from('billing_transaction_item');
		db::where('billing_transaction.siteID = ?', array($siteID));
		db::where('MONTH(billingTransactionDate) = ? AND YEAR(billingTransactionDate) = ?', array($month, $year));
		db::where('billing_transaction_item.billingItemID IN (SELECT billingItemID FROM billing_item WHERE billingItemCode = ?)', array('lms_item'));
		db::join('billing_transaction', 'billing_transaction.billingTransactionID = billing_transaction_item.billingTransactionID');
		$sales = db::get()->row('total') ? : 0;

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
		db::where('siteID', $siteID);

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
		$totalMembers = db::select('count(userID) as total')->where('siteID', $siteID)->get('site_member')->row('total');

		/*$activeMembers = db::from('site_member')
		->select('count(userID) as total')
		->where('siteID', $siteID)
		->where('siteMemberStatus',1)
		->where('userID IN (SELECT userID FROM log_login WHERE MONTH(logLoginCreatedDate) = ? AND YEAR(logLoginCreatedDate) = ?)', array($month, $year))
		->get()->row('total');*/

		
		db::select('count(distinct userID) as total');
		db::from('OLAP_user_logins');
		db::where('siteID', $siteID);
		db::where('MONTH(loginDate) = ? AND YEAR(loginDate) = ?', array($month, $year));
		$activeMembers = db::get()->row('total');

		if ($totalMembers == 0) {
			$active_member_percentage = 0;
		} else {
			$active_member_percentage = $activeMembers / $totalMembers * 100;			
		}

		db::select('count(userID) as total');
		db::from('site_member');
		db::where('siteID', $siteID);
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
		db::where("siteID", $siteID);
		db::where("MONTH(articlePublishedDate)", $month);
		db::where("YEAR(articlePublishedDate)", $year);

		$totalEntArticle = db::get('article')->result();		
		//print_r($totalEntArticle);
		//die();

		//$totalKdbSession = db::query("SELECT COUNT(`activityID`) AS 'total' FROM `activity` WHERE `activityType` =2 AND `activityApprovalStatus`=1 
		//	AND YEAR(`activityStartDate`) = $year AND `activityID` IN (SELECT `activityID` FROM `training` WHERE `trainingType` = 7 AND `trainingSubType` = 14)  ")->result();

		//$totalKdbPax = db::query("SELECT SUM(`trainingMaxPax`) AS 'totalpax' FROM `training` WHERE `activityID` IN (SELECT `activityID` FROM `activity` WHERE `activityType` =2 
		//	AND `activityApprovalStatus`=1 AND YEAR(`activityStartDate`) = $year AND `activityID` IN (SELECT `activityID` FROM `training` WHERE `trainingType` = 7 AND `trainingSubType` = 14))")->result();
	
		$kdb_sessions = 0;
		$kdb_pax = 0;
		// $kdbData = db::query("SELECT COUNT(distinct activity.activityID) as sessions, COUNT(activity.activityID) as pax
		// 	FROM activity_user, activity, training
		// 	WHERE siteID = '$siteID'
		// 	AND activityStartDate <= NOW() - INTERVAL 1 DAY 
		// 	AND (YEAR(activityStartDate) = $year OR YEAR(activityEndDate) = $year)
		// 	AND (MONTH(activityStartDate) <= $month OR MONTH(activityEndDate) <= $month)
		// 	AND activity.activityID = activity_user.activityID AND training.activityID = activity.activityID
		// 	AND trainingType = 7 AND trainingSubType = 14")->result();

		db::select("SUM(session) as sessions , SUM(pax) as pax");
		db::where("siteID", $siteID);
		db::where("month", $month);
		db::where("year", $year);
		$kdbData = db::get('OLAP_kdb_session_pax')->result();

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

		$clusterID 		= model::load("site/cluster")->getClusterID($siteID);
		//var_dump($clusterID[0]);
		$clusterDetails = model::load("site/cluster")->getClusterByID($clusterID[0]);
		$auditScore 	= $clusterDetails['clusterAuditScore'];
		$auditScore  === null ? $data['auditScore'] = "N/A" : $data['auditScore'] = $auditScore;
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

		return view::render('sitemanager/site/overview', $data);
	}

	public function overview_old($year = null,$month = null)
	{
		$data['year'] = $year = $year ? : date("Y");
		$data['month'] = $month = $month ? : date("n");

		$siteID	= authData('site.siteID');


		//---------------------------------------------------------------------------
		## event

		$test	= model::load("blog/article")->getReportBySiteID($siteID,$year,$month);
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
			
		$data['event'] = $event;

		//---------------------------------------------------------------------------
		## user login
		$active = db::where('month(logLoginCreatedDate) = ? AND year(logLoginCreatedDate) = ?', array($month, $year))
		->where('userID IN (SELECT userID FROM site_member WHERE siteID = ?)', array($siteID))
		->group_by('userID')->get('log_login')->num_rows();
		$all = db::where('siteID', $siteID)->get('site_member')->num_rows();
		
			$cond =	Array(			
				"year(userLastLogin)"=>$year,
				"month(userLastLogin)"=>$month			
					);

		$target = ($active/$all)*100;

		if ($target >= 60)
		{ 
			$login['done'] = 1;	
		}

		$target = round((float)$target) . '%';
		$login['target'] = $target;

		$data['login'] = $login;

		//---------------------------------------------------------------------------
		## training hour

		$activitySlug = 2;

		$activityID = model::load("activity/activity")->getActivityIDListPerSlug($siteID,$year,$month,2,$groupBy);

		foreach($activityID as $no=>$row)
		{
			$totalTime = 0;

				$start_time = strtotime($row['activityDateStartTime']); 	
				$end_time = strtotime($row['activityDateEndTime']);

			$totalTime = $end_time - $start_time;
			$totalTime1 = $totalTime1 + $totalTime;				
		}

		$trainingHour = floor($totalTime1/3600);
		$training['maxHour'] = 32;

		if($trainingHour >= $training['maxHour'])
		{ 
			$training['done'] = 1;	
			$training['hour'] = $trainingHour;	
		}
		else
		{
			$training['hour'] = $trainingHour;
		}

		$data['training'] = $training;			
			  
		//--------------------------------------------------------------------
		##  getEntrepreneurshipBySlug

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
		
		$data['entClass'] = $entClass;

		//--------------------------------------------------------------------
		##  get sales
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
		
		$data['entProgram'] = $entProgram;

		//--------------------------------------------------------------------

		view::render("sitemanager/site/overview",$data);
	}

	public function message($page = 1)
	{
		$messages = model::orm('site/message')
		->where('site_message.siteID', site()->siteID)
		->prepare(function($db)
		{
			if($category = request::get('category'))
				$db->where('siteMessageCategory', $category);
		})
		->paginate(array(
			'currentPage'=>$page,
			'urlFormat'=>url::base('site/message/{page}'),
			'limit'=>10
			))
		->order_by('siteMessageStatus', 'asc')
		->order_by('messageCreatedDate', 'desc')
		->execute();

		$data['categoryNames'] = model::load('site/message')->getCategoryName();
		$data['messages'] = $messages;

		view::render('sitemanager/site/message', $data);
	}

	public function messageView($encryptedSiteMessageID)
	{
		$siteMessageID = model::load('site/message')->encryptID($encryptedSiteMessageID, 'decrypt');
		$siteMessage = model::orm('site/message')->find($siteMessageID);

		// read this message as soon as user open this submodule.
		$siteMessage->read();

		$data['message'] = $siteMessage;

		view::render('sitemanager/site/messageView', $data);
	}

	public function messageClose($siteMessageID)
	{
		$this->template = false;

		$data['siteMessageID'] = $siteMessageID;

		if(form::submitted())
		{
			model::orm('site/message')->find($siteMessageID)->close(input::get('siteMessageRemark'));

			redirect::to('site/message', 'Message has been marked as closed.');
		}

		view::render('sitemanager/site/messageClose', $data);
	}


		public function kpiMonthly($currentyear)
	{

		$siteID	= authData('site.siteID');
		$year = $currentyear;

		if(request::isAjax())
			$this->template = false;

			for ($m=1; $m<=12; $m++) {
	    
	    		$monthName = date('F', mktime(0,0,0,$m, 1, $year)); 		
				## month
     			$data[$m]['monthName'] = $monthName;
				## event
				$test	= model::load("blog/article")->getReportBySiteID($siteID,$year,$m);
				$data[$m]['event'] = count($test);
				## login
				$active = db::where('month(logLoginCreatedDate) = ? AND year(logLoginCreatedDate) = ?', array($m, $year))
						->where('userID IN (SELECT userID FROM site_member WHERE siteID = ?)', array($siteID))
						->group_by('userID')->get('log_login')->num_rows();
				$all 	= db::where('siteID', $siteID)->get('site_member')->num_rows();
		
				$cond =	Array(			
							"year(userLastLogin)"=>$year,
							"month(userLastLogin)"=>$month			
						);

				$target = ($active/$all)*100;
				$target = round((float)$target) . '%';
				$data[$m]['login'] = $target;
				## training
				$activityID = model::load("activity/activity")->getActivityIDListPerSlug($siteID,$year,$m,2,$groupBy);		 
					$totalTime1 = 0;
					foreach($activityID as $no=>$row)
					{
						$start_time = strtotime($row['activityDateStartTime']); 	
						$end_time = strtotime($row['activityDateEndTime']);
						$totalTime = $end_time - $start_time;
						$totalTime1 = $totalTime1 + $totalTime;				
					}
					$trainingHour = floor($totalTime1/3600);
				
				$data[$m]['training'] = $trainingHour;						 
				##  entreclass
				$entClass = model::load("activity/activity")->getEntrepreneurshipBySlug($siteID,$m,$year);		
				$totalClass = count($entClass);
				$data[$m]['entreclass'] = $totalClass;
				##  entreprogram
				$sales = model::load("sales/sales")->getSales($siteID,$m,$year);		
				$totalSale = $sales[0][totalSale];	
				if ($totalSale == "") { $totalSale ="0"; }		
				$data[$m]['entreprogram'] = $totalSale;

    		 }

    		 $data['data'] = $data;
    		 $data['year'] = $year;

		
		view::render('sitemanager/site/kpimonthly', $data);
	}
}