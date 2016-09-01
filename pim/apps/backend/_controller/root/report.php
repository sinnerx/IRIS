<?php
//use Knp\Snappy\Pdf;
require_once(getcwd().'/pim/apps/_library/fpdf181/fpdf.php');


class Controller_Report
{

	public function reportDashboardGenerator(){
			$input = input::get();
			//var_dump($input);			


			switch ($input['idreport']) {
				case 1:
					# code...
					$this->reportDashboardTraining($input);
					break;				
				case 2:
					# code...
					$this->reportDashboardEvent($input);
					break;		
				case 11:
					# code...
					$this->reportDashboardUSPMircosites($input);
					break;
				case 12:
					# code...
					$this->reportUSPStaffStrength();
					break;				
				case 13:
					# code...
					$this->reportEntrepreneurship($input);
					break;				
				case 15:
					# code...
					$this->reportLMSTraining($input);
					break;
				case 14:
					# code...
					$this->reportCashFlowSummaryFull($input);
					break;
				case 16:
					# code...
					$this->reportCashFlowSummaryFull($input);
					break;
				
				default:
					# code...
					break;
			}
	}

	private function reportLMSTraining($input){
		//input
		$year = $input['year'];

		// db::select("MONTH(BT.billingTransactionDate) as monthly, count(BTI.billingTransactionItemID) as totalPaid");
		// db::from("billing_transaction_item BTI");
		// db::join("billing_transaction BT", "BT.billingTransactionID = BTI.billingTransactionID", "INNER JOIN");
		// db::where("BTI.billingItemID IN (12,13,14)");
		// db::where("YEAR(BT.billingTransactionDate)", $year);
		// db::group_by("monthly");

		db::select("MONTH(A.activityCreatedDate) as monthly, count(A.activityID) as totalPaid");
		db::from("training T");
		db::join("activity A", "A.activityID = T.activityID");
		db::join("training_lms TLMS", "TLMS.trainingID = T.trainingID");
		db::join("lms_package_module LPM", "LPM.id = TLMS.packageModuleID");
		db::join("lms_package P", "P.packageID = LPM.packageID");
		db::join("billing_item BI", "BI.billingItemID = P.billing_item_id");
		db::group_by("T.trainingID");
		db::group_by("MONTH(A.activityCreatedDate)");
		db::where("BI.billingItemPrice > 0");
		db::where("YEAR(A.activityCreatedDate)", $year);
		$resultTrainingPaid = db::get()->result();
		// var_dump($resultTrainingPaid);
		// die;

		// db::select("MONTH(A.activityCreatedDate) as monthly, count(U.userID) as totalFree");
		// db::from("user U");
		// db::join("activity_user AU", "AU.userID = U.userID", "INNER JOIN");
		// db::join("activity A", "A.activityID = AU.activityID", "INNER JOIN");
		// db::join("training T", "T.activityID = A.activityID", "INNER JOIN");
		// db::join("training_lms TLMS", "T.trainingID = TLMS.trainingID", "INNER JOIN");

		db::select("MONTH(A.activityCreatedDate) as monthly, count(A.activityID) as totalFree");
		db::from("training T");
		db::join("activity A", "A.activityID = T.activityID");
		db::join("training_lms TLMS", "TLMS.trainingID = T.trainingID");
		db::join("lms_package_module LPM", "LPM.id = TLMS.packageModuleID");
		db::join("lms_package P", "P.packageID = LPM.packageID");
		db::join("billing_item BI", "BI.billingItemID = P.billing_item_id");
		db::group_by("T.trainingID");
		db::group_by("MONTH(A.activityCreatedDate)");
		db::where("BI.billingItemPrice = 0");
		db::where("YEAR(A.activityCreatedDate)", $year);
		$resultTrainingFree = db::get()->result();
		// var_dump($resultTrainingFree);
		// die;		

		$arrayResult[0][0]['typeTraining'] 		= "SMART LEARNING (# OF PAID)";
		$arrayResult[1][0]['typeTraining'] 		= "SMART LEARNING (# OF FREE)";
		$arrayResult[2][0]['typeTraining'] 		= "INTEL TTT (# OF PERSONNEL)";

		//make skeleton of array
		for($keyRow = 0; $keyRow <= 2; $keyRow++){
			//$arrayResult[$keyRow][0]['typeTraining'] 		= "";
			if($keyRow == 0){
				for($x = 1; $x <= 12; $x++){
					$arrayResult[0][1][$x]['totalPaid'] = "";
				}				
			}
			else if ($keyRow == 1){
				for($x = 1; $x <= 12; $x++){
					$arrayResult[1][1][$x]['totalFree'] = "";
				}
			}			
			else if ($keyRow == 2){
				for($x = 1; $x <= 12; $x++){
					$arrayResult[2][1][$x]['totalIntel'] = "";
				}
			}

		}
		// var_dump($arrayResult);
		// die;
		//paid lms training
		foreach ($resultTrainingPaid as $keyPaid => $valuePaid) {
			# code...
			//var_dump($valuePaid);
			//die;
			$arrayResult[0][1][$valuePaid['monthly']]['totalPaid'] = $valuePaid['totalPaid'];
		}//foreach

		//free lms training
		foreach ($resultTrainingFree as $keyFree => $valueFree) {
			# code...
			//var_dump($valuePaid);
			//die;
			$arrayResult[1][1][$valueFree['monthly']]['totalFree'] = $valueFree['totalFree'];
		}//foreach		

		//intel TTT
		// foreach ($resultTrainingFree as $keyFree => $valueFree) {
		// 	# code...
		// 	//var_dump($valuePaid);
		// 	//die;
		// 	$arrayResult[2][1][$valueFree['monthly']]['totalFree'] = $valueFree['totalFree'];
		// }//foreach		



		// var_dump($arrayResult);
		// die;
		//pdf
		$pdf = new FPDF();
		$pdf->AddPage('L', 'A4');
		$pdf->SetFont('Arial','B',10);

		$w = array(90, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15);

		$pdf->Cell(40,10,'OPERATIONS UPDATE (LMS TRAINING) ON '. $year);	
	    $pdf->Ln();

	    $header = model::load("helper")->monthYear("monthE");

	    foreach ($header as $key => $value) {
	    	# code...
	    	$dateObj   = DateTime::createFromFormat('!m', $key);
			$monthName = $dateObj->format('M'); // March

			$header[$key] = $monthName;
	    }

	    //var_dump($header);
	    //die;

	    for($i=0;$i<count($header)+1;$i++){
	    	$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	    }
	        $pdf->Ln();	
	        
	    $counterArray = 0;
	    foreach ($arrayResult as $keyResult => $valueResult) {
	    	# code...
	    	// var_dump($valueResult);
	    	// die;
	    	$counter = 1;

	    	$pdf->Cell($w[0],7,$valueResult[0]['typeTraining'],1,0,'C');
	    	$monthly = $valueResult[1];
	    	//var_dump($monthly);

	    	foreach ($monthly as $keyMonthly => $valueMonthly) {
	    		# code...
	    		//var_dump($valueMonthly);
	    		if($counterArray == 0)
	    			$pdf->Cell($w[$counter],7,$valueMonthly['totalPaid'],1,0,'C');	    		
	    		else if($counterArray == 1)
	    			$pdf->Cell($w[$counter],7,$valueMonthly['totalFree'],1,0,'C');	    		
	    		else if($counterArray == 2)
	    			$pdf->Cell($w[$counter],7,$valueMonthly['totalIntel'],1,0,'C');
	    		//die;
	    		$counter++;
	    	}

	    	$pdf->Ln();
	    	$counterArray++;	
	    }
	    $pdf->Output();		
	}

	private function reportEntrepreneurship($input){
		//input
		$year = $input['year'];

		db::select("clusterID, clusterName");
		db::from("cluster");
		$cluster = db::get()->result("clusterID");
		//var_dump($cluster);
		//die;
		$arrayResult = array();
		$arrayResultVid = array();

		//$arrayResult ['clusterGroup'] = '';
		//$arrayResult['clusterGroup']['clusterMonth']['totalEntre'] = 0;
		//ENTREPRENEURSHIP SQL
		db::select("c.clusterID, c.clusterName, month(u.userCreatedDate) AS monthly, count(upa.userID) AS totalEntre");
		db::from("cluster c");
		db::join("cluster_site cs", "c.clusterID = cs.clusterID");
		db::join("site s", "cs.siteID = s.siteID");
		db::join("site_member sm", "s.siteid=sm.siteid");
		db::join("user u", "u.userID=sm.userID");
		db::join("user_profile_additional upa", "u.userID=upa.userID ");
		db::where("upa.userProfileOccupationGroup", 3);
		db::where("YEAR(u.userCreatedDate)", $year);
		db::group_by("c.clusterID");
		db::group_by("month(u.userCreatedDate)");
		db::order_by("c.clusterID");
		db::order_by("month(u.userCreatedDate)");

		$resultEntre = db::get()->result();
		//var_dump($resultEntre);
		//die;

		db::select("c.clusterID, c.clusterName, month(v.videoCreatedDate) AS monthly, count(v.videoID) AS totalVid");
		db::from("cluster c");
		db::join("cluster_site cs", "c.clusterID = cs.clusterID");
		db::join("site s", "cs.siteID = s.siteID");
		db::join("video_album va", "s.siteID = va.siteID");
		db::join("video v", "v.videoAlbumID = va.videoAlbumID");
		db::where("v.videoName LIKE '%usaha%'");
		db::where("YEAR(v.videoCreatedDate)", $year);
		db::group_by("c.clusterID");
		db::group_by("month(v.videoCreatedDate)");
		db::order_by("c.clusterID");
		db::order_by("month(v.videoCreatedDate)");		

		$resultVid = db::get()->result();

		//var_dump($resultVid);
		//die;


		//make skeleton of array
		for($keyEntre = 1; $keyEntre <= 6; $keyEntre++){
			$arrayResult[$keyEntre][0]['clusterName'] 		= $cluster[$keyEntre]['clusterName'];
			$arrayResultVid[$keyEntre][0]['clusterName'] 	= $cluster[$keyEntre]['clusterName'];
			for($x = 1; $x <= 12; $x++){
				$arrayResult[$keyEntre][1][$x]['totalEntre'] = "";
				$arrayResultVid[$keyEntre][1][$x]['totalVid'] = "";
			}
		}

		//var_dump($arrayResult);
		//die;

		foreach ($resultEntre as $keyEntre => $valueEntre) {
			# code...
			//var_dump($valueEntre);
			//die;
			switch ($valueEntre['clusterID']) {
				case '1': //sabah cluster A
					# code...
					$arrayResult[1][1][$valueEntre['monthly']]['totalEntre'] = $valueEntre['totalEntre'];
					break;
				
				case '2': //sabah cluster B
					# code...
					$arrayResult[2][1][$valueEntre['monthly']]['totalEntre'] = $valueEntre['totalEntre'];
					break;

				case '3': //sabah cluster C
					# code...					
					$arrayResult[3][1][$valueEntre['monthly']]['totalEntre'] = $valueEntre['totalEntre'];
					break;

				case '4': //sarawak
					# code...
					$arrayResult[4][1][$valueEntre['monthly']]['totalEntre'] = $valueEntre['totalEntre'];
					break;

				case '5': //semenanjung
					# code...
					$arrayResult[5][1][$valueEntre['monthly']]['totalEntre'] = $valueEntre['totalEntre'];
					break;

				case '6': //semenanjung N
					# code...
					$arrayResult[6][1][$valueEntre['monthly']]['totalEntre'] = $valueEntre['totalEntre'];
					break;															
				default:
					# code...
					break;
			}//switch
		}//foreach

		//var_dump($arrayResult[1][1]);
		//die;
		foreach ($resultVid as $keyVid => $valueVid) {
			# code...
			//var_dump($valueVid);
			//die;
			switch ($valueVid['clusterID']) {
				case '1': //sabah cluster A
					# code...
					$arrayResultVid[1][1][$valueVid['monthly']]['totalVid'] = $valueVid['totalVid'];
					break;
				
				case '2': //sabah cluster B
					# code...
					$arrayResultVid[2][1][$valueVid['monthly']]['totalVid'] = $valueVid['totalVid'];
					break;

				case '3': //sabah cluster C
					# code...					
					$arrayResultVid[3][1][$valueVid['monthly']]['totalVid'] = $valueVid['totalVid'];
					break;

				case '4': //sarawak
					# code...
					$arrayResultVid[4][1][$valueVid['monthly']]['totalVid'] = $valueVid['totalVid'];
					break;

				case '5': //semenanjung
					# code...
					$arrayResultVid[5][1][$valueVid['monthly']]['totalVid'] = $valueVid['totalVid'];
					break;

				case '6': //semenanjung N
					# code...
					$arrayResultVid[6][1][$valueVid['monthly']]['totalVid'] = $valueVid['totalVid'];
					break;															
				default:
					# code...
					break;
			}//switch
		}//foreach

		// var_dump($arrayResultVid[2][1]);
		// die;
		//pdf
		$pdf = new FPDF();
		$pdf->AddPage('L', 'A4');
		$pdf->SetFont('Arial','B',10);

		$w = array(90, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15);

		$pdf->Cell(40,10,'OPERATIONS UPDATE (ENTREPRENEURSHIP) ON '. $year);	
	    $pdf->Ln();

	    $header = model::load("helper")->monthYear("monthE");

	    foreach ($header as $key => $value) {
	    	# code...
	    	$dateObj   = DateTime::createFromFormat('!m', $key);
			$monthName = $dateObj->format('M'); // March

			$header[$key] = $monthName;
	    }

	    //var_dump($header);
	    //die;

	    for($i=0;$i<count($header)+1;$i++){
	    	$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	    }
	        $pdf->Ln();

	    $pdf->Cell($w[0],7,'TOTAL PROFILED',1,0,'C');

	    for($i=1;$i<count($header)+1;$i++){
	    	$totalEmptyWidth += $w[$i];
	    	//$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	    }
	    $pdf->Cell($totalEmptyWidth,7,'',1,0,'C');
	    $pdf->Ln();

	    foreach ($arrayResult as $key => $value) {
	    	# code...
	    	//var_dump($value);
	    	//die;
	    	$pdf->Cell($w[0],7,$value[0]['clusterName'],1,0,'C');
	    	$counter = 1;
	    	foreach ($value[1] as $keyIn => $valueIn) {

	    		# code...
	    		//var_dump($valueIn);
	    		//die;
	    		$pdf->Cell($w[$counter],7,$valueIn['totalEntre'],1,0,'C');
	    		$counter++;
	    	}
	    	$pdf->Ln();
	    }		    

	    $totalEmptyWidth = 0;
	    $pdf->Cell($w[0],7,'VIDEO DONE',1,0,'C');
	    for($i=1;$i<count($header)+1;$i++){
	    	$totalEmptyWidth += $w[$i];
	    	//$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	    }
	    $pdf->Cell($totalEmptyWidth,7,'',1,0,'C');
	    $pdf->Ln();	    

	    foreach ($arrayResultVid as $key => $value) {
	    	# code...
	    	//var_dump($value);
	    	//die;
	    	$pdf->Cell($w[0],7,$value[0]['clusterName'],1,0,'C');
	    	$counter = 1;
	    	foreach ($value[1] as $keyIn => $valueIn) {

	    		# code...
	    		//var_dump($valueIn);
	    		//die;
	    		$pdf->Cell($w[$counter],7,$valueIn['totalVid'],1,0,'C');
	    		$counter++;
	    	}
	    	$pdf->Ln();
	    }
	    
	    // foreach ($arrayResults as $Eventkey => $Eventvalue) {
	    // 	# code...
	    // 	$i=0;
	    // 	//var_dump($Eventkey);
	    // 	$pdf->Cell($w[$i],7,$Eventkey,1,0,'L');
	    // 	foreach ($Eventvalue as $Monthkey => $Monthvalue) {
	    // 		# code...
	    // 		//var_dump($Monthvalue);
	    // 		//die;
	    // 		count($Monthvalue) > 0 ? $Monthvalue = $Monthvalue : $Monthvalue = ' ';
	    // 		$pdf->Cell($w[$i+1],7,$Monthvalue,1,0,'C');
	    // 	}
	    // 	$pdf->Ln();
	    // 	$i++;

	    // }

	    $pdf->Output();		
	}

	private function reportDashboardEvent($input){
		$year = $input['year'];

		db::select("COUNT(A.activityID) AS totalActivity, E.eventType, MONTH(A.activityStartDate) as month");
		db::from("activity A");
		db::join("event E", "E.activityID = A.activityID");
		db::where("YEAR(A.activityStartDate)", $year);
		db::where("A.activityType", 1);
		db::where("A.activityApprovalStatus", 1);
		//db::where("E.eventType IN ", "(2,3,7)");
		db::group_by("E.eventType, month");
		$results = db::get()->result();

		//var_dump($results);
		//die;

		$event_types = model::load("activity/event")->type();

		$arrayResults = array();

		foreach ($event_types as $event_typeKey => $event_typeValue) {
			# code...
			//var_dump($event_type);
			//die;
			$arrayResults[$event_typeKey] = array();
			for($x =1; $x <= 12; $x++){
				$arrayResults[$event_typeKey][$x] = array();
			}
			
		}

		foreach ($results as $result) {
			# code...
			$arrayResults[$result['eventType']][$result['month']] = $result['totalActivity'];
		}

		$arrayResults = array_combine($event_types, array_values($arrayResults));
		//var_dump($arrayResults);
		//die;


		$pdf = new FPDF();
		$pdf->AddPage('L', 'A4');
		$pdf->SetFont('Arial','B',10);

		$w = array(90, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15);

		$pdf->Cell(40,10,'OPERATIONS UPDATE (EVENT) ON '. $year);	
	    $pdf->Ln();

	    $header = model::load("helper")->monthYear("monthE");

	    foreach ($header as $key => $value) {
	    	# code...
	    	$dateObj   = DateTime::createFromFormat('!m', $key);
			$monthName = $dateObj->format('M'); // March

			$header[$key] = $monthName;
	    }

	    //var_dump($header);
	    //die;

	    for($i=0;$i<count($header)+1;$i++){
	    	$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	    }
	        $pdf->Ln();


	    
	    foreach ($arrayResults as $Eventkey => $Eventvalue) {
	    	# code...
	    	$i=0;
	    	//var_dump($Eventkey);
	    	$pdf->Cell($w[$i],7,$Eventkey,1,0,'L');
	    	foreach ($Eventvalue as $Monthkey => $Monthvalue) {
	    		# code...
	    		//var_dump($Monthvalue);
	    		//die;
	    		count($Monthvalue) > 0 ? $Monthvalue = $Monthvalue : $Monthvalue = ' ';
	    		$pdf->Cell($w[$i+1],7,$Monthvalue,1,0,'C');
	    	}
	    	$pdf->Ln();
	    	$i++;

	    }

	    $pdf->Output();
	}

	private function reportDashboardTraining ($input){

		$year = $input['year'];
		//$year = 2015;
		$quarter = $input['quarter']+1;
		//$quarter = 4;
		//var_dump($year);
		$list_quarters = array();

		
		db::select("clusterID, clusterName");
		db::from("cluster");
		$listclusters = db::get()->result();

		$arrayResult = array();

		foreach ($listclusters as $cluster) {
			# code...
			$arrayResult[$cluster['clusterName']] = array();

			for($x = 1; $x <= 12; $x++ ){
				$arrayResult[$cluster['clusterName']][$x] = array();
			}
		}



		//var_dump($arrayResult);
		//die;
			db::select("C.clusterID, clusterName, MONTH(AD.activityDateValue) AS month, SUM(HOUR(TIMEDIFF(AD.activityDateEndTime, AD.activityDateStartTime))) as totalhours, COUNT(DISTINCT S.siteID) as totalsites");
			db::from("activity A");
			db::join("site S", "S.siteID = A.siteID");
			db::join("cluster_site CS", "CS.siteID = S.siteID");
			db::join("cluster C", "C.clusterID = CS.clusterID");
			db::join("activity_date AD", "AD.activityID = A.activityID");
			db::where("A.activityType", 2);
			db::where("YEAR(AD.activityDateValue) =", $year);
			db::where("A.activityApprovalStatus", 1);
			// db::where("AD.activityDateValue <=", "2015-12-31");
			//db::group_by("month");			
			db::group_by("C.clusterID, month");

			db::order_by("C.clusterID, month");
			$results = db::get()->result();

			//var_dump($results);
			//die;

			foreach ($results as $result) {
				$arrayResult[$result['clusterName']][$result['month']][0] = $result['totalhours'];
				$arrayResult[$result['clusterName']][$result['month']][1] = $result['totalsites'];
				$arrayResult[$result['clusterName']][$result['month']][2] = $result['totalsites'] * 16; //mcmc kpi
				$arrayResult[$result['clusterName']][$result['month']][3] = $result['totalsites'] * 48; //ntsb kpi


			}			

				$totalHoursQuarter1stAllCluster = 0;
				$totalHoursQuarter2ndAllCluster = 0;
				$totalHoursQuarter3rdAllCluster = 0;
				$totalHoursQuarter4thAllCluster = 0;

				$totalKPIMCMC1stQuarter			= 0;
				$totalKPIMCMC2ndQuarter 		= 0;
				$totalKPIMCMC3rdQuarter 		= 0;
				$totalKPIMCMC4thQuarter 		= 0;

				$totalKPINTSB1stQuarter 		= 0;
				$totalKPINTSB2ndQuarter 		= 0;
				$totalKPINTSB3rdQuarter 		= 0;
				$totalKPINTSB4thQuarter 		= 0;								

			foreach ($arrayResult as $clusterKey => $clusterValue) {
				$q1 = 0;
				$q2 = 0;
				$q3 = 0;
				$q4 = 0;	

				$q1mcmc = 0;			
				$q2mcmc = 0;			
				$q3mcmc = 0;			
				$q4mcmc = 0;

				$q1ntsb = 0;			
				$q2ntsb = 0;			
				$q3ntsb = 0;			
				$q4ntsb = 0;			
				# code...
				//var_dump($clusterKey);
				//die;
				foreach ($clusterValue as $key => $value) {
					# code...
					//var_dump($key);
					//var_dump($value[0]);
					//die;

					// db::select("count(CS.siteID) as ops_pi1m");
					// db::from("cluster_site CS");
					// db::join("site S", "S.siteID = CS.siteID");
					// db::join("cluster C", "C.clusterID = CS.clusterID");
					// db::where("MONTH(S.siteCreatedDate) <=", $key);
					// db::where("YEAR(S.siteCreatedDate) <=", $year);
					// db::where("C.clusterName", $clusterKey);

					// $result_ops_pi1m = db::get()->result();
					//  //var_dump($result_ops_pi1m);
					// // die;

					// foreach ($result_ops_pi1m as $value) {
					// # code...
					// 	$arrayResult[$clusterKey][$key][2] = $value['ops_pi1m'];
					// }

					if($key == 1 || $key == 2 || $key == 3){
						//$q1 = $value = null ? $value = 0 : $value = $value;
						//var_dump($value[0]);
						$q1 		+= $value[0] == null ? $value[0] = 0 : $value[0] = $value[0];
						$q1mcmc 	+= $value[2] == null ? $value[2] = 0 : $value[2] = $value[2];
						$q1ntsb 	+= $value[3] == null ? $value[3] = 0 : $value[3] = $value[3];
					}	
					else if ($key == 4 || $key == 5 || $key == 6){
						//$q1 = $value = null ? $value = 0 : $value = $value;
						//var_dump($value[0]);
						$q2 		+= $value[0] == null ? $value[0] = 0 : $value[0] = $value[0];
						$q2mcmc 	+= $value[2] == null ? $value[2] = 0 : $value[2] = $value[2];
						$q2ntsb 	+= $value[3] == null ? $value[3] = 0 : $value[3] = $value[3];
					}					
					else if ($key == 7 || $key == 8 || $key == 9){
						//$q1 = $value = null ? $value = 0 : $value = $value;
						//var_dump($value[0]);
						$q3 		+= $value[0] == null ? $value[0] = 0 : $value[0] = $value[0];
						$q3mcmc 	+= $value[2] == null ? $value[2] = 0 : $value[2] = $value[2];
						$q3ntsb 	+= $value[3] == null ? $value[3] = 0 : $value[3] = $value[3];
					}					
					else if ($key == 10 || $key == 11 || $key == 12){
						//$q1 = $value = null ? $value = 0 : $value = $value;
						//var_dump($value[0]);
						$q4 		+= $value[0] == null ? $value[0] = 0 : $value[0] = $value[0];
						$q4mcmc 	+= $value[2] == null ? $value[2] = 0 : $value[2] = $value[2];
						$q4ntsb 	+= $value[3] == null ? $value[3] = 0 : $value[3] = $value[3];
					}			
					//die;
				}//foreach clusterValue
				$arrayResult[$clusterKey]['MCMC1'] = $q1mcmc;
				$arrayResult[$clusterKey]['MCMC2'] = $q2mcmc;
				$arrayResult[$clusterKey]['MCMC3'] = $q3mcmc;
				$arrayResult[$clusterKey]['MCMC4'] = $q4mcmc;

				$arrayResult[$clusterKey]['NTSB1'] = $q1ntsb;
				$arrayResult[$clusterKey]['NTSB2'] = $q2ntsb;
				$arrayResult[$clusterKey]['NTSB3'] = $q3ntsb;
				$arrayResult[$clusterKey]['NTSB4'] = $q4ntsb;

				$arrayResult[$clusterKey]['Quarter1'] = $q1;
				$arrayResult[$clusterKey]['Quarter2'] = $q2;
				$arrayResult[$clusterKey]['Quarter3'] = $q3;
				$arrayResult[$clusterKey]['Quarter4'] = $q4;

				$totalHoursQuarter1stAllCluster += $q1;
				$totalHoursQuarter2ndAllCluster += $q2;
				$totalHoursQuarter3rdAllCluster += $q3;
				$totalHoursQuarter4thAllCluster += $q4;		

				$totalKPIMCMC1stQuarter += $q1mcmc;
				$totalKPIMCMC2ndQuarter += $q2mcmc;
				$totalKPIMCMC3rdQuarter += $q3mcmc;
				$totalKPIMCMC4thQuarter += $q4mcmc;

				$totalKPINTSB1stQuarter += $q1ntsb;
				$totalKPINTSB2ndQuarter += $q2ntsb;
				$totalKPINTSB3rdQuarter += $q3ntsb;
				$totalKPINTSB4thQuarter += $q4ntsb;

				//die;
			}//foreach arrayResult

			//var_dump($arrayResult);
			//die;

		//var_dump($list_quarters);
		//die;
		$pdf = new FPDF();
		$pdf->AddPage('L', 'A4');
		$pdf->SetFont('Arial','B',10);

		$w = array(60, 20, 20, 20, 30, 30, 30, 50);
	    // Header
		$header = array();

	    $headerQ1 = array(
	    		0 => "CLUSTER",
	    		1 => "JAN",
	    		2 => "FEB",
	    		3 => "MAR",
	    		4 => "TOTAL HOURS",
	    		5 => "KPI(MCMC)",
	    		6 => "KPI(NTSB)",
	    		7 => "# OF OPS PI1M",
	    	);

	    $headerQ2 = array(
	    		0 => "CLUSTER",
	    		1 => "APR",
	    		2 => "MAY",
	    		3 => "JUN",
	    		4 => "TOTAL HOURS",
	    		5 => "KPI(MCMC)",
	    		6 => "KPI(NTSB)",
	    		7 => "# OF OPS PI1M",
	    	);

	    $headerQ3 = array(
	    		0 => "CLUSTER",
	    		1 => "JULY",
	    		2 => "AUG",
	    		3 => "SEP",
	    		4 => "TOTAL HOURS",
	    		5 => "KPI(MCMC)",
	    		6 => "KPI(NTSB)",
	    		7 => "# OF OPS PI1M",
	    	);

	    $headerQ4 = array(
	    		0 => "CLUSTER",
	    		1 => "OCT",
	    		2 => "NOV",
	    		3 => "DEC",
	    		4 => "TOTAL HOURS",
	    		5 => "KPI(MCMC)",
	    		6 => "KPI(NTSB)",
	    		7 => "# OF OPS PI1M",
	    	);	    

	    if($quarter == 1){
	    	$header = $headerQ1;
	    	$sumQuarterHours = $totalHoursQuarter1stAllCluster;
	    	$sumKPIMCMC	 	 = $totalKPIMCMC1stQuarter;
	    	$sumKPINTSB	 	 = $totalKPINTSB1stQuarter;
	    }
	    	
	    else if($quarter == 2){
	    	$header = $headerQ2;
	    	$sumQuarterHours = $totalHoursQuarter2ndAllCluster;
			$sumKPIMCMC	 	 = $totalKPIMCMC2ndQuarter;
			$sumKPINTSB	 	 = $totalKPINTSB2ndQuarter;	    	

	    }
	    		    
	    else if($quarter == 3){
	    	$header = $headerQ3;
	    	$sumQuarterHours = $totalHoursQuarter3rdAllCluster;
			$sumKPIMCMC	 	 = $totalKPIMCMC3rdQuarter;
			$sumKPINTSB	 	 = $totalKPINTSB3rdQuarter;	    	
	    }
	    	
	    else if($quarter == 4){
	    	$header = $headerQ4;
	    	$sumQuarterHours = $totalHoursQuarter4thAllCluster;
			$sumKPIMCMC	 	 = $totalKPIMCMC4thQuarter;
			$sumKPINTSB	 	 = $totalKPINTSB4thQuarter;	    	
	    }
	    	   

	    $pdf->Cell(40,10,'OPERATIONS UPDATE (TRAINING) ON '. $year);	
	    $pdf->Ln();


	    for($i=0;$i<count($header);$i++)
	        $pdf->Cell($w[$i],7,$header[$i],1,0,'C');

	    $pdf->Ln();

	    foreach ($arrayResult as $clusterkey => $clustervalue) {
	    	# code...
	    	//var_dump($clusterkey);
	    	//die;
	    	$i =0;
	    	$pdf->Cell($w[$i],7,$clusterkey,1,0,'L');
	    	$stringtemp = '';
	    	$i=1;
	    	foreach ($clustervalue as $key => $value) {
	    		
	    		# code...

					if (($key <= $quarter * 3) && ($key >= ($quarter*3)-2)) {
						$pdf->Cell($w[$i],7,$value[0],1,0,'C');
						$dateObj   = DateTime::createFromFormat('!m', $key);
						$monthName = $dateObj->format('M'); // March
						//$value[1] ? $value[1] = $value[1] : $value[1] = 0 ;
						if($value[1])
							$stringtemp .= $monthName . ": " .$value[1]. ", ";
						$i++;
					}
				
					
	    	}
	    	$pdf->Cell($w[$i],7,$clustervalue['Quarter'. $quarter],1,0,'C');
	    	$pdf->Cell($w[$i],7,$clustervalue['MCMC'. $quarter],1,0,'C');
	    	$pdf->Cell($w[$i],7,$clustervalue['NTSB'. $quarter],1,0,'C');

	    	//MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])


	    	$pdf->Cell($w[7],7,$stringtemp,1,0,'L');

    	

	    	
	    	$pdf->Ln();
	    	$i++;
	    	 //$pdf->Cell($w[$i],7,$headerQ1[$i],1,0,'C');
	    }//foreach arrayresult

	    $pdf->Cell($w[0]+$w[1]+$w[2]+$w[3],7,'',1,0,'C');
	    $pdf->Cell($w[4],7,$sumQuarterHours,1,0,'C');
	    $pdf->Cell($w[5],7,$sumKPIMCMC,1,0,'C');
	    $pdf->Cell($w[6],7,$sumKPINTSB,1,0,'C');
	    $pdf->Cell($w[7],7,'',1,0,'C');

	    $pdf->Ln();

	    if($sumKPIMCMC != 0)
	    	$percentKPIMCMC = round($sumQuarterHours / $sumKPIMCMC * 100);
	    else
	    	$percentKPIMCMC = 0;

	    if($sumKPINTSB != 0)
	    	$percentKPINTSB = round($sumQuarterHours / $sumKPINTSB * 100);
	    else
	    	$percentKPINTSB = 0;

		$pdf->Cell($w[0]+$w[1]+$w[2]+$w[3],7,'',1,0,'C');
		$pdf->Cell($w[4],7,'',1,0,'C');
	    $pdf->Cell($w[5],7,$percentKPIMCMC. '%',1,0,'C');
	    $pdf->Cell($w[6],7,$percentKPINTSB. '%',1,0,'C');
	    $pdf->Cell($w[7],7,'',1,0,'C');	    

		$pdf->Output();

		//view::render('root/report/dashboardReportGenerator');		
	}

	// USP MICROSITES VITAL STATISTICS
	private function getREgister($date) {
		// GET Registered
		db::select('COUNT(A.userID) AS registered, COUNT(C.siteID) AS site, C.clusterID AS clusterID');
		db::from('user A');
		db::join('site_member B', 'B.userID = A.userID');
		db::join('cluster_site C', 'C.siteID = B.siteID');
		db::where('A.userCreatedDate LIKE','%'.$date.'%');
		db::group_by('C.clusterID');
		$regsitered = db::get()->result();

		// select COUNT(A.userID) AS registered, COUNT(C.siteID) AS site, C.clusterID AS cluster
		// from user A
		// join site_member B on B.userID = A.userID
		// right join cluster_site C on C.siteID = B.siteID
		// WHERE A.userCreatedDate LIKE '%2015%'
		// group by C.clusterID

		//Set array for total registered cell
		$totalRegistered = array();

		//Set default value to 0
		$reSabah = 0;
		$reSarawak = 0;
		$reSemenanjung = 0;

		//Get total value for each State
		foreach ($regsitered as $key => $value) {
			if ($value['clusterID'] >= 1 && $value['clusterID'] <= 3) {
		 		$reSabah += $value['registered'];
		 	}
			if ($value['clusterID'] == 4) {
		 		$reSarawak += $value['registered'];

		 	}
			if ($value['clusterID'] > 4) {
		 		$reSemenanjung += $value['registered'];
		 	}
		}

		//Push to content registered cell array
		array_push($totalRegistered, 'Total Registered User', $reSemenanjung, $reSabah, $reSarawak);

		return $totalRegistered;
	}

	private function getlogIn($date) {
		// GET Login
		db::select('COUNT(A.userID) AS totalLogin, COUNT(B.userID) AS User, COUNT(D.siteID) AS Site, D.clusterID AS clusterID');
		db::from('log_login A');
		db::join('user B', 'B.userID = A.userID');
		db::join('site_member C', 'C.userID = B.userID');
		db::join('cluster_site D', 'D.siteID = C.siteID');
		db::where('A.logLoginCreatedDate LIKE','%'.$date.'%');
		db::group_by('D.clusterID');
		$uLogin = db::get()->result();

		// SELECT COUNT(A.userID) AS totalLogin, COUNT(B.userID) AS User, COUNT(D.siteID) AS Site, D.clusterID AS Cluster
		// FROM log_login A
		// RIGHT JOIN user B ON B.userID = A.userID
		// LEFT JOIN site_member C ON C.userID = B.userID
		// RIGHT JOIN cluster_site D ON D.siteID = C.siteID
		// WHERE A.logLoginCreatedDate LIKE '%2016%'
		// GROUP BY D.clusterID

		//Set array for total login cell
		$totalLogin	= array();

		//Set default value to 0
		$loSabah = 0;
		$loSarawak = 0;
		$loSemenanjung = 0;

		//Get total value login
		foreach ($uLogin as $key => $value) {
			if ($value['clusterID'] >= 1 && $value['clusterID'] <= 3) {
				//array_push($sumSabah, $value['totalLogin']);
				$loSabah += $value['totalLogin'];
		 	}
			if ($value['clusterID'] == 4) {
				$loSarawak += $value['totalLogin'];

		 	}
			if ($value['clusterID'] > 4) {
				$loSemenanjung += $value['totalLogin'];
		 	}
		}

		//Push to login cell array
		array_push($totalLogin, 'Total User Logins', $loSemenanjung, $loSabah, $loSarawak);

		return $totalLogin;
	}

	private function getContentUpdate($date) {
		// GET Content Update
		db::select('COUNT(C.siteID) AS Request, A.clusterID AS ClusterId, A.clusterName AS Cluster');
		db::from('cluster A');
		db::join('cluster_site B', 'B.clusterID = A.clusterID');
		db::join('site_request C', 'C.siteID = B.siteID');
		db::where('C.siteRequestCreatedDate LIKE','%'.$date.'%');
		db::group_by('A.clusterID');
		$contentUpdate = db::get()->result();

		//Set array for content update cell
		$totalUpdate = array();

		//Set default value to 0
		$upSabah = 0;
		$upSarawak = 0;
		$upSemenanjung = 0;

		//Get total value for each State
		foreach ($contentUpdate as $key => $value) {
			if ($value['ClusterId'] >= 1 && $value['ClusterId'] <= 3) {
		 		$upSabah += $value['Request'];
		 	}
			if ($value['ClusterId'] == 4) {
		 		$upSarawak += $value['Request'];
		 	}
			if ($value['ClusterId'] > 4) {
		 		$upSemenanjung += $value['Request'];
		 	}
		}

		//Push to content update cell array
		array_push($totalUpdate, 'Total Content Updated', $upSemenanjung, $upSabah, $upSarawak);

		return $totalUpdate;
	}

	private function getEntrepreneurs($date) {
		//Get Entrepreneurs
		db::select('COUNT(A.userID) AS Entrepreneurs, COUNT(C.siteID) AS site, E.clusterID AS clusterID, E.clusterName AS Cluster');
		db::from('user A');
		db::join('user_profile_additional B', 'B.userID = A.userID');
		db::join('site_member C', 'C.userID = B.userID');
		db::join('cluster_site D', 'D.siteID = C.siteID');
		db::join('cluster E', 'E.clusterID = D.clusterID');
		db::where('A.userLevel', '1');
		db::where('B.userProfileOccupationGroup', '3');
		db::where('A.userCreatedDate LIKE','%'.$date.'%');
		db::group_by('E.clusterID');
		$Entrepreneurs = db::get()->result();

		//Set array for entreprenerus cell
		$totalEntrepre = array();

		//Set default value to 0
		$enSabah = 0;
		$enSarawak = 0;
		$enSemenanjung = 0;

		//Get total value for each State
		foreach ($Entrepreneurs as $key => $value) {
			if ($value['clusterID'] >= 1 && $value['clusterID'] <= 3) {
		 		$enSabah += $value['Entrepreneurs'];
		 	}
			if ($value['clusterID'] == 4) {
		 		$enSarawak += $value['Entrepreneurs'];
		 	}
			if ($value['clusterID'] > 4) {
		 		$enSemenanjung += $value['Entrepreneurs'];
		 	}
		}

		//Push to entreprenerus cell array
		array_push($totalEntrepre, 'Total Entrepreneurs Profiled', $enSemenanjung, $enSabah, $enSarawak);

		return $totalEntrepre;
	}

	private function reportDashboardUSPMircosites($input) {

		$month 	= $input['month'];
		$year 	= $input['year'];
		$endTitle = '';

		// Convert month value to month name
		$month_text = model::load("helper")->monthYear('monthE');

		foreach ($month_text as $key => $value) {
			if ($key == $month) {
				$month_text =  $value;
			}
		}

		// Get month value length
		$Nmonth = strlen($month);

		// Set month value to 2 digit eg: 01,02,03,04,05,06,07,08,09
		if ($Nmonth == 1) {
			$months = '0'.$month;
		} else {
			$months = $month;
		}

		// Set date search format & reset month text
		if ($month == '') {
			$date = $year;
			$month_text = '';
		} else {
			$date = $year.'-'.$months;
		}

		// Set end title if select year
		if ($year != '') {
			$endTitle = 'ON '.$month_text.' '.$year;
		}

		// Get Register & Login
		$totalregister 	= $this->getREgister($date);

		// Get Login
		$totaLogin 		= $this->getlogIn($date);

		// Get Content Update
		$contentUpdate 	= $this->getContentUpdate($date);

		// Get Entrepreneurs Profiled
		$Entrepreneurs 	= $this->getEntrepreneurs($date);

		// var_dump($totalregister);
		// die();

		// Start convert to PDF
		$pdf = new FPDF();
		$pdf->AddPage('L', 'A4');
		$pdf->SetFont('Arial','B',10);

		// Cell width
		$w = array(60, 30, 30, 30);

	    $pdf->Cell(40,10,'OPERATIONS UPDATE (USP MICROSITES VITAL STATISTICS) '.$endTitle);
	    $pdf->Ln();

	    // Header
		$header = array('','Semenanjung','Sabah','Sarawak');
	    // Alignment
		$hAlign = array('L','C','C','C');

	    for ($i=0;$i<count($header);$i++) {
	        $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
	    }

	    $pdf->Ln();

	    // Total Registered User
	    for ($i=0;$i<count($totalregister);$i++) {
	        $pdf->Cell($w[$i],7,$totalregister[$i],1,0,$hAlign[$i]);
	    }

	    $pdf->Ln();

	    // Total User Logins
	    for ($i=0;$i<count($totaLogin);$i++) {
	        $pdf->Cell($w[$i],7,$totaLogin[$i],1,0,$hAlign[$i]);
	    }

	    $pdf->Ln();

	    // Total Content Updated
	    for ($i=0;$i<count($contentUpdate);$i++) {
	        $pdf->Cell($w[$i],7,$contentUpdate[$i],1,0,$hAlign[$i]);
	    }

	    $pdf->Ln();

	    // Total Entrepreneurs Profiled
	    for ($i=0;$i<count($Entrepreneurs);$i++) {
	        $pdf->Cell($w[$i],7,$Entrepreneurs[$i],1,0,$hAlign[$i]);
	    }

		$pdf->Output();

	}

	// USP STAFF STRENGTH
	private function getStaffStrength() {
		// Get TotalManager
		db::select('COUNT(B.userID) AS Manager, COUNT(DISTINCT(A.siteID)) AS Site');
		db::from('site A');
		db::join('site_manager B','B.siteID = A.siteID','RIGHT JOIN');
		$ResultManager = db::get()->result();

		// Set array for entreprenerus cell
		$totalManager = array();

		// Get totalManager data
		foreach ($ResultManager as $key => $value) {
			// Total Position
			$position = $value['Site']*2;
			// Get Total Vacant
			if ($value['Manager'] != $position) {
				$totalVacant = $position-$value['Manager'];
			} else {
				$totalVacant = '0';
			}
			// Get Percentage
			$filled = floatval(number_format((100.0*$value['Manager'])/$position, 2));
			// Push to entreprenerus cell array
			array_push($totalManager,'Total',$value['Site'],$position,$value['Manager'],$totalVacant,$filled.'%');
		}

		return $totalManager;
	}

	private function allBatch() {
		// Get TotalManager
		db::select("SUM(CASE WHEN B.clusterID IN ('5','6') THEN 1 ELSE 0 END) Semenanjung, SUM(CASE WHEN B.clusterID IN ('1','2','3') THEN 1 ELSE 0 END) Sabah, SUM(CASE WHEN B.clusterID IN ('4') THEN 1 ELSE 0 END) Sarawak, COUNT(A.`userID`) AS totalManager, COUNT(DISTINCT(B.`siteID`))*2 AS Positions, COUNT(DISTINCT(B.`siteID`)) AS totalSite, C.`siteInfoPhase` AS BatchID, E.`batchName` AS Batch");
		db::from('site_manager A');
		db::join('cluster_site B', 'B.siteID = A.siteID');
		db::join('site_info C', 'C.siteID = B.siteID', 'RIGHT JOIN');
		db::join('cluster D', 'D.clusterID = B.clusterID', 'RIGHT JOIN');
		db::join('batch E', 'E.batchID = C.siteInfoPhase', 'RIGHT JOIN');
		db::group_by('C.siteInfoPhase');
		$ResultBatch = db::get()->result();

		return $ResultBatch;
	}

	private function reportUSPStaffStrength() {

		// Get Staff Strength
		$totalStaffStrength = $this->getStaffStrength();

		// Get All batch
		$totalBatch = $this->allBatch();

		// Start convert to PDF
		$pdf = new FPDF();
		$pdf->AddPage('L', 'A4');
		$pdf->SetFont('Arial','B',10);

	    $pdf->Cell(40,10,'USP STAFF STRENGTH');
	    $pdf->Ln();

	    // 1st Header
		$header = array('Staff Strength','PI1Ms','Positions','Filled','Vacant','%Filled');
		// 1st Cell width
		$w = array(60, 30, 30, 30, 30, 30);
		// 1st Cell alignment
		$hAlign = array('L', 'R', 'R', 'R', 'R', 'R');

	    for ($i=0;$i<count($header);$i++) {
	        $pdf->Cell($w[$i],7,$header[$i],1,0,$hAlign[$i]);
	    }

	    $pdf->Ln();

	    for ($i=0;$i<count($header);$i++) {
	        $pdf->Cell($w[$i],7,$totalStaffStrength[$i],1,0,$hAlign[$i]);
	    }

	    $pdf->Ln();
	    $pdf->Ln();

	    // 2nd Header
		$headers = array('Contract','# Pi1Ms in operation','Staffing');

		// 2nd Cell width
		$Nw = array(90, 80, 40); //210

	    for ($i=0;$i<count($headers);$i++) {
	        $pdf->Cell($Nw[$i],7,$header[$i],1,0,'C');
	    }

	    $pdf->Ln();

	    // 3rd Header
		$subHeaders = array('Name','End date','Semenanjung','Sabah','Sarawak','Vacant','%Filled');
		// 3rd Cell width
		$Nws = array(60, 30, 30, 30, 20, 20, 20); //210

	    for ($i=0;$i<count($subHeaders);$i++) {
	        $pdf->Cell($Nws[$i],7,$subHeaders[$i],1,0,'C');
	    }

	    $pdf->Ln();

	    // End date
		$endDate = array('2015','2016','2016','2017','2017','2017','');
		
		for ($i=0; $i < count($subHeaders); $i++) { 
    		//echo $endDate[$i].'<br/>';
        	$pdf->Cell($Nws[0],7,$totalBatch[$i]['Batch'].' ('.$totalBatch[$i]['totalSite'].' Pi1M)',1,0,'L');
        	$pdf->Cell($Nws[1],7,$endDate[$i],1,0,'C');
        	$pdf->Cell($Nws[2],7,$totalBatch[$i]['Semenanjung'],1,0,'C');
        	$pdf->Cell($Nws[3],7,$totalBatch[$i]['Sabah'],1,0,'C');
        	$pdf->Cell($Nws[4],7,$totalBatch[$i]['Sarawak'],1,0,'C');
        	$pdf->Cell($Nws[5],7,$totalBatch[$i]['Positions']-$totalBatch[$i]['totalManager'],1,0,'C');
			if ($totalBatch[$i]['Positions'] && $totalBatch[$i]['Positions'] > 0) {
        		$pdf->Cell($Nws[6],7,floatval(number_format((100.0*$totalBatch[$i]['totalManager'])/$totalBatch[$i]['Positions'], 2)).'%',1,0,'C');
        	} else {
        		$pdf->Cell($Nws[6],7,0,1,0,'C');
        	}
	    	$pdf->Ln();
        }

		$pdf->Output();

	}

	// Report Cash Flow Summary

	private function getTotalCashFlow($date) {
		# Get Cash Flow Summary
		// db::select("D.siteInfoDistrict,A.stateID,A.siteID,A.siteName,COUNT(B.siteID) AS NumberOfTransaction,SUM(CASE WHEN C.billingItemID = 1 THEN C.billingTransactionItemPrice ELSE 0 END) AS Membership,SUM(CASE WHEN C.billingItemID = 3 || C.billingItemID = 5 || C.billingItemID = 16 THEN C.billingTransactionItemPrice ELSE 0 END) AS PCUsage,SUM(CASE WHEN C.billingItemID = 4 || C.billingItemID = 6 THEN C.billingTransactionItemPrice ELSE 0 END) AS PrintPhotst,SUM(CASE WHEN C.billingItemID = 5 THEN C.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,SUM(CASE WHEN C.billingItemID = 7 THEN C.billingTransactionItemPrice ELSE 0 END) AS Scanning,SUM(CASE WHEN C.billingItemID = 8 || C.billingItemID = 17 THEN C.billingTransactionItemPrice ELSE 0 END) AS Laminating,SUM(CASE WHEN C.billingItemID = 2 || C.billingItemID = 9 || C.billingItemID = 12 || C.billingItemID = 13 || C.billingItemID = 14 THEN C.billingTransactionItemPrice ELSE 0 END) AS OthersCashIn");
		// db::from('site A');
		// db::join('billing_transaction B', 'B.siteID = A.siteID');
		// db::join('billing_transaction_item C', 'C.billingTransactionID = B.billingTransactionID');
		// db::join('site_info D', 'D.siteID = A.siteID');
		// db::where('B.billingTransactionDate LIKE','%'.$date.'%');
		// db::group_by('A.siteID');
		// db::order_by('A.StateID');
		// $ResultSummary = db::get()->result();

		# New ultimate query from Mr. Moridh
		$ResultSummary = db::query("SELECT siteName, site.siteID, stateID, Membership, PCUsage, PrintPhotst, OthersSrvc, Scanning, Laminating, OthersCashIn, Expense FROM site LEFT JOIN ( SELECT A.siteID,SUM(CASE WHEN B.billingItemID = 1 THEN B.billingTransactionItemPrice ELSE 0 END) AS Membership,SUM(CASE WHEN B.billingItemID = 3 || B.billingItemID = 5 || B.billingItemID = 16 THEN B.billingTransactionItemPrice ELSE 0 END) AS PCUsage,SUM(CASE WHEN B.billingItemID = 4 || B.billingItemID = 6 THEN B.billingTransactionItemPrice ELSE 0 END) AS PrintPhotst,SUM(CASE WHEN B.billingItemID = 5 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,SUM(CASE WHEN B.billingItemID = 7 THEN B.billingTransactionItemPrice ELSE 0 END) AS Scanning,SUM(CASE WHEN B.billingItemID = 8 || B.billingItemID = 17 THEN B.billingTransactionItemPrice ELSE 0 END) AS Laminating,SUM(CASE WHEN B.billingItemID = 2 || B.billingItemID = 9 || B.billingItemID = 12 || B.billingItemID = 13 || B.billingItemID = 14 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersCashIn,SUM(CASE WHEN B.billingItemID = 10 || B.billingItemID = 11 THEN ABS(B.billingTransactionItemPrice) ELSE 0 END) AS Expense FROM billing_transaction A LEFT JOIN billing_transaction_item B ON B.billingTransactionID = A.billingTransactionID WHERE A.billingTransactionStatus = 1 AND A.billingTransactionDate LIKE '%".$date."%' GROUP BY A.siteID) AS figures ON figures.siteID = site.siteID ORDER BY site.stateID ASC")->result();


		# new from Mr. Moridh

		// SELECT siteName, stateID, Membership, PCUsage, PrintPhotst, OthersSrvc, Scanning, Laminating, OthersCashIn, Expense
		// FROM site
		// LEFT JOIN (
		// SELECT 
		// A.`siteID`,
		// SUM(CASE WHEN B.billingItemID = 1 THEN B.billingTransactionItemPrice ELSE 0 END) AS Membership,
		// SUM(CASE WHEN B.billingItemID = 3 || B.billingItemID = 15 || B.billingItemID = 16 THEN B.billingTransactionItemPrice ELSE 0 END) AS PCUsage,
		// SUM(CASE WHEN B.billingItemID = 4 || B.billingItemID = 6 THEN B.billingTransactionItemPrice ELSE 0 END) AS PrintPhotst,
		// SUM(CASE WHEN B.billingItemID = 5 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,
		// SUM(CASE WHEN B.billingItemID = 7 THEN B.billingTransactionItemPrice ELSE 0 END) AS Scanning,
		// SUM(CASE WHEN B.billingItemID = 8 || B.billingItemID = 17 THEN B.billingTransactionItemPrice ELSE 0 END) AS Laminating,
		// SUM(CASE WHEN B.billingItemID = 2 || B.billingItemID = 9 || B.billingItemID = 12 || B.billingItemID = 13 || B.billingItemID = 14 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersCashIn,
		// SUM(CASE WHEN B.billingItemID = 10 || B.billingItemID = 11 THEN ABS(B.billingTransactionItemPrice) ELSE 0 END) AS Expense
		// FROM `billing_transaction` A
		// LEFT JOIN `billing_transaction_item` B ON B.`billingTransactionID` = A.`billingTransactionID`
  //       WHERE A.billingTransactionStatus = 1
  //   	AND A.billingTransactionDate LIKE '%2016-06%'
		// GROUP BY A.`siteID`) AS figures ON figures.siteID = site.siteID

		# New Query

		// SELECT 
		// D.`siteInfoDistrict`,
		// A.`stateID`,
		// A.`siteID`,
		// A.`siteName`,
		// COUNT(B.siteID) AS NumberOfTransaction,
		// SUM(CASE WHEN C.billingItemID = 1 THEN C.billingTransactionItemPrice ELSE 0 END) AS Membership,
		// SUM(CASE WHEN C.billingItemID = 3 || C.billingItemID = 5 || C.billingItemID = 16 THEN C.billingTransactionItemPrice ELSE 0 END) AS PCUsage,
		// SUM(CASE WHEN C.billingItemID = 4 || C.billingItemID = 6 THEN C.billingTransactionItemPrice ELSE 0 END) AS PrintPhotst,
		// SUM(CASE WHEN C.billingItemID = 5 THEN C.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,
		// SUM(CASE WHEN C.billingItemID = 7 THEN C.billingTransactionItemPrice ELSE 0 END) AS Scanning,
		// SUM(CASE WHEN C.billingItemID = 8 || C.billingItemID = 17 THEN C.billingTransactionItemPrice ELSE 0 END) AS Laminating,
		// SUM(CASE WHEN C.billingItemID = 2 || C.billingItemID = 9 || C.billingItemID = 12 || C.billingItemID = 13 || C.billingItemID = 14 THEN C.billingTransactionItemPrice ELSE 0 END) AS OthersCashIn
		// FROM `site` A
		// LEFT JOIN `billing_transaction` B ON B.`siteID` = A.`siteID`
		// LEFT JOIN `billing_transaction_item` C ON C.`billingTransactionID` = B.`billingTransactionID`
		// LEFT JOIN `site_info` D ON D.`siteID` = A.`siteID`
		// GROUP BY A.`siteID`
		// ORDER BY A.`stateID`

		# Old Query

		// SELECT 
		// D.`siteInfoDistrict`,
		// A.`stateID`,
		// A.`siteID`,
		// A.`siteName`,
		// COUNT(B.siteID) AS NumberOfTransaction,
		// SUM(CASE WHEN C.billingItemID = 1 THEN C.billingTransactionItemPrice ELSE 0 END) AS Membership,
		// SUM(CASE WHEN C.billingItemID = 3 THEN C.billingTransactionItemPrice ELSE 0 END) AS PCUsage,
		// SUM(CASE WHEN C.billingItemID = 4 THEN C.billingTransactionItemPrice ELSE 0 END) AS PrintPhotstBW,
		// SUM(CASE WHEN C.billingItemID = 5 THEN C.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,
		// SUM(CASE WHEN C.billingItemID = 6 THEN C.billingTransactionItemPrice ELSE 0 END) AS PrintPhotstC,
		// SUM(CASE WHEN C.billingItemID = 7 THEN C.billingTransactionItemPrice ELSE 0 END) AS Scanning,
		// SUM(CASE WHEN C.billingItemID = 8 THEN C.billingTransactionItemPrice ELSE 0 END) AS Laminating,
		// SUM(CASE WHEN C.billingItemID = 12 THEN C.billingTransactionItemPrice ELSE 0 END) AS PackageA,
		// SUM(CASE WHEN C.billingItemID = 13 THEN C.billingTransactionItemPrice ELSE 0 END) AS PackageB,
		// SUM(CASE WHEN C.billingItemID = 14 THEN C.billingTransactionItemPrice ELSE 0 END) AS PackageC,
		// SUM(B.billingTransactionTotal) AS TotalIncome
		// FROM `site` A
		// LEFT JOIN `billing_transaction` B ON B.`siteID` = A.`siteID`
		// LEFT JOIN `billing_transaction_item` C ON C.`billingTransactionID` = B.`billingTransactionID`
		// LEFT JOIN `site_info` D ON D.`siteID` = A.`siteID`
		// GROUP BY A.`siteID`

		// var_dump($ResultSummary);

		return $ResultSummary;
	}


	private function getMonthTotalCashFlow($year,$month,$day) {

	$ResultSummary = db::query("SELECT siteName, site.siteID, stateID, Membership, PCUsage, PrintPhotst, OthersSrvc, Scanning, Laminating, OthersCashIn, Expense FROM site LEFT JOIN ( SELECT A.siteID,SUM(CASE WHEN B.billingItemID = 1 THEN B.billingTransactionItemPrice ELSE 0 END) AS Membership,SUM(CASE WHEN B.billingItemID = 3 || B.billingItemID = 5 || B.billingItemID = 16 THEN B.billingTransactionItemPrice ELSE 0 END) AS PCUsage,SUM(CASE WHEN B.billingItemID = 4 || B.billingItemID = 6 THEN B.billingTransactionItemPrice ELSE 0 END) AS PrintPhotst,SUM(CASE WHEN B.billingItemID = 5 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,SUM(CASE WHEN B.billingItemID = 7 THEN B.billingTransactionItemPrice ELSE 0 END) AS Scanning,SUM(CASE WHEN B.billingItemID = 8 || B.billingItemID = 17 THEN B.billingTransactionItemPrice ELSE 0 END) AS Laminating,SUM(CASE WHEN B.billingItemID = 2 || B.billingItemID = 9 || B.billingItemID = 12 || B.billingItemID = 13 || B.billingItemID = 14 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersCashIn,SUM(CASE WHEN B.billingItemID = 10 || B.billingItemID = 11 THEN ABS(B.billingTransactionItemPrice) ELSE 0 END) AS Expense FROM billing_transaction A LEFT JOIN billing_transaction_item B ON B.billingTransactionID = A.billingTransactionID WHERE A.billingTransactionStatus = 1 AND YEAR(A.billingTransactionDate)=$year AND MONTH(A.billingTransactionDate)=$month AND DAY(A.billingTransactionDate)=$day GROUP BY A.siteID) AS figures ON figures.siteID = site.siteID ORDER BY site.stateID ASC")->result();

	/*$ResultSummary = db::query("SELECT siteName, site.siteID, stateID, Membership, PCUsage, PrintPhotst, OthersSrvc, Scanning, Laminating, OthersCashIn, Expense FROM site LEFT JOIN ( SELECT A.siteID,SUM(CASE WHEN B.billingItemID = 1 THEN B.billingTransactionItemPrice ELSE 0 END) AS Membership,SUM(CASE WHEN B.billingItemID = 3 || B.billingItemID = 5 || B.billingItemID = 16 THEN B.billingTransactionItemPrice ELSE 0 END) AS PCUsage,SUM(CASE WHEN B.billingItemID = 4 || B.billingItemID = 6 THEN B.billingTransactionItemPrice ELSE 0 END) AS PrintPhotst,SUM(CASE WHEN B.billingItemID = 5 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersSrvc,SUM(CASE WHEN B.billingItemID = 7 THEN B.billingTransactionItemPrice ELSE 0 END) AS Scanning,SUM(CASE WHEN B.billingItemID = 8 || B.billingItemID = 17 THEN B.billingTransactionItemPrice ELSE 0 END) AS Laminating,SUM(CASE WHEN B.billingItemID = 2 || B.billingItemID = 9 || B.billingItemID = 12 || B.billingItemID = 13 || B.billingItemID = 14 THEN B.billingTransactionItemPrice ELSE 0 END) AS OthersCashIn,SUM(CASE WHEN B.billingItemID = 10 || B.billingItemID = 11 THEN ABS(B.billingTransactionItemPrice) ELSE 0 END) AS Expense FROM billing_transaction A LEFT JOIN billing_transaction_item B ON B.billingTransactionID = A.billingTransactionID WHERE A.billingTransactionStatus = 1 AND A.billingTransactionDate LIKE '%".$date."%' GROUP BY A.siteID) AS figures ON figures.siteID = site.siteID WHERE site.siteID=$siteID ORDER BY site.stateID ASC")->result();*/


	return $ResultSummary;
	}

	
	private function reportCashFlowSummary($input) {

		$month 	= $input['month'];
		$year 	= $input['year'];
		$endTitle = '';

		# Get month value length
		$Nmonth = strlen($month);

		# Set month value to 2 digit eg: 01,02,03,04,05,06,07,08,09
		if ($Nmonth == 1) {
			$months = '0'.$month;
		} else {
			$months = $month;
		}

		# Set date search format & reset month text
		if ($month == '') {
			$date = $year;
			$titleDate = $year;
		} else {
			$date = $year.'-'.$months;
			$titleDate = $year.'/'.$months;
		}

		# Get Total Cash Flow Summary Data
		$data = $this->getTotalCashFlow($date);

		$month 			= $input['month'];
		$year 			= $input['year'];
		// $sheetName 	= $input['sheetName'];
		$sheetName 		= 'Pi1M';

		if($month){

			$filename		= "Monthly CBC Cash Flow Summary, ".$titleDate;

		}else{

			$filename		= "Annual CBC Cash Flow Summary, ".$titleDate;

		}

		$excel			= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xls");

		# set running alphabet and number.
		$cellRange = range('A', 'Z');
		$i = 0;

		# Calling State helper
		$state = model::load("helper")->state();

		## the main working sheet.
		$sheet	= $excel->getActiveSheet();
		$sheet->setTitle("All Pi1M");

		## prepare report.

		## all cell
		$allCells = $sheet->getStyle("A1:O".(4+count($data)));
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCells->getAlignment()->setWrapText(true);
		foreach(range('A','O') as $columnID) {
		    $sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$allCells->applyFromArray(
					array(
						'borders' => array(
								'allborders' => array(
										'style' => PHPExcel_Style_Border::BORDER_THIN,
										'color' => array('rgb' => 'D3D3D3'),
										'size'  => 11,
										)
								)
						)
					);

		# prepare header.

		# first row header
		$sheet->setCellValue("A1", $filename);
		$sheet->mergeCells("A1:O1");

		# second row header
		$sheet->setCellValue("A2", $sheetName);
		$sheet->mergeCells("A2:C2");

		$sheet->setCellValue("D2", 'Generated at '.now());
		$sheet->mergeCells("D2:O2");

		# set alignment
		$sheet->getStyle("D1:O2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# third row header
		$sheet->setCellValue("D3", 'Income');
		$sheet->mergeCells("D3:K3");

		$sheet->setCellValue("L3", 'Expense');
		$sheet->mergeCells("L3:N3");

		$sheet->setCellValue("O3", 'Balance');

		# set alignment
		$sheet->getStyle("D3:O3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# forth row header
		$forthheader = array('State Name','District Name','Site','Membership Fee','PC Usage','Print Service','Other Service','Scanning','Laminating','Other Cash In','Total Income','Cash Drawer','Bank Account','Total Expense','Balance');

		foreach ($forthheader as $key => $value) {
			$i++;
			//echo $cellRange[$i-1];
			$sheet->setCellValue($cellRange[$i-1].'4', $value);
		}

		# set alignment
		$sheet->getStyle("A4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# set background color
		$sheet->getStyle('A1:O4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6495ED');

		# set font
		$sheet->getStyle('A1:O4')->applyFromArray(
										array(
							    			'font'  => array(
								        		'bold'  => true,
								        		'color' => array('rgb' => 'FFFFFF')
							    			)
							    		)
									);

		# prepare data cell.

		# set alignment
		$sheet->getStyle("D5:O".(4+count($data)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# set color
		$sheet->getStyle("K5:K".(4+count($data)))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('e0e0e0');

		$sheet->getStyle("N5:O".(4+count($data)))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('e0e0e0');

		$sheet->getStyle("A".(5+count($data)).":O".(5+count($data)))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ADD8E6');

		$sheet->getStyle("D5:O".(5+count($data)))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		
		/*
		siteName,
		stateID,
		Membership,
		PCUsage,
		PrintPhotst,
		OthersSrvc,
		Scanning,
		Laminating,
		OthersCashIn
		*/

		// var_dump($data);
		// die();

		$ii = 4;
		$currentState = 0;
		foreach ($data as $key => $value) {
			$ii++;
			if ($currentState != $value['stateID']) {
				$currentState = $value['stateID'];
				$sheet->setCellValue('A'.($ii), $state[$currentState]);
			}

			if (empty($value['Membership'])) {
				$value['Membership'] = 0;
			}

			if (empty($value['PCUsage'])) {
				$value['PCUsage'] = 0;
			}

			if (empty($value['PrintPhotst'])) {
				$value['PrintPhotst'] = 0;
			}

			if (empty($value['OthersSrvc'])) {
				$value['OthersSrvc'] = 0;
			}

			if (empty($value['Scanning'])) {
				$value['Scanning'] = 0;
			}

			if (empty($value['Laminating'])) {
				$value['Laminating'] = 0;
			}

			if (empty($value['OthersCashIn'])) {
				$value['OthersCashIn'] = 0;
			}

			if (empty($value['Expense'])) {
				$value['Expense'] = 0;
			}

			$sheet->setCellValue('C'.($ii), $value['siteName']);
			$sheet->setCellValue('D'.($ii), $value['Membership']);
			$sheet->setCellValue('E'.($ii), $value['PCUsage']);
			$sheet->setCellValue('F'.($ii), $value['PrintPhotst']);
			$sheet->setCellValue('G'.($ii), $value['OthersSrvc']);
			$sheet->setCellValue('H'.($ii), $value['Scanning']);
			$sheet->setCellValue('I'.($ii), $value['Laminating']);
			$sheet->setCellValue('J'.($ii), $value['OthersCashIn']);
			$sheet->setCellValue('K'.($ii), '=SUM(D'.($ii).':J'.($ii).')');

			$sheet->setCellValue('L'.($ii), '0');
			$sheet->setCellValue('M'.($ii), '0');
			$sheet->setCellValue('N'.($ii), $value['Expense']);
			$sheet->setCellValue('O'.($ii), '=K'.($ii).'-N'.($ii));
		}

		# bottom row | total all column
		$sheet->setCellValue('C'.(5+count($data)), 'Pi1M Total');

		for ($i=3; $i < 15; $i++) {
			$sheet->setCellValue($cellRange[$i].(5+count($data)),'=SUM('.$cellRange[$i].'5:'.$cellRange[$i].(4+count($data)).')');
		}

		


		// # Calling Month helper
		// $monthList = model::load("helper")->monthYear("monthE");

		// #create new data by month

		// $monthlyData = array();
		// foreach ($monthList as $key => $val) {
		// 	# Get month value length
		// 	$Nmonth = strlen($key);

		// 	# Set month value to 2 digit eg: 01,02,03,04,05,06,07,08,09
		// 	if ($Nmonth == 1) {
		// 		$monthSite = '0'.$key;
		// 	} else {
		// 		$monthSite = $key;
		// 	}

		// 	$data=$this->getTotalCashFlow($year."-".$monthSite);
		// 	$newData=array();

		// 	foreach($data as $no=>$pim){
		// 		$newData[$pim['siteID']]=$pim;
		// 	}

		// 	$monthlyData[$key]=$newData;
		// }

		// #start looping each tab (pim)
		// foreach($data as $no=>$sitePim){

		// #add sheets for each pi1ms

		// #Start new site tab

		// $siteIDs=$sitePim['siteID'];
		// $siteName=$sitePim['siteName'];

		// $arr = explode(",", $siteName, 2);
		// $first = $arr[0];
		// $sheetSite = $excel->createSheet(1);
		// $sheetSite->setTitle($first);
		// $cellRangeSite = range('A', 'Z');

		// ## all cell
		// $allCellSite = $sheetSite->getStyle("A1:Q".(17));
		// $allCellSite->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		// $allCellSite->getAlignment()->setWrapText(true);
		// foreach(range('A','Q') as $columnID) {
		//     $sheetSite->getColumnDimension($columnID)->setAutoSize(true);
		// }
		// $allCellSite->applyFromArray(
		// 			array(
		// 				'borders' => array(
		// 						'allborders' => array(
		// 								'style' => PHPExcel_Style_Border::BORDER_THIN,
		// 								'color' => array('rgb' => 'D3D3D3'),
		// 								'size'  => 11,
		// 								)
		// 						)
		// 				)
		// 			);



		// # prepare header.

		// # first row header
		// $sheetSite->setCellValue("A1", "CBC Cash Flow Details, ".$date);
		// $sheetSite->mergeCells("A1:Q1");

		// # second row header
		// $sheetSite->setCellValue("A2", $siteName);
		// $sheetSite->mergeCells("A2:C2");

		// $sheetSite->setCellValue("D2", 'Generated at '.now());
		// $sheetSite->mergeCells("D2:Q2");

		// # set alignment
		// $sheetSite->getStyle("D2:Q2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		// # third row header
		// $sheetSite->setCellValue("D3", 'Income');
		// $sheetSite->mergeCells("D3:L3");

		// $sheetSite->setCellValue("M3", 'Expense');
		// $sheetSite->mergeCells("M3:P3");

		// $sheetSite->setCellValue("Q3", 'Balance');

		// # set alignment
		// $sheetSite->getStyle("D3:Q3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		// # forth row header
		// $fourthHeaderSite = array('Year','Month','Date','Membership Fee','PC Usage','Print Service','Other Service','Scanning','Laminating','Other Cash In','Other Cash In Description','Total Income','Cash Drawer','Bank Account','Expense Description','Total Expense','Balance');
		
		// foreach ($fourthHeaderSite as $key=>$value) {
		// 	$sheetSite->setCellValue($cellRange[$key].'4', $value);
		// }

		// # set alignment
		// $sheetSite->getStyle("A4:Q4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		// # set background color
		// $sheetSite->getStyle('A1:Q4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6495ED');

		// # set font
		// $sheetSite->getStyle('A1:Q4')->applyFromArray(
		// 								array(
		// 					    			'font'  => array(
		// 						        		'bold'  => true,
		// 						        		'color' => array('rgb' => 'FFFFFF')
		// 					    			)
		// 					    		)
		// 							);

		// # fifth row header
		// $sheetSite->setCellValue("A5", $year);
		// $sheetSite->mergeCells("A5:A17");

		// # set alignment
		// $sheetSite->getStyle("D5:Q17")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		// # set color
		// $sheetSite->getStyle("B5:Q5")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('add8e6');

		// $sheetSite->getStyle("D6:Q17")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');

		// $sheetSite->getStyle("D5:Q17")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);



		// #All total formula
		// $cellTotal=array('D','E','F','G','H','I','J','L','M','N','P');
		// foreach($cellTotal as $key=>$cell){
		// 		$sheetSite->setCellValue($cell.'5', '=SUM('.$cell.'6:'.$cell.'17)');
		// }
		// #all total balance formula
		// $sheetSite->setCellValue('Q5', '=L5-P5');

		
		// $sheetSite->setCellValue("B5", "Total");
		// $noMonth=6;
		// foreach ($monthList as $key => $value) {
		// 	$sheetSite->setCellValue("B".$noMonth, $value);
		// 	$sheetSite->setCellValue("C".$noMonth, "Total");
		// 	$noMonth++;
		// }

	
		// #list data by date or year
		// if($month){
		// #list data by date
		// $cellSiteStart=6;
		// foreach ($monthList as $key => $value) {
			
		// 	if($key==$month){

		// 	$dataSite = $monthlyData[$key][$siteIDs];
		// 	$sheetSite->setCellValue('D'.$cellSiteStart, empty($dataSite['Membership'])?'0':$dataSite['Membership']);
		// 	$sheetSite->setCellValue('E'.$cellSiteStart, empty($dataSite['PCUsage'])?'0':$dataSite['PCUsage']);
		// 	$sheetSite->setCellValue('F'.$cellSiteStart, empty($dataSite['PrintPhotst'])?'0':$dataSite['PrintPhotst']);
		// 	$sheetSite->setCellValue('G'.$cellSiteStart, empty($dataSite['OthersSrvc'])?'0':$dataSite['OthersSrvc']);
		// 	$sheetSite->setCellValue('H'.$cellSiteStart, empty($dataSite['Scanning'])?'0':$dataSite['Scanning']);
		// 	$sheetSite->setCellValue('I'.$cellSiteStart, empty($dataSite['Laminating'])?'0':$dataSite['Laminating']);
		// 	$sheetSite->setCellValue('J'.$cellSiteStart, empty($dataSite['OthersCashIn'])?'0':$dataSite['OthersCashIn']);
		// 	$sheetSite->setCellValue('K'.$cellSiteStart, "");
		// 	$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
		// 	$sheetSite->setCellValue('M'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('N'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('O'.$cellSiteStart, "");
		// 	$sheetSite->setCellValue('P'.$cellSiteStart, empty($dataSite['Expense'])?'0':$dataSite['Expense']);
		// 	$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);

		// 	}else{

		// 	$sheetSite->setCellValue('D'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('E'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('F'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('G'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('H'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('I'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('J'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('K'.$cellSiteStart, "");
		// 	$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
		// 	$sheetSite->setCellValue('M'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('N'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('O'.$cellSiteStart, "");
		// 	$sheetSite->setCellValue('P'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);
		// 	}
			
		// 	$cellSiteStart++;
			
		// }
		// }else{
		// 	#list data by month yearly
		// $cellSiteStart=6;
		// foreach ($monthList as $key => $value) {

			
		// 	$dataSite = $monthlyData[$key][$siteIDs];
		// 	$sheetSite->setCellValue('D'.$cellSiteStart, empty($dataSite['Membership'])?'0':$dataSite['Membership']);
		// 	$sheetSite->setCellValue('E'.$cellSiteStart, empty($dataSite['PCUsage'])?'0':$dataSite['PCUsage']);
		// 	$sheetSite->setCellValue('F'.$cellSiteStart, empty($dataSite['PrintPhotst'])?'0':$dataSite['PrintPhotst']);
		// 	$sheetSite->setCellValue('G'.$cellSiteStart, empty($dataSite['OthersSrvc'])?'0':$dataSite['OthersSrvc']);
		// 	$sheetSite->setCellValue('H'.$cellSiteStart, empty($dataSite['Scanning'])?'0':$dataSite['Scanning']);
		// 	$sheetSite->setCellValue('I'.$cellSiteStart, empty($dataSite['Laminating'])?'0':$dataSite['Laminating']);
		// 	$sheetSite->setCellValue('J'.$cellSiteStart, empty($dataSite['OthersCashIn'])?'0':$dataSite['OthersCashIn']);
		// 	$sheetSite->setCellValue('K'.$cellSiteStart, "");
		// 	$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
		// 	$sheetSite->setCellValue('M'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('N'.$cellSiteStart, "0");
		// 	$sheetSite->setCellValue('O'.$cellSiteStart, "");
		// 	$sheetSite->setCellValue('P'.$cellSiteStart, empty($dataSite['Expense'])?'0':$dataSite['Expense']);
		// 	$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);

		// 	$cellSiteStart++;
			
		// }
		// }
		


		// }


		$ExcelHelper->execute();
	}

    public function get_site(){
       $sitemodel = model::load("site/site");
        if (isset($_GET['term'])){
          $q = strtolower($_GET['term']);
          //var_dump($q);
          $sitemodel->get_list_site($q);

          //return $sitemodel;
        }
    }

    private function reportCashFlowSummaryFull($input) {

		$month 	= $input['month'];
		$year 	= $input['year'];
		$endTitle = '';

		# Get month value length
		$Nmonth = strlen($month);

		# Set month value to 2 digit eg: 01,02,03,04,05,06,07,08,09
		if ($Nmonth == 1) {
			$months = '0'.$month;
		} else {
			$months = $month;
		}

		# Set date search format & reset month text
		if ($month == '') {
			$date = $year;
			$titleDate = $year;
		} else {
			$date = $year.'-'.$months;
			$titleDate = $year.'/'.$months;
		}

		# Get Total Cash Flow Summary Data
		$data = $this->getTotalCashFlow($date);

		$month 			= $input['month'];
		$year 			= $input['year'];
		// $sheetName 	= $input['sheetName'];
		$sheetName 		= 'Pi1M';

		$filename		= "CBC Cash Flow Summary, ".$titleDate;

		$excel			= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xls");

		# set running alphabet and number.
		$cellRange = range('A', 'Z');
		$i = 0;

		# Calling State helper
		$state = model::load("helper")->state();

		## the main working sheet.
		$sheet	= $excel->getActiveSheet();
		$sheet->setTitle("All Pi1M");

		## prepare report.

		## all cell
		$allCells = $sheet->getStyle("A1:O".(4+count($data)));
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCells->getAlignment()->setWrapText(true);
		foreach(range('A','O') as $columnID) {
		    $sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$allCells->applyFromArray(
					array(
						'borders' => array(
								'allborders' => array(
										'style' => PHPExcel_Style_Border::BORDER_THIN,
										'color' => array('rgb' => 'D3D3D3'),
										'size'  => 11,
										)
								)
						)
					);

		# prepare header.

		# first row header
		$sheet->setCellValue("A1", $filename);
		$sheet->mergeCells("A1:O1");

		# second row header
		$sheet->setCellValue("A2", $sheetName);
		$sheet->mergeCells("A2:C2");

		$sheet->setCellValue("D2", 'Generated at '.now());
		$sheet->mergeCells("D2:O2");

		# set alignment
		$sheet->getStyle("D1:O2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# third row header
		$sheet->setCellValue("D3", 'Income');
		$sheet->mergeCells("D3:K3");

		$sheet->setCellValue("L3", 'Expense');
		$sheet->mergeCells("L3:N3");

		$sheet->setCellValue("O3", 'Balance');

		# set alignment
		$sheet->getStyle("D3:O3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# forth row header
		$forthheader = array('State Name','District Name','Site','Membership Fee','PC Usage','Print Service','Other Service','Scanning','Laminating','Other Cash In','Total Income','Cash Drawer','Bank Account','Total Expense','Balance');

		foreach ($forthheader as $key => $value) {
			$i++;
			//echo $cellRange[$i-1];
			$sheet->setCellValue($cellRange[$i-1].'4', $value);
		}

		# set alignment
		$sheet->getStyle("A4:O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# set background color
		$sheet->getStyle('A1:O4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6495ED');

		# set font
		$sheet->getStyle('A1:O4')->applyFromArray(
										array(
							    			'font'  => array(
								        		'bold'  => true,
								        		'color' => array('rgb' => 'FFFFFF')
							    			)
							    		)
									);

		# prepare data cell.

		# set alignment
		$sheet->getStyle("D5:O".(4+count($data)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# set color
		$sheet->getStyle("K5:K".(4+count($data)))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('e0e0e0');

		$sheet->getStyle("N5:O".(4+count($data)))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('e0e0e0');

		$sheet->getStyle("A".(5+count($data)).":O".(5+count($data)))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ADD8E6');

		$sheet->getStyle("D5:O".(5+count($data)))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		
		/*
		siteName,
		stateID,
		Membership,
		PCUsage,
		PrintPhotst,
		OthersSrvc,
		Scanning,
		Laminating,
		OthersCashIn
		*/

		// var_dump($data);
		// die();

		$ii = 4;
		$currentState = 0;
		foreach ($data as $key => $value) {
			$ii++;
			if ($currentState != $value['stateID']) {
				$currentState = $value['stateID'];
				$sheet->setCellValue('A'.($ii), $state[$currentState]);
			}

			if (empty($value['Membership'])) {
				$value['Membership'] = 0;
			}

			if (empty($value['PCUsage'])) {
				$value['PCUsage'] = 0;
			}

			if (empty($value['PrintPhotst'])) {
				$value['PrintPhotst'] = 0;
			}

			if (empty($value['OthersSrvc'])) {
				$value['OthersSrvc'] = 0;
			}

			if (empty($value['Scanning'])) {
				$value['Scanning'] = 0;
			}

			if (empty($value['Laminating'])) {
				$value['Laminating'] = 0;
			}

			if (empty($value['OthersCashIn'])) {
				$value['OthersCashIn'] = 0;
			}

			if (empty($value['Expense'])) {
				$value['Expense'] = 0;
			}

			$sheet->setCellValue('C'.($ii), $value['siteName']);
			$sheet->setCellValue('D'.($ii), $value['Membership']);
			$sheet->setCellValue('E'.($ii), $value['PCUsage']);
			$sheet->setCellValue('F'.($ii), $value['PrintPhotst']);
			$sheet->setCellValue('G'.($ii), $value['OthersSrvc']);
			$sheet->setCellValue('H'.($ii), $value['Scanning']);
			$sheet->setCellValue('I'.($ii), $value['Laminating']);
			$sheet->setCellValue('J'.($ii), $value['OthersCashIn']);
			$sheet->setCellValue('K'.($ii), '=SUM(D'.($ii).':J'.($ii).')');

			$sheet->setCellValue('L'.($ii), '0');
			$sheet->setCellValue('M'.($ii), '0');
			$sheet->setCellValue('N'.($ii), $value['Expense']);
			$sheet->setCellValue('O'.($ii), '=K'.($ii).'-N'.($ii));
		}

		# bottom row | total all column
		$sheet->setCellValue('C'.(5+count($data)), 'Pi1M Total');

		for ($i=3; $i < 15; $i++) {
			$sheet->setCellValue($cellRange[$i].(5+count($data)),'=SUM('.$cellRange[$i].'5:'.$cellRange[$i].(4+count($data)).')');
		}

		


		# Calling Month helper
		$monthList = model::load("helper")->monthYear("monthE");

		// #create new data by month

		 $monthlyData = array();
		 foreach ($monthList as $key => $val) {
		 	# Get month value length
		 	$Nmonth = strlen($key);

		 	# Set month value to 2 digit eg: 01,02,03,04,05,06,07,08,09
		 	if ($Nmonth == 1) {
		 		$monthSite = '0'.$key;
		 	} else {
		 		$monthSite = $key;
		 	}

		 	$data=$this->getTotalCashFlow($year."-".$monthSite);
		 	$newData=array();

		 	foreach($data as $no=>$pim){
		 		$newData[$pim['siteID']]=$pim;
		 	}

		 	$monthlyData[$key]=$newData;
		 }


		##############################################
		#  Start create new sheets for pim           #
		#  if theres month, do monthly, else annual  #
		##############################################

		if($month){
		#preparing data
		#day count in month
		$daysInMonthData = array();
		$monCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for ($i=1; $i <= $monCount; $i++) { 
			
			$data=$this->getMonthTotalCashFlow($year,$month,$i);
			$newData=array();

			foreach($data as $no=>$pim){
				$newData[$pim['siteID']]=$pim;
			}

			$daysInMonthData[$i]=$newData;
		}
	

		#start looping each tab (pim)
		foreach($data as $no=>$sitePim){

		// #add sheets for each pi1ms

		// #Start new site tab

		$siteIDs=$sitePim['siteID'];
		$siteName=substr($sitePim['siteName'], 0, 15);;

		//$siteIDs=68;
		//$siteName="Felda Bukit Batu, Johor";

		// $arr = explode(",", $siteName, 2);
		// $first = $arr[0];
		// $sheetSite = $excel->createSheet(1);
		// $sheetSite->setTitle($first);
		// $cellRangeSite = range('A', 'Z');

		$endSheetRow=$monCount+5;

		## all cell
		$allCellSite = $sheetSite->getStyle("A1:Q".$endSheetRow);
		$allCellSite->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCellSite->getAlignment()->setWrapText(true);
		foreach(range('A','Q') as $columnID) {
		    $sheetSite->getColumnDimension($columnID)->setAutoSize(true);
		}
		$allCellSite->applyFromArray(
					array(
						'borders' => array(
								'allborders' => array(
										'style' => PHPExcel_Style_Border::BORDER_THIN,
										'color' => array('rgb' => 'D3D3D3'),
										'size'  => 11,
										)
								)
						)
					);



		# prepare header.

		# first row header
		$sheetSite->setCellValue("A1", $filename);
		$sheetSite->mergeCells("A1:Q1");

		# second row header
		$sheetSite->setCellValue("A2", $siteName);
		$sheetSite->mergeCells("A2:C2");

		$sheetSite->setCellValue("D2", 'Generated at '.now());
		$sheetSite->mergeCells("D2:Q2");

		# set alignment
		$sheetSite->getStyle("D2:Q2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# third row header
		$sheetSite->setCellValue("D3", 'Income');
		$sheetSite->mergeCells("D3:L3");

		$sheetSite->setCellValue("M3", 'Expense');
		$sheetSite->mergeCells("M3:P3");

		$sheetSite->setCellValue("Q3", 'Balance');

		# set alignment
		$sheetSite->getStyle("D3:Q3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# forth row header
		$fourthHeaderSite = array('Year','Month','Date','Membership Fee','PC Usage','Print Service','Other Service','Scanning','Laminating','Other Cash In','Other Cash In Description','Total Income','Cash Drawer','Bank Account','Expense Description','Total Expense','Balance');
		
		foreach ($fourthHeaderSite as $key=>$value) {
			$sheetSite->setCellValue($cellRange[$key].'4', $value);
		}

		# set alignment
		$sheetSite->getStyle("A4:Q4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# set background color
		$sheetSite->getStyle('A1:Q4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6495ED');

		# set font
		$sheetSite->getStyle('A1:Q4')->applyFromArray(
										array(
							    			'font'  => array(
								        		'bold'  => true,
								        		'color' => array('rgb' => 'FFFFFF')
							    			)
							    		)
									);

		# fifth row header
		$sheetSite->setCellValue("A5", $year);
		$sheetSite->mergeCells("A5:A".$endSheetRow);

		# set alignment
		$sheetSite->getStyle("D5:Q".$endSheetRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# set color
		$sheetSite->getStyle("B5:Q5")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('add8e6');

		$sheetSite->getStyle("D6:Q".$endSheetRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');

		$sheetSite->getStyle("D5:Q".$endSheetRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);



		#All total formula
		$cellTotal=array('D','E','F','G','H','I','J','L','M','N','P');
		$endDateRow=$monCount+5;
		foreach($cellTotal as $key=>$cell){
				$sheetSite->setCellValue($cell.'5', '=SUM('.$cell.'6:'.$cell.$endDateRow.')');
		}
		#all total balance formula
		$sheetSite->setCellValue('Q5', '=L5-P5');

		
		$sheetSite->setCellValue("B5", "Total");
		$sheetSite->setCellValue("B6", $monthList[$month]);
		$dateRowStart=6;
		for ($i=1; $i <= $monCount; $i++) { 
			$sheetSite->setCellValue("C".$dateRowStart, $i."/".$month."/".$year);
			$dateRowStart++;
		}


		#list data by date
		$cellSiteStart=6;
		for ($i=1; $i <= $monCount; $i++) {

			
			$dataSite = $daysInMonthData[$i][$siteIDs];
			$sheetSite->setCellValue('D'.$cellSiteStart, empty($dataSite['Membership'])?'0':$dataSite['Membership']);
			$sheetSite->setCellValue('E'.$cellSiteStart, empty($dataSite['PCUsage'])?'0':$dataSite['PCUsage']);
			$sheetSite->setCellValue('F'.$cellSiteStart, empty($dataSite['PrintPhotst'])?'0':$dataSite['PrintPhotst']);
			$sheetSite->setCellValue('G'.$cellSiteStart, empty($dataSite['OthersSrvc'])?'0':$dataSite['OthersSrvc']);
			$sheetSite->setCellValue('H'.$cellSiteStart, empty($dataSite['Scanning'])?'0':$dataSite['Scanning']);
			$sheetSite->setCellValue('I'.$cellSiteStart, empty($dataSite['Laminating'])?'0':$dataSite['Laminating']);
			$sheetSite->setCellValue('J'.$cellSiteStart, empty($dataSite['OthersCashIn'])?'0':$dataSite['OthersCashIn']);
			$sheetSite->setCellValue('K'.$cellSiteStart, "");
			$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
			$sheetSite->setCellValue('M'.$cellSiteStart, "0");
			$sheetSite->setCellValue('N'.$cellSiteStart, "0");
			$sheetSite->setCellValue('O'.$cellSiteStart, "");
			$sheetSite->setCellValue('P'.$cellSiteStart, empty($dataSite['Expense'])?'0':$dataSite['Expense']);
			$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);

			$cellSiteStart++;
			
		}
		

		}
		

		}else{

		#start looping each tab (pim)
		foreach($data as $no=>$sitePim){

		#add sheets for each pi1ms

		#Start new site tab

		$siteIDs=$sitePim['siteID'];
		$siteName=substr($sitePim['siteName'], 0, 15);

		// $arr = explode(",", $siteName, 2);
		// $first = $arr[0];
		// $sheetSite = $excel->createSheet(1);
		// $sheetSite->setTitle($first);
		// $cellRangeSite = range('A', 'Z');

		## all cell
		$allCellSite = $sheetSite->getStyle("A1:Q".(17));
		$allCellSite->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCellSite->getAlignment()->setWrapText(true);
		foreach(range('A','Q') as $columnID) {
		    $sheetSite->getColumnDimension($columnID)->setAutoSize(true);
		}
		$allCellSite->applyFromArray(
					array(
						'borders' => array(
								'allborders' => array(
										'style' => PHPExcel_Style_Border::BORDER_THIN,
										'color' => array('rgb' => 'D3D3D3'),
										'size'  => 11,
										)
								)
						)
					);



		// # prepare header.

		# first row header
		$sheetSite->setCellValue("A1", $filename);
		$sheetSite->mergeCells("A1:Q1");

		# second row header
		$sheetSite->setCellValue("A2", $siteName);
		$sheetSite->mergeCells("A2:C2");

		$sheetSite->setCellValue("D2", 'Generated at '.now());
		$sheetSite->mergeCells("D2:Q2");

		# set alignment
		$sheetSite->getStyle("D2:Q2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# third row header
		$sheetSite->setCellValue("D3", 'Income');
		$sheetSite->mergeCells("D3:L3");

		$sheetSite->setCellValue("M3", 'Expense');
		$sheetSite->mergeCells("M3:P3");

		$sheetSite->setCellValue("Q3", 'Balance');

		# set alignment
		$sheetSite->getStyle("D3:Q3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# forth row header
		$fourthHeaderSite = array('Year','Month','Date','Membership Fee','PC Usage','Print Service','Other Service','Scanning','Laminating','Other Cash In','Other Cash In Description','Total Income','Cash Drawer','Bank Account','Expense Description','Total Expense','Balance');
		
		foreach ($fourthHeaderSite as $key=>$value) {
			$sheetSite->setCellValue($cellRange[$key].'4', $value);
		}

		# set alignment
		$sheetSite->getStyle("A4:Q4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		# set background color
		$sheetSite->getStyle('A1:Q4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6495ED');

		# set font
		$sheetSite->getStyle('A1:Q4')->applyFromArray(
										array(
							    			'font'  => array(
								        		'bold'  => true,
								        		'color' => array('rgb' => 'FFFFFF')
							    			)
							    		)
									);

		# fifth row header
		$sheetSite->setCellValue("A5", $year);
		$sheetSite->mergeCells("A5:A17");

		# set alignment
		$sheetSite->getStyle("D5:Q17")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		# set color
		$sheetSite->getStyle("B5:Q5")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('add8e6');

		$sheetSite->getStyle("D6:Q17")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');

		$sheetSite->getStyle("D5:Q17")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);



		#All total formula
		$cellTotal=array('D','E','F','G','H','I','J','L','M','N','P');
		foreach($cellTotal as $key=>$cell){
				$sheetSite->setCellValue($cell.'5', '=SUM('.$cell.'6:'.$cell.'17)');
		}
		#all total balance formula
		$sheetSite->setCellValue('Q5', '=L5-P5');

		
		$sheetSite->setCellValue("B5", "Total");
		$noMonth=6;
		foreach ($monthList as $key => $value) {
			$sheetSite->setCellValue("B".$noMonth, $value);
			$sheetSite->setCellValue("C".$noMonth, "Total");
			$noMonth++;
		}

	
		#list data by date or year
		if($month){
		#list data by date
		$cellSiteStart=6;
		foreach ($monthList as $key => $value) {
			
			if($key==$month){

			$dataSite = $monthlyData[$key][$siteIDs];
			$sheetSite->setCellValue('D'.$cellSiteStart, empty($dataSite['Membership'])?'0':$dataSite['Membership']);
			$sheetSite->setCellValue('E'.$cellSiteStart, empty($dataSite['PCUsage'])?'0':$dataSite['PCUsage']);
			$sheetSite->setCellValue('F'.$cellSiteStart, empty($dataSite['PrintPhotst'])?'0':$dataSite['PrintPhotst']);
			$sheetSite->setCellValue('G'.$cellSiteStart, empty($dataSite['OthersSrvc'])?'0':$dataSite['OthersSrvc']);
			$sheetSite->setCellValue('H'.$cellSiteStart, empty($dataSite['Scanning'])?'0':$dataSite['Scanning']);
			$sheetSite->setCellValue('I'.$cellSiteStart, empty($dataSite['Laminating'])?'0':$dataSite['Laminating']);
			$sheetSite->setCellValue('J'.$cellSiteStart, empty($dataSite['OthersCashIn'])?'0':$dataSite['OthersCashIn']);
			$sheetSite->setCellValue('K'.$cellSiteStart, "");
			$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
			$sheetSite->setCellValue('M'.$cellSiteStart, "0");
			$sheetSite->setCellValue('N'.$cellSiteStart, "0");
			$sheetSite->setCellValue('O'.$cellSiteStart, "");
			$sheetSite->setCellValue('P'.$cellSiteStart, empty($dataSite['Expense'])?'0':$dataSite['Expense']);
			$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);

			}else{

			$sheetSite->setCellValue('D'.$cellSiteStart, "0");
			$sheetSite->setCellValue('E'.$cellSiteStart, "0");
			$sheetSite->setCellValue('F'.$cellSiteStart, "0");
			$sheetSite->setCellValue('G'.$cellSiteStart, "0");
			$sheetSite->setCellValue('H'.$cellSiteStart, "0");
			$sheetSite->setCellValue('I'.$cellSiteStart, "0");
			$sheetSite->setCellValue('J'.$cellSiteStart, "0");
			$sheetSite->setCellValue('K'.$cellSiteStart, "");
			$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
			$sheetSite->setCellValue('M'.$cellSiteStart, "0");
			$sheetSite->setCellValue('N'.$cellSiteStart, "0");
			$sheetSite->setCellValue('O'.$cellSiteStart, "");
			$sheetSite->setCellValue('P'.$cellSiteStart, "0");
			$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);
			}
			
			$cellSiteStart++;
			
		}
		}else{
			#list data by month yearly
		$cellSiteStart=6;
		foreach ($monthList as $key => $value) {

			
			$dataSite = $monthlyData[$key][$siteIDs];
			$sheetSite->setCellValue('D'.$cellSiteStart, empty($dataSite['Membership'])?'0':$dataSite['Membership']);
			$sheetSite->setCellValue('E'.$cellSiteStart, empty($dataSite['PCUsage'])?'0':$dataSite['PCUsage']);
			$sheetSite->setCellValue('F'.$cellSiteStart, empty($dataSite['PrintPhotst'])?'0':$dataSite['PrintPhotst']);
			$sheetSite->setCellValue('G'.$cellSiteStart, empty($dataSite['OthersSrvc'])?'0':$dataSite['OthersSrvc']);
			$sheetSite->setCellValue('H'.$cellSiteStart, empty($dataSite['Scanning'])?'0':$dataSite['Scanning']);
			$sheetSite->setCellValue('I'.$cellSiteStart, empty($dataSite['Laminating'])?'0':$dataSite['Laminating']);
			$sheetSite->setCellValue('J'.$cellSiteStart, empty($dataSite['OthersCashIn'])?'0':$dataSite['OthersCashIn']);
			$sheetSite->setCellValue('K'.$cellSiteStart, "");
			$sheetSite->setCellValue('L'.$cellSiteStart, '=SUM(D'.$cellSiteStart.':J'.$cellSiteStart.')');
			$sheetSite->setCellValue('M'.$cellSiteStart, "0");
			$sheetSite->setCellValue('N'.$cellSiteStart, "0");
			$sheetSite->setCellValue('O'.$cellSiteStart, "");
			$sheetSite->setCellValue('P'.$cellSiteStart, empty($dataSite['Expense'])?'0':$dataSite['Expense']);
			$sheetSite->setCellValue('Q'.$cellSiteStart, '=L'.$cellSiteStart.'-P'.$cellSiteStart);

			$cellSiteStart++;
			
		}
		}
		


		}

		}
		#end if else



		$ExcelHelper->execute();
	}

	public function reportFormField($idReport){

		$data['todayDateStart'] 	= date('Y-m-d H:i');
		$data['model_form_fields'] 	= model::load("report/report")->getReportFormField($idReport);
		$data['model_report_form']	= model::load("report/report")->getReportForm($idReport);
		$data['idreport']			= $idReport;
		//var_dump($data);
		view::render('root/report/dashboardFormField',$data);
	}

	public function reportDashboard($page = 1){

				## if got search key.
		if(request::get("search"))
		{
			$searchText = request::get('search');
			$where['reportsFormName LIKE ? OR reportsFormDesc LIKE ?'] = array('%'.$searchText.'%', '%'.$searchText . '%');
		}

		## get paginated user list.
		$data['reports']	= model::load("report/report")->getListReport($where,
			array('currentPage'=> $page,
				  'urlFormat'=> url::base("report/reportDashboard/{page}", true)));

		//var_dump($data);
		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());

		view::render("root/report/dashboard", $data);
	}

	public function monthlyActivity()
	{
		view::render("root/report/monthlyActivity");
	}

	public function masterListing()
	{
		view::render("root/report/masterListing");
	}

	public function generateMonthlyActivity($year,$month)
	{
		$report	= model::load("report/report")->getMonthlyReport($year,$month);
/*
		echo "<pre>";
		print_r($report);die;*/
		$filename	= "Monthly USP Project Update - ".date("F",strtotime("2014-$month-01"))." $year";

		$excel	= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xls");

		## the main working sheet.
		$sheet	= $excel->getActiveSheet();

		## prepare report.

		# prepare header.
		$cellHeader	= Array(
						"No",
						"State",
						"Parliament",
						"Phase",
						"District",
						"Name of Kampung / Coverage Target / Signage Name",
						"Wifi Access Point (AP) Location 3G/HSDPA Coverage",
						"SP",
						"Total no. of registered member",
						"Monthly New Members",
						"Monthly Training Hours",
						"Training Details",
						"Total No. of participants",
						"Event(s)",
						"Performance Summary",
						"",
						"",
						"Scheduled Maintenance");

		$startX	= "A";
		$startY	= 1;

		$currX	= $startX;
		$currY	= $startY;

		foreach($cellHeader as $no=>$headerColname)
		{
			$sheet->setCellValue($currX.$currY,$headerColname);

			## merge cells, except QPO.
			if(!in_array($currX,Array("O","P","Q")))
				$sheet->mergeCells($currX.$currY.":".$currX.($currY+2));
			
			$currX++;
		}

		## set performance columns.
		$sheet->setCellValue("O3","Total Faulty Equipments");
		$sheet->setCellValue("P3","Status (Pending/Resolved)");
		$sheet->setCellValue("Q3","Description");

		## merge performance.
		$sheet->mergeCells("O1:Q2");

		## set background color/alignment/font for header.
		$headerStyle	= $sheet->getStyle('A1:R3');
		$headerStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFC000');
		$headerStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$headerStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$headerStyle->getFont()->setBold(true);
		$headerStyle->getAlignment()->setWrapText(true);


		$currY++;$currY++;$currY++;
		$no = 0;
		foreach($report['siteData'] as $siteID=>$row)
		{
			$currX	= $startX;
			$no++;

			## prepare array of data..
			$cellData	= Array(
						"no"=>$no,
						"state"=>$row['stateName'],
						"parliament"=>"",
						"phase"=>"",
						"district"=>"",
						"siteName"=>$row['siteName'],
						"wifi"=>"",
						"sp"=>"CELCOM",
						"registered"=>$report['totalMember'][$siteID],
						"monthly"=>$report['monthlyRegistered'][$siteID],
						"trainingHours"=>isset($report['training'][$siteID])?$report['training'][$siteID]['hours']:0,
						"trainingDetails"=>isset($report['training'][$siteID])?implode(", ",$report['training'][$siteID]['name']):"",
						"totalParticipants"=>isset($report['training'][$siteID])?$report['training'][$siteID]['users']:0,
						"totalEvents"=>$report['totalEvents'][$siteID],
						"performance_totalfault"=>"",
						"performance_status"=>"",
						"performance_description"=>"",
						"scheduled"=>""
								);

			foreach($cellData as $val)
			{
				$sheet->setCellValue($currX.$currY,$val);
				$currX++;
			}

			$currY++;
		}

		## width dimensional width set.
		$widthR	= Array(
				"a"=>"5",
				"b"=>"20",
				"c"=>"20",
				"d"=>"20",
				"e"=>"20",
				"f"=>30,
				"g"=>25,
				"h"=>8,
				"i"=>15,
				"j"=>15,
				"k"=>8,
				"l"=>8,
				"m"=>13,
				"n"=>13,
				"o"=>20,
				"p"=>20,
				"q"=>20,
				"r"=>20
						);

		foreach($widthR as $widthX=>$width)
		{
			$sheet->getColumnDimension($widthX)->setWidth($width);
		}

		## all borders
		$allCells	= $sheet->getStyle("A1:R".(3+count($report['siteData'])));
		$allCells->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCells->getAlignment()->setWrapText(true);
		$allCells->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$ExcelHelper->execute();
	}

	public function generateMasterListing($month = null, $year = null, $totalMonth = 12)
	{
		if(!$month || !$year)
		{
			$startDate = request::get('start').' 00:00:00';
			$endDate = request::get('end').' 23:59:59';
		}
		else
		{
			$endDate	= "$year-$month-".date("t",strtotime("$year-$month-01"))." 23:59:59";
			$startDate	= date("Y-m-d",strtotime("-$totalMonth month",strtotime("+1 day",strtotime($endDate))));
		}

		$data = model::load("report/report")->getMasterListing($startDate,$endDate);

		$filename	= "Pi1M Data (NEW KPI) - ".date("j F Y",strtotime($endDate));

		## initiate both helper and php excel.
		$excel			= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xls");

		## the main working sheet.
		$sheet	= $excel->getActiveSheet();
		$sheet->setTitle("MasterListing");
		#1 create main title.
		$sheet->setCellValue("F1","MASTER LISTING DATA");
		$sheet->getStyle("F1")->getFont()->setSize(18);
		$sheet->getRowDimension(1)->setRowHeight(25);
		$sheet->getRowDimension(4)->setRowHeight(20);
		$sheet->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheet->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate)));
		$sheet->getStyle("A2")->getFont()->setSize(16);
		$sheet->getRowDimension(2)->setRowHeight(22);


		#3 main table.
		$startX	= "A";
		$startY	= 4;

		$x	= $startX;
		$y	= $startY;

		## create table header.
		$tableHeader	= Array(
					"No.",
					"RO",
					"State",
					"District",
					"UST",
					"CBC's Name",
					"SP",
					"Status",
					"Total Registered Member",
					"Total Active Members",
					"Gender", # 2 split
					"",
					"Group", # 2 split (bumi, non bumi)
					"",
					"Total Trained", # 2 split
					"",
					"Age Group", # 2 split
					"",
					"",
					"",
					"Occupation", # 5 split
					"",
					"",
					"",
					"",
					""
								);



		foreach($tableHeader as $no=>$headerColname)
		{
			$sheet->setCellValue($x.$y,$headerColname);

			## merge cells.
			if($x <= "J")
			{
				$sheet->mergeCells($x."4:$x"."5");
			}

			$x++;
		}

		## merge parents cells.
		foreach(Array("k4:l4","m4:n4","o4:p4","q4:t4","u4:z4") as $coords)
		{
			$sheet->mergeCells($coords);
		}

		$y++;

		## create subcolumn.
		# gender.
		$sheet->setCellValue("k$y","Male");
		$sheet->setCellValue("l$y","Female");

		$subcolumn	= Array(
					"k"=>"Male",
					"l"=>"Female",
					"m"=>"Bumi",
					"n"=>"Non Bumi",
					"o"=>"Male",
					"p"=>"Female",
					"q"=>"Under 18",
					"r"=>"18 - 35",
					"s"=>"36 - 55",
					"t"=>"Over 55",
					"u"=>"Students",
					"v"=>"Housewives",
					"w"=>"Self-employed",
					"x"=>"Employed",
					"y"=>"Not-employed",
					"z"=>"Retiree"
							);

		foreach($subcolumn as $subcolX=>$colname)
		{
			$sheet->setCellValue($subcolX.$y,$colname);
		}
		$y++;

		## coloring.
		$headerStyle	= $sheet->getStyle('A4:H5');
		$headerStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

		# color : reg member.
		$colors		= Array(
					"A4:H5"=>'FFFF00',
					"I4:I5"=>"00b0f0",
					"J4:J5"=>"daeef3",
					"K4:L5"=>"ccc0d9",
					"M4:N5"=>"b8cce4",
					"O4:P5"=>"ffff00",
					"Q4:T5"=>"fbd4b4",
					"U4:Z5"=>"d6e3bc"
							);

		foreach($colors as $coords=>$color)
		{
			$sheet->getStyle($coords)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
		}

		## all borders
		$allCells	= $sheet->getStyle("A4:Z".(5+count($data['siteData'])));
		$allCells->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCells->getAlignment()->setWrapText(true);
		$allCells->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		## width dimensional width set.
		$widthR	= Array(
				"a"=>"5",
				"b"=>"10",
				"c"=>"20",
				"d"=>"20",
				"e"=>"20",
				"f"=>30,
				"g"=>8,
				"h"=>8,
				"i"=>15,
				"j"=>15,
				"k"=>8,
				"l"=>8,
				"m"=>13,
				"n"=>13,
				"o"=>8,
				"p"=>8,
				"q"=>10,
				"r"=>10,
				"s"=>10,
				"t"=>10,
				"u"=>10,
				"v"=>14,
				"w"=>14,
				"x"=>10,
				"y"=>14,
				"z"=>10
						);

		foreach($widthR as $widthX=>$width)
		{
			$sheet->getColumnDimension($widthX)->setWidth($width);
		}

		#3.1 main loop.
		$no = 0;
		foreach($data['siteData'] as $siteID=>$row)
		{
			$siteInfo = $data['siteInfo'][$siteID];
			//var_dump($siteInfo);
			//die;
			$no++;
			$cellData = Array(
					"no"=>$no,
					"RO"=>"",
					"state"=>$row['stateName'],
					"district"=>$siteInfo['siteInfoDistrict'],
					"ust"=>$siteInfo['siteInfoUst'],
					"siteName"=>$row['siteName'],
					"sp"=>"Celcom",
					"status"=>"On Air",
					"totalRegistered"		=>$data['totalRegistered'][$siteID],
					"totalActive"			=>$data['totalActive'][$siteID]?:0,
					"gender_male"			=>$data['gender'][$siteID]['male'],
					"gender_female"			=>$data['gender'][$siteID]['female'],
					"group_bumi"			=>$data['group'][$siteID]['bumi'],
					"group_nonbumi"			=>$data['group'][$siteID]['non-bumi'],
					"totalTrainined_male"	=>$data['totalTrained'][$siteID]['male'],
					"totalTrainined_female"	=>$data['totalTrained'][$siteID]['female'],
					"age_18"				=>$data['ageRange'][$siteID]['under18'],
					"age_over18"			=>$data['ageRange'][$siteID]['18-35'],
					"age_over35"			=>$data['ageRange'][$siteID]['36-55'],
					"age_over55"			=>$data['ageRange'][$siteID]['over55'],
					"occ_students"			=>$data['occupation'][$siteID]['students'],
					"occ_housewives"		=>$data['occupation'][$siteID]['housewives'],
					"occ_selfemployed"		=>$data['occupation'][$siteID]['self-employed'],
					"employed"				=>$data['occupation'][$siteID]['employed'],
					"not-employed"			=>$data['occupation'][$siteID]['not-employed'],
					"retiree"				=>$data['occupation'][$siteID]['retiree']
							);

			## reset.
			$x	= $startX;
			foreach($cellData as $key=>$val)
			{
				$sheet->setCellValue($x.$y,$val);
				$x++;
			}

			$y++;
		}

		//ENTREPRENEURSHIP
		#1 create main title.
		$sheetEntre = $excel->createSheet(1); 
		//$sheetTrain = $excel->getActiveSheet(2);
		$sheetEntre->setTitle("ENTREPRENEURSHIP");
		$sheetEntre->setCellValue("F1","ENTREPRENEURSHIP DATA");
		$sheetEntre->getStyle("F1")->getFont()->setSize(18);
		$sheetEntre->getRowDimension(1)->setRowHeight(25);
		$sheetEntre->getRowDimension(4)->setRowHeight(20);
		$sheetEntre->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheetEntre->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate)));
		$sheetEntre->getStyle("A2")->getFont()->setSize(16);
		$sheetEntre->getRowDimension(2)->setRowHeight(22);


		#3 main table.
		$startX	= "A";
		$startY	= 4;

		$x	= $startX;
		$y	= $startY;

		## create table header.
		$tableHeader	= Array(
					"No.",
					"RO",
					"State",
					"District",
					"UST",
					"CBC's Name",
					"SP",
					"Status",
					"Gender", # 2 split
					"",
					"Group", # 2 split (bumi, non bumi)
					"",
					"Age Group", # 2 split
					"",
					"",
					"",
					"Occupation", # 5 split
					"",
					"",
					"",
					"",
					""
								);



		foreach($tableHeader as $no=>$headerColname)
		{
			$sheetEntre->setCellValue($x.$y,$headerColname);

			## merge cells.
			if($x <= "H")
			{
				$sheetEntre->mergeCells($x."4:$x"."5");
			}

			if($x == "W"){
				$sheetEntre->mergeCells($x."4:$x"."5");
			}

			$x++;
		}

		## merge parents cells.
		foreach(Array("i4:j4", "k4:l4","m4:p4","q4:v4") as $coords)
		{
			$sheetEntre->mergeCells($coords);
		}

		$y++;

		## create subcolumn.
		# gender.
		$sheetEntre->setCellValue("k$y","Male");
		$sheetEntre->setCellValue("l$y","Female");

		$subcolumn	= Array(
					"i"=>"Male",
					"j"=>"Female",
					"k"=>"Bumi",
					"l"=>"Non Bumi",
					// "m"=>"Male",
					// "n"=>"Female",
					"m"=>"Under 18",
					"n"=>"18 - 35",
					"o"=>"36 - 55",
					"p"=>"Over 55",
					"q"=>"Students",
					"r"=>"Housewives",
					"s"=>"Self-employed",
					"t"=>"Employed",
					"u"=>"Not-employed",
					"v"=>"Retiree",
					"w"=>"Total Hours",
							);

		foreach($subcolumn as $subcolX=>$colname)
		{
			$sheetEntre->setCellValue($subcolX.$y,$colname);
		}
		$y++;

		## coloring.
		$headerStyle	= $sheetEntre->getStyle('A4:H5');
		$headerStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

		# color : reg member.
		$colors		= Array(
					"A4:H5"=>'FFFF00',
					"I4:J5"=>"00b0f0",
					"K4:L5"=>"ccc0d9",
					"M4:P5"=>"b8cce4",
					"Q4:V5"=>"fbd4b4",
					"W4:W5"=>"d6e3bc"
							);

		foreach($colors as $coords=>$color)
		{
			$sheetEntre->getStyle($coords)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
		}

		## all borders
		$allCells	= $sheetEntre->getStyle("A4:Z".(5+count($data['siteData'])));
		$allCells->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$allCells->getAlignment()->setWrapText(true);
		$allCells->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		## width dimensional width set.
		$widthR	= Array(
				"a"=>"5",
				"b"=>"10",
				"c"=>"20",
				"d"=>"20",
				"e"=>"20",
				"f"=>30,
				"g"=>8,
				"h"=>8,
				// "i"=>15,
				// "j"=>15,
				"i"=>8,
				"j"=>8,
				"k"=>13,
				"l"=>13,
				// "o"=>8,
				// "p"=>8,
				"m"=>10,
				"n"=>10,
				"o"=>10,
				"p"=>10,
				"q"=>10,
				"r"=>14,
				"s"=>14,
				"t"=>10,
				"u"=>14,
				"v"=>10,
				"w"=>10,
						);

		foreach($widthR as $widthX=>$width)
		{
			$sheetEntre->getColumnDimension($widthX)->setWidth($width);
		}

		#3.1 main loop.
		$no = 0;
		foreach($data['EsiteData'] as $siteID=>$row)
		{
			$siteInfo = $data['EsiteInfo'][$siteID];
			//var_dump($siteInfo);
			//die;
			$no++;
			$cellData = Array(
					"no"=>$no,
					"RO"=>"",
					"state"=>$row['stateName'],
					"district"=>$siteInfo['siteInfoDistrict'],
					"ust"=>$siteInfo['siteInfoUst'],
					"siteName"=>$row['siteName'],
					"sp"=>"Celcom",
					"status"=>"On Air",
					"gender_male"			=>$data['Egender'][$siteID]['male'],
					"gender_female"			=>$data['Egender'][$siteID]['female'],
					"group_bumi"			=>$data['Egroup'][$siteID]['bumi'],
					"group_nonbumi"			=>$data['Egroup'][$siteID]['non-bumi'],
					"age_18"				=>$data['EageRange'][$siteID]['under18'],
					"age_over18"			=>$data['EageRange'][$siteID]['18-35'],
					"age_over35"			=>$data['EageRange'][$siteID]['36-55'],
					"age_over55"			=>$data['EageRange'][$siteID]['over55'],
					"occ_students"			=>$data['Eoccupation'][$siteID]['students'],
					"occ_housewives"		=>$data['Eoccupation'][$siteID]['housewives'],
					"occ_selfemployed"		=>$data['Eoccupation'][$siteID]['self-employed'],
					"employed"				=>$data['Eoccupation'][$siteID]['employed'],
					"not-employed"			=>$data['Eoccupation'][$siteID]['not-employed'],
					"retiree"				=>$data['Eoccupation'][$siteID]['retiree'],
					"totalhours"			=>$data['EclassHour'][$siteID],
							);

			## reset.
			$x	= $startX;
			foreach($cellData as $key=>$val)
			{
				$sheetEntre->setCellValue($x.$y,$val);
				$x++;
			}

			$y++;
		}


		//TRAINING
		## the main working sheet.
		// $sheet	= $excel->getActiveSheet();
		// $excel->createSheet(1); 
		// $excel->getActiveSheet(1);
		// $excel->setTitle("Entrepreneurship");

		$sheetTrain = $excel->createSheet(2); 
		//$sheetTrain = $excel->getActiveSheet(2);

		// set all center and wrap
		$allCells = $sheetTrain->getStyle("A1:Z".(5+count($data['siteData'])));
		$allCells->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$allCells->getAlignment()->setWrapText(true);

		$sheetTrain->setTitle("Training");
		
		#1 create main title.
		$sheetTrain->setCellValue("A1","TRAINING DATA");
		$sheetTrain->mergeCells("A1:P1"); //merge from A1 to P1
		$sheetTrain->getStyle("A1")->getFont()->setSize(18);
		$sheetTrain->getRowDimension(1)->setRowHeight(25);
		$sheetTrain->getRowDimension(4)->setRowHeight(20);
		$sheetTrain->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheetTrain->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate)));
		$sheetTrain->mergeCells("A2:F2"); //merge from A2 to F2
		$sheetTrain->getStyle("A2")->getFont()->setSize(16);
		$sheetTrain->getRowDimension(2)->setRowHeight(22);		

		$tableHeaderTraining = Array(
				"SP",
				"Gender",
				"",
				"Group",
				"",
				"Age Group",
				"",
				"",
				"",
				"Occupation", # 5 split
				"",
				"",
				"",
				"",
				"",
				"Total class hours"
			);

		$subcolumnTraining	= Array(
					"B"=>"Male",
					"C"=>"Female",
					"D"=>"Bumi",
					"E"=>"Non Bumi",
					"F"=>"Under 18",
					"G"=>"18 - 35",
					"H"=>"36 - 55",
					"I"=>"Over 55",
					"J"=>"Students",
					"K"=>"Housewives",
					"L"=>"Self-employed",
					"M"=>"Employed",
					"N"=>"Not-employed",
					"O"=>"Retiree"
							);		

		
		//$yTraining = 1;
		$rowCountTraining = 4;

		//echo ++$xTraining;
		//die;

		//var_dump($data['TrainingInfo']);
		// die;

		// $headerStyle	= $sheetEntre->getStyle('A4:H5');
		// $headerStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

		// # color : reg member.
		// $colors		= Array(
		// 			"A4:H5"=>'FFFF00',
		// 			"I4:J5"=>"00b0f0",
		// 			"K4:L5"=>"ccc0d9",
		// 			"M4:P5"=>"b8cce4",
		// 			"Q4:V5"=>"fbd4b4",
		// 			"W4:W5"=>"d6e3bc"
		// 					);
		// foreach($colors as $coords=>$color)
		// {
		// 	$sheetEntre->getStyle($coords)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
		// }

		$TrainingInfo = $data['TrainingInfo'];
		foreach ($TrainingInfo as $keyTraining => $rowTraining) {
			$xTraining = 'A';
			$yTraining = 1;

			$sheetTrain->mergeCells("A".$rowCountTraining.":"."P".$rowCountTraining); //total
			$sheetTrain->setCellValue("A".$rowCountTraining, $rowTraining['trainingTypeName']);
			$headerStyle = $sheetTrain->getStyle('A'. $rowCountTraining .':' .'P'. $rowCountTraining);
			$headerStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF7FED');


			foreach ($tableHeaderTraining as $key => $valueHeader) {
				$sheetTrain->setCellValue($xTraining.($rowCountTraining+1), $valueHeader);
				++$xTraining;
				//$rowCountTraining++;
			}

			foreach ($subcolumnTraining as $key => $valueSubColumn) {
				$sheetTrain->setCellValue($key . ($rowCountTraining+2), $valueSubColumn);
				++$key;
			}

			$cellData = Array(
				"sp"=>"Celcom",
				"gender_male"			=>$data['Tgender'][$keyTraining]['male'],
				"gender_female"			=>$data['Tgender'][$keyTraining]['female'],
				"group_bumi"			=>$data['Tgroup'][$keyTraining]['bumi'],
				"group_nonbumi"			=>$data['Tgroup'][$keyTraining]['non-bumi'],
				"age_18"				=>$data['TageRange'][$keyTraining]['under18'],
				"age_over18"			=>$data['TageRange'][$keyTraining]['18-35'],
				"age_over35"			=>$data['TageRange'][$keyTraining]['36-55'],
				"age_over55"			=>$data['TageRange'][$keyTraining]['over55'],
				"occ_students"			=>$data['Toccupation'][$keyTraining]['students'],
				"occ_housewives"		=>$data['Toccupation'][$keyTraining]['housewives'],
				"occ_selfemployed"		=>$data['Toccupation'][$keyTraining]['self-employed'],
				"employed"				=>$data['Toccupation'][$keyTraining]['employed'],
				"not-employed"			=>$data['Toccupation'][$keyTraining]['not-employed'],
				"retiree"				=>$data['Toccupation'][$keyTraining]['retiree'],
				"total_hours"			=>$data['TclassHour'][$keyTraining],
			);

			$x	= 'A';
			foreach($cellData as $key=>$val)
			{
				$sheetTrain->setCellValue($x.($rowCountTraining+3),$val);
				++$x;
			}

			//all border
			$sheetTrain->getStyle('A'.($rowCountTraining+1).':P'.($rowCountTraining+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			//font size
			$sheetTrain->getStyle('A'.($rowCountTraining+1).':P'.($rowCountTraining+3))->getFont()->setSize(9);

			//merge SP with bottom cell
			$sheetTrain->mergeCells('A'.($rowCountTraining+1).':A'.($rowCountTraining+2));
			//color SP cell
			$sheetTrain->getStyle('A'.($rowCountTraining+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

			//merge Gender with right cell
			$sheetTrain->mergeCells('B'.($rowCountTraining+1).':C'.($rowCountTraining+1));
			//color Gender cell
			$sheetTrain->getStyle('B'.($rowCountTraining+1).':C'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCC0DA');

			//merge Group with right cell
			$sheetTrain->mergeCells('D'.($rowCountTraining+1).':E'.($rowCountTraining+1));
			//color Group cell
			$sheetTrain->getStyle('D'.($rowCountTraining+1).':E'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B8CCE4');

			//merge Age Group with right cell
			$sheetTrain->mergeCells('F'.($rowCountTraining+1).':I'.($rowCountTraining+1));
			//color Age Group cell
			$sheetTrain->getStyle('F'.($rowCountTraining+1).':I'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FDE9D9');

			//merge Accoupation Group with right cell
			$sheetTrain->mergeCells('J'.($rowCountTraining+1).':O'.($rowCountTraining+1));
			//color Accoupation cell
			$sheetTrain->getStyle('J'.($rowCountTraining+1).':O'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D8E4BC');

			//merge Total Class Hours with bottom cell
			$sheetTrain->mergeCells('P'.($rowCountTraining+1).':P'.($rowCountTraining+2));
			//color Total Class cell
			$sheetTrain->getStyle('P'.($rowCountTraining+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F2DCDB');

			$rowCountTraining +=5;
		}

		//SUBTYPE part
		$sheetSubTrain = $excel->createSheet(3); 
		//$sheetTrain = $excel->getActiveSheet(2);

		// set all center and wrap
		$allCells = $sheetSubTrain->getStyle("A1:Z".(5+count($data['siteData'])));
		$allCells->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$allCells->getAlignment()->setWrapText(true);

		$sheetSubTrain->setTitle("KDB");
		
		#1 create main title.
		$sheetSubTrain->setCellValue("A1","KDB DATA");
		$sheetSubTrain->mergeCells("A1:P1"); //merge from A1 to P1
		$sheetSubTrain->getStyle("A1")->getFont()->setSize(18);
		$sheetSubTrain->getRowDimension(1)->setRowHeight(25);
		$sheetSubTrain->getRowDimension(4)->setRowHeight(20);
		$sheetSubTrain->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheetSubTrain->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate)));
		$sheetSubTrain->mergeCells("A2:F2"); //merge from A2 to G2
		$sheetSubTrain->getStyle("A2")->getFont()->setSize(16);
		$sheetSubTrain->getRowDimension(2)->setRowHeight(22);		

		$tableHeaderSubTraining = Array(
				"SP",
				"Gender",
				"",
				"Group",
				"",
				"Age Group",
				"",
				"",
				"",
				"Occupation", # 5 split
				"",
				"",
				"",
				"",
				"",
				"Total class hours",
			);

		$subcolumnSubTraining	= Array(
					"B"=>"Male",
					"C"=>"Female",
					"D"=>"Bumi",
					"E"=>"Non Bumi",
					"F"=>"Under 18",
					"G"=>"18 - 35",
					"H"=>"36 - 55",
					"I"=>"Over 55",
					"J"=>"Students",
					"K"=>"Housewives",
					"L"=>"Self-employed",
					"M"=>"Employed",
					"N"=>"Not-employed",
					"O"=>"Retiree"
							);		

		
		//$yTraining = 1;
		$rowCountTraining = 4;

		//echo ++$xTraining;
		//die;

		$STrainingInfo = $data['STrainingInfo'];

		foreach ($STrainingInfo as $keyTraining => $rowTraining) {
			$xTraining = 'A';
			$yTraining = 1;
			$sheetSubTrain->mergeCells("A".$rowCountTraining.":"."P".$rowCountTraining); //total
			$sheetSubTrain->setCellValue("A".$rowCountTraining, $rowTraining['trainingTypeName']);

			foreach ($tableHeaderTraining as $key => $valueHeader) {
				$sheetSubTrain->setCellValue($xTraining . ($rowCountTraining+1), $valueHeader);
				++$xTraining;
				//$rowCountTraining++;
			}

			foreach ($subcolumnTraining as $key => $valueSubColumn) {
				$sheetSubTrain->setCellValue($key . ($rowCountTraining+2), $valueSubColumn);
				++$key;
			}

			$cellData = Array(
					"sp"=>"Celcom",
					"gender_male"			=>$data['STgender'][$keyTraining]['male'],
					"gender_female"			=>$data['STgender'][$keyTraining]['female'],
					"group_bumi"			=>$data['STgroup'][$keyTraining]['bumi'],
					"group_nonbumi"			=>$data['STgroup'][$keyTraining]['non-bumi'],
					"age_18"				=>$data['STageRange'][$keyTraining]['under18'],
					"age_over18"			=>$data['STageRange'][$keyTraining]['18-35'],
					"age_over35"			=>$data['STageRange'][$keyTraining]['36-55'],
					"age_over55"			=>$data['STageRange'][$keyTraining]['over55'],
					"occ_students"			=>$data['SToccupation'][$keyTraining]['students'],
					"occ_housewives"		=>$data['SToccupation'][$keyTraining]['housewives'],
					"occ_selfemployed"		=>$data['SToccupation'][$keyTraining]['self-employed'],
					"employed"				=>$data['SToccupation'][$keyTraining]['employed'],
					"not-employed"			=>$data['SToccupation'][$keyTraining]['not-employed'],
					"retiree"				=>$data['SToccupation'][$keyTraining]['retiree'],
					"total_hours"			=>$data['STclassHour'][$keyTraining],
							);

			## reset.
			$x	= 'A';
			foreach($cellData as $key=>$val)
			{
				$sheetSubTrain->setCellValue($x.($rowCountTraining+3),$val);
				++$x;
			}

			//all border
			$sheetSubTrain->getStyle('A'.($rowCountTraining+1).':P'.($rowCountTraining+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			//font size
			$sheetSubTrain->getStyle('A'.($rowCountTraining+1).':P'.($rowCountTraining+3))->getFont()->setSize(9);

			//merge SP with bottom cell
			$sheetSubTrain->mergeCells('A'.($rowCountTraining+1).':A'.($rowCountTraining+2));
			//color SP cell
			$sheetSubTrain->getStyle('A'.($rowCountTraining+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

			//merge Gender with right cell
			$sheetSubTrain->mergeCells('B'.($rowCountTraining+1).':C'.($rowCountTraining+1));
			//color Gender cell
			$sheetSubTrain->getStyle('B'.($rowCountTraining+1).':C'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCC0DA');

			//merge Group with right cell
			$sheetSubTrain->mergeCells('D'.($rowCountTraining+1).':E'.($rowCountTraining+1));
			//color Group cell
			$sheetSubTrain->getStyle('D'.($rowCountTraining+1).':E'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B8CCE4');

			//merge Age Group with right cell
			$sheetSubTrain->mergeCells('F'.($rowCountTraining+1).':I'.($rowCountTraining+1));
			//color Age Group cell
			$sheetSubTrain->getStyle('F'.($rowCountTraining+1).':I'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FDE9D9');

			//merge Accoupation Group with right cell
			$sheetSubTrain->mergeCells('J'.($rowCountTraining+1).':O'.($rowCountTraining+1));
			//color Accoupation cell
			$sheetSubTrain->getStyle('J'.($rowCountTraining+1).':O'.($rowCountTraining+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D8E4BC');

			//merge Total Class Hours with bottom cell
			$sheetSubTrain->mergeCells('P'.($rowCountTraining+1).':P'.($rowCountTraining+2));
			//color Total Class cell
			$sheetSubTrain->getStyle('P'.($rowCountTraining+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F2DCDB');

			$rowCountTraining +=5;
		}

		// $sheet->createSheet(1);
		// $sheet->setActiveSheetIndex(2);  
		// $sheet->getActiveSheet()->setCellValue('A4', 'Training') ;		

		$ExcelHelper->execute();
	}


	# gett all activity report -- root 
	public function monthlyActivityReport($year = null,$month = null, $category = null)
	{
		$categories = model::load("blog/category")->getCategoryList();

		$arr	= Array();
		if($categories)
			foreach($categories as $row) $arr[$row['categoryID']] = $row['categoryName'];

		$data['categories'] = $arr;
		$data['year'] = $year = $year ? : date("Y");
		$data['month'] = $month = $month ? : date("n");

		// check for non approved report.
		$data['totalApprovalPendingReport'] = model::load("blog/article")->getTotalApprovalPendingReport($year, $month, $category);
		$data['totalNonrecentReport'] = model::load('blog/article')->getTotalOfNonrecentReport($year, $month, $category);

		view::render("root/report/monthlyActivityReport",$data);
	}

	# all site report by month and year
	public function generateAllActivityReport($year = null,$month = null)
	{
		if($year == null)
		{
			$year = request::get('year',date("Y"));
			$month = request::get('month',date("n"));		
		}

		$reports	= model::load("blog/article")->getAllSiteReport($year,$month);

		if(count($reports) == 0)
		{
			redirect::to("report/getallActivityReport/$year/$month","Report Not Available","error");
		}

		$folderpath = path::files('reportGeneration/monthlyActivities/'.time(true));

		if(!is_dir($folderpath))
		mkdir($folderpath);

		foreach($reports as $no=>$report)
		{
		 	$dt = new DateTime($report['activityStartDate']);
			$date = $dt->format('dmY');
			$articleName =	$report['articleName'];
			$articleID = $report['articleID'];
			$articleText = $report['articleText'];
			$siteID = $report['siteID'];



			$siteInfo = model::load("site/site")->getSite($siteID);
			$siteName = $siteInfo['siteName'];
		
			$fileName = $siteName." - ".$date." - ".$articleName;

			$word	= new \PhpOffice\PhpWord\PhpWord();
	
			$monthNo = substr($date, 2, 2);  
			$monthNo = (int)$monthNo;
			$yearNo = substr($date, 4, 4);  
	
			$monthEvent =	model::load("helper")->monthYear("month",$monthNo);
			
			$title = "LAPORAN AKTIVITI PI1M ".$siteName." ".$monthEvent." ".$yearNo;
			
			$word->addTitleStyle('rStyle', array('bold' => true,  'size' => 11, 'allCaps' => true),array('align' => 'center'));
	
    		$section = $word->addSection();		
			$section->addTitle(htmlspecialchars($title), 'rStyle');
	
			$doc = new DOMDocument();
			$html = $articleText;	
			$finalArray = explode('</p>', $html);
			$lastValue = count($finalArray);
	
			foreach ($finalArray as $key => $value) {		
			
				@$doc->loadHTML($finalArray[$key]);
				$tags = $doc->getElementsByTagName('img');
			
					if ($tags->length == 0) {
			  
						if ($key == ($lastValue-1))
						{
							$finalHtml= $finalArray[$key];
							\PhpOffice\PhpWord\Shared\Html::addHtml($section, $finalHtml);
						}
						else
						{
							$finalHtml= $finalArray[$key]."</p>";	
							\PhpOffice\PhpWord\Shared\Html::addHtml($section, $finalHtml);
						}
					}
					else 
					{
						if ($key != ($lastValue-1)){
							foreach ($tags as $tag) {
				
			       				$imageLink = $tag->getAttribute('src');
			       				$section->addImage($imageLink, array('width' => 500));
							}
						}				
					}
			}

			$writer = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
			$writer->save($folderpath.'/'.$fileName.'.docx');
		}

		$path = opendir($folderpath);

		$zipfilename = 'MONTHLY ACTIVITES REPORT '.$month."-".$year.'.zip';
		$zippath = path::files('reportGeneration/monthlyActivities/tmp/'.$zipfilename);

		$myzip = new ZipArchive;

		$myzip->open($zippath, ZIPARCHIVE::CREATE);
		
		while($file = readdir($path))
		{
			if(in_array($file, array('.', '..')))
				continue;

			$filepath = refine_path($folderpath.'/'.$file);

			if(!file_exists($filepath))
			{
				
				continue;
			}

			$myzip->addFile($folderpath.'/'.$file,$file);
		}

		$myzip->close();

		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename="'.$zipfilename.'"');
		header('Content-Length: ' . filesize($zippath));
		readfile($zippath);
		//die;
	}

	public function showQuarterlyReportPage ($year = null, $quarter = null) {
		//view::render("root/report/quarterlyReport");

		$data['quarter']		= $quarter;
		$data['year']			= $year;
		

		view::render("root/report/dashboardQuarterlyReport", $data);
	}

}

?>
