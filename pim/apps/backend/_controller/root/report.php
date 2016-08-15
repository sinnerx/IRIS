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
				
				default:
					# code...
					break;
			}
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

	    $percentKPIMCMC = round($sumQuarterHours / $sumKPIMCMC * 100);
	    $percentKPINTSB = round($sumQuarterHours / $sumKPINTSB * 100);
		$pdf->Cell($w[0]+$w[1]+$w[2]+$w[3],7,'',1,0,'C');
		$pdf->Cell($w[4],7,'',1,0,'C');
	    $pdf->Cell($w[5],7,$percentKPIMCMC. '%',1,0,'C');
	    $pdf->Cell($w[6],7,$percentKPINTSB. '%',1,0,'C');
	    $pdf->Cell($w[7],7,'',1,0,'C');	    



		$pdf->Output();

		//view::render('root/report/dashboardReportGenerator');		
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
		$excel	= new \PHPExcel;
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
		$sheetTrain->setTitle("Training");
		
		#1 create main title.
		$sheetTrain->setCellValue("F1","TRAINING DATA");
		$sheetTrain->getStyle("F1")->getFont()->setSize(18);
		$sheetTrain->getRowDimension(1)->setRowHeight(25);
		$sheetTrain->getRowDimension(4)->setRowHeight(20);
		$sheetTrain->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheetTrain->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate)));
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
				"Total class hours",
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
			# code...
			//var_dump($rowTraining);
			//die;
			$xTraining = 'A';
			$yTraining = 1;
			$sheetTrain->mergeCells("A".$rowCountTraining.":"."P".$rowCountTraining); //total
			$sheetTrain->setCellValue("A".$rowCountTraining, $rowTraining['trainingTypeName']);
			$headerStyle	= $sheetTrain->getStyle('A'. $rowCountTraining .':' .'P'. $rowCountTraining);
			$headerStyle->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF7FED');			


			foreach ($tableHeaderTraining as $key => $valueHeader) {
				# code...
				$sheetTrain->setCellValue($xTraining . ($rowCountTraining+1), $valueHeader);
				# color : reg member.
				$colors		= Array(
							$xTraining . ($rowCountTraining+1) =>'FFFF00',
							($xTraining+1) . ($rowCountTraining+1) . ":" . ($xTraining+2) . ($rowCountTraining+1) => "ccc0d9",
							// "K4" . ":" . "L5"=>"00b0f0",
							// "M4:P5"=>"b8cce4",
							// "Q4:V5"=>"fbd4b4",
							// "W4:W5"=>"d6e3bc"
									);
				
				foreach($colors as $coords=>$color)
				{
					$sheetTrain->getStyle($coords)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
				}				

				++$xTraining;

				//$rowCountTraining++;
			}

			foreach ($subcolumnTraining as $key => $valueSubColumn) {
				# code...
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

			//var_dump($cellData);
			//die;
			## reset.
			$x	= 'A';
			foreach($cellData as $key=>$val)
			{
				$sheetTrain->setCellValue($x.($rowCountTraining+3),$val);
				++$x;
			}

			$rowCountTraining +=5;
		}

		//SUBTYPE part
		$sheetSubTrain = $excel->createSheet(3); 
		//$sheetTrain = $excel->getActiveSheet(2);
		$sheetSubTrain->setTitle("KDB");
		
		#1 create main title.
		$sheetSubTrain->setCellValue("F1","KDB DATA");
		$sheetSubTrain->getStyle("F1")->getFont()->setSize(18);
		$sheetSubTrain->getRowDimension(1)->setRowHeight(25);
		$sheetSubTrain->getRowDimension(4)->setRowHeight(20);
		$sheetSubTrain->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheetSubTrain->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate)));
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

		//var_dump($data['TrainingInfo']);
		// die;

		$STrainingInfo = $data['STrainingInfo'];
		foreach ($STrainingInfo as $keyTraining => $rowTraining) {
			# code...
			//var_dump($rowTraining);
			//die;
			$xTraining = 'A';
			$yTraining = 1;
			$sheetSubTrain->mergeCells("A".$rowCountTraining.":"."P".$rowCountTraining); //total
			$sheetSubTrain->setCellValue("A".$rowCountTraining, $rowTraining['trainingTypeName']);

			foreach ($tableHeaderTraining as $key => $valueHeader) {
				# code...
				$sheetSubTrain->setCellValue($xTraining . ($rowCountTraining+1), $valueHeader);
				++$xTraining;
				//$rowCountTraining++;
			}

			foreach ($subcolumnTraining as $key => $valueSubColumn) {
				# code...
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

			//var_dump($cellData);
			//die;
			## reset.
			$x	= 'A';
			foreach($cellData as $key=>$val)
			{
				$sheetSubTrain->setCellValue($x.($rowCountTraining+3),$val);
				++$x;
			}

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
