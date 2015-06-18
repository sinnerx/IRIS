<?php
Class Controller_Kpi
{
	public function kpi_overview($page = 1, $month = null,$year = null)
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