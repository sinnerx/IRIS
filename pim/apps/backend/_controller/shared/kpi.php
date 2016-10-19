<?php
Class Controller_Kpi
{
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
			'active_member_percentage' => 80
			);

		db::from('site');
		db::order_by('siteName');

		if(authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD)
		{
			db::where('siteID IN (SELECT cluster_site.siteID FROM cluster_site WHERE cluster_site.clusterID IN (SELECT cluster_lead.clusterID FROM cluster_lead WHERE cluster_lead.userID = ?))', array(authData('user.userID')));
		}

		$sites = $data['sites'] = db::get()->result('siteID');
		$siteIDs = array_keys($sites);
		

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

			db::select('siteID, count(distinct userID) as total');
			db::from('OLAP_user_logins');
			db::where('siteID', $siteIDs);
			db::where('MONTH(loginDate) = ? AND YEAR(loginDate) = ?', array($month, $year));
			db::group_by('siteID');
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