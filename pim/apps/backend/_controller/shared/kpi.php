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
			$groupedEvents = db::from('activity')
			->where('siteID', $siteIDs)
			->where('activityType', 1)
			->where('activityApprovalStatus', 1)
			->where('activityID IN (SELECT activityID FROM activity_article WHERE activity_article.activityID = activity.activityID)')
			->where('MONTH(activityStartDate) = ? AND YEAR(activityStartDate) = ?', array($month, $year))
			->get()->result('siteID', true);

			// 2. enterpreneuship class
			$groupedEntrepreneurships = db::from('activity')
			->where('siteID', $siteIDs)
			->where('activityType', 2)
			->where('activityApprovalStatus', 1)
			->where('MONTH(activityStartDate) = ? AND YEAR(activityStartDate) = ?', array($month, $year))
			->where('training_type.trainingTypeName LIKE ?', '%Entrepreneurship%')
			// ->join('activity_article', 'activity_article.activityID = activity.activityID', 'INNER JOIN')
			->join('training', 'activity.activityID = training.activityID', 'INNER JOIN')
			->join('training_type', 'training.trainingType = training_type.trainingTypeID', 'INNER JOIN')
			->get()->result('siteID', true);

			// 3. entrepreneurship program (accumulated amounts of sales)
			$groupedSales = db::from('billing_transaction_item')
			->where('billing_transaction.siteID', $siteIDs)
			->where('MONTH(billingTransactionDate) = ? AND YEAR(billingTransactionDate) = ?', array($month, $year))
			->where('billing_transaction_item.billingItemID IN (SELECT billingItemID FROM billing_item WHERE billingItemCode = ?)', array('lms_item'))
			->join('billing_transaction', 'billing_transaction.billingTransactionID = billing_transaction_item.billingTransactionID')
			->get()->result('siteID', true);

			// 4. training hours

			$groupedTrainingHours = 
			//db::from('activity_date')
			db::select('activityDateStartTime', 'activityDateEndTime', 'siteID', 'activity.activityID')
			->where('activityType', 2)
			->where('activityApprovalStatus', 1)
			->where('activity.siteID', $siteIDs)
			// ->where('activity_date.activityID IN (SELECT activity_article.activityID FROM activity_article)')
			->where('activity_date.activityID IN (SELECT activityID FROM activity_user)') // rsvp
			->where('MONTH(activity.activityStartDate) = ? AND YEAR(activity.activityStartDate) = ?', array($month, $year))
			->join('activity', 'activity.activityID = activity_date.activityID', 'INNER JOIN')
			// ->join('activity_article', 'activity_article.activityID = activity.activityID', 'INNER JOIN')
			->get('activity_date')->result('siteID', true);



			// 5. active members
			/*$groupedMembers = db::from('user')
			->select('user.userID', 'siteID')
			->where('userLevel', 1)
			->where('site_member.siteID', $siteIDs)
			->where('user.userID IN (SELECT userID FROM log_login WHERE MONTH(logLoginCreatedDate) = ? AND YEAR(logLoginCreatedDate) = ?)', array($month, $year))
			->join('site_member', 'site_member.userID = user.userID', 'INNER JOIN')
			->get()->result('siteID', true);*/

			$groupedMembers = db::from('site_member')
			->select('siteID', 'count(userID) as total')
			->where('siteID', $siteIDs)
			->where('siteMemberStatus',1)
			->where('userID IN (SELECT userID FROM log_login WHERE MONTH(logLoginCreatedDate) = ? AND YEAR(logLoginCreatedDate) = ?)', array($month, $year))
			->group_by('siteID')
			->get()->result('siteID');

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
				$total['event'] = 0;
				if(isset($groupedEvents[$siteID]))
				{
					foreach($groupedEvents[$siteID] as $event)
						$total['event']++;
				}


				// 2. entrepreneurship class
				$total['entrepreneurship_class'] = 0;
				if(isset($groupedEntrepreneurships[$siteID]))
				{
					foreach($groupedEntrepreneurships[$siteID] as $entrepreneurship)
						$total['entrepreneurship_class']++;
				}

				// 3. entrepreneurship sales
				// $total['entrepreneurship_sales'] = 0;
				$total['entrepreneurship_sales'] = 0;

				if(isset($groupedSales[$siteID]))
				{
					foreach($groupedSales[$siteID] as $billingItem)
					{
						$total = $billingItem['billingTransactionItemQuantity'] * $billingItem['billingTransactionItemPrice'];
						$total['entrepreneurship_sales'] += $total;
					}
				}

				// 4. training hours
				$total['training_hours'] = 0;
				if(isset($groupedTrainingHours[$siteID]))
				{
					foreach($groupedTrainingHours[$siteID] as $trainingHour)
					{
						$hour =  (strtotime($trainingHour['activityDateEndTime']) - strtotime($trainingHour['activityDateStartTime'])) / 3600;

						$total['training_hours'] += $hour;
					}
				}

				$total['population'] = 0;
				if(isset($totalpopulation[$siteID]))
				{
					foreach($totalpopulation[$siteID] as $population)
						$total['population']++;
				}


				// 5. active members.
				$siteTotalMember = 0;
				if(isset($groupedMembers[$siteID]))
					$siteTotalMember = $groupedMembers[$siteID]['total'];

				if($siteTotalMember > 0 && $totalMembers[$siteID]['total'] > 0)
					$total['active_member_percentage'] = $siteTotalMember / $totalMembers[$siteID]['total'] * 100;
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