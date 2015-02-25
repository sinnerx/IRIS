<?php
Class Controller_Site
{
	public function overview($year = null,$month = null)
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
				$event['totalEvent'] = $event['maxEvent'];	
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
		$training['maxHour'] = 48;

		if($trainingHour >= $training['maxHour'])
		{ 
			$training['done'] = 1;	
			$training['hour'] = 48;	
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
			$entClass['totalEvent'] = $entClass['maxClass'];	
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
			$entProgram['total'] = $entProgram['maxSale'];	
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
				//if ($totalSale == "") { $totalSale ="0"; }		
				$data[$m]['entreprogram'] = $totalSale;

    		 }

    		 $data['data'] = $data;
    		 $data['year'] = $year;

		
		view::render('sitemanager/site/kpimonthly', $data);
	}
}