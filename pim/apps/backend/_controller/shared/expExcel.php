<?php

class Controller_ExpExcel
{


	public function getDailyCashProcess($siteID, $month, $year)
	{

		if(!((session::get("userLevel") == \model\user\user::LEVEL_ROOT) || (session::get("userLevel") == \model\user\user::LEVEL_CLUSTERLEAD)  || (session::get("userLevel") == \model\user\user::LEVEL_FINANCIALCONTROLLER))){
			//var_dump(session::get("userLevel"). " " . \model\user\user::LEVEL_CLUSTERLEAD);
			$siteID = authData('site.siteID');
		}
		

		//var_dump($siteID. " ". $month. " ". $year);
		//die;		
		$data['siteID'] = $siteID;

		$data['selectYear'] = $year ;//= request::get('selectYear', date('Y'));
		$data['selectMonth'] = $month;// = request::get('selectMonth', date('m'));

		$site = model::load('site/site')->getSite($siteID);
		// prepare all the required by codes.
		$codes = model::load('billing/item')->getItemCodes();

		// if(!$data['siteID'])
		// 	die;

		// get previous month balance.
		// list($previousYear, $previousMonth) = explode('-', date('Y-n', strtotime('-1 month', strtotime($year.'-'.$month.'-01'))));

		// sum of total for previous month.
		$previousTransaction = db::from('billing_transaction')
		->select('SUM(billingTransactionTotal) as total')
		->where('siteID', $data['siteID'])
		->where('billingTransactionDate <', $year.'-'.$month.'-01')
		->where('billingTransactionStatus', 1)
		->get()->row('total');

		if($previousTransaction)
			$data['balance'] = $previousTransaction;
		else
			$data['balance'] = 0;

		$billingItemCode = model::orm(array('billing_item_code', 'billingItemCodeID'))->execute();

		$billingItems = model::orm('billing/item')->execute();

		$startDate = $year.'-'.$month.'-01';
		
		$transactionItems = db::from('billing_transaction_item')
		->where('YEAR(billingTransactionDate)', $year)
		->where('MONTH(billingTransactionDate)', $month)
		->where('siteID', $data['siteID'])
		->where('billingTransactionStatus', 1)
		->join('billing_transaction', 'billing_transaction.billingTransactionID = billing_transaction_item.billingTransactionID', 'INNER JOIN')
		->join('billing_transaction_user', 'billing_transaction_user.billingTransactionID = billing_transaction.billingTransactionID')
		->join('billing_item_code', 'billing_item_code.billingItemID = billing_transaction_item.billingItemID')
		->get()->result();

		// Group by date, itemCOde by item codes.
		$report = array();

		foreach($transactionItems as $row)
		{
			$date = date('Y-m-d', strtotime($row['billingTransactionDate']));

			// if no code was configured for this item, set it to other.
			if($row['billingItemCodeName'])
				$code = $row['billingItemCodeName'];
			else
				$code = 'Other';

			// if age is lower than 18, OR occupation group = 1 (student), set it to student.
			if($row['billingTransactionUserAge'] < 18 || $row['billingTransactionUserOccupationGroup'] == 1)
				$userType = 'student';
			else
				$userType = 'adult';

			// if membership, status will require no member.
			if($code == 'Membership')
				$status = 'nonmember';
			else
				$status = $row['billingTransactionUser'] === 0 || !$row['billingTransactionUser'] ? 'nonmember' : 'member';

			$reference = &$report[$date][$code];

			// time
			$time = date('G', strtotime($row['billingTransactionDate'])) >= 19 ? 'night' : 'day';

			// point to the time
			if($code == 'PC')
			{
				if(!isset($reference[$time]))
					$reference[$time] = array();

				$reference = &$reference[$time];
			}

			// initiates.
			if(!isset($reference['total']))
				$reference['total'] = 0;

			if(!isset($reference['total_users']))
				$reference['total_users'] = 0;

			if(!isset($reference['total_quantity']))
				$reference['total_quantity'] = 0;

			if(!isset($reference[$userType][$status]['total']))
				$reference[$userType][$status]['total'] = 0;

			if(!isset($report[$date]['total']))
				$report[$date]['total'] = 0;

			// set.
			$transactionItemTotal = $row['billingTransactionItemPrice'] * $row['billingTransactionItemQuantity'];
			$reference[$userType][$status]['total'] += $transactionItemTotal;
			$reference['total'] += $transactionItemTotal;
			$reference['total_quantity'] += $row['billingTransactionItemQuantity'];
			$reference[$userType][$status]['total_quantity'] += $row['billingTransactionItemQuantity'];

			$report[$date]['total'] += $transactionItemTotal;
		}

		$data['report'] = $report;

		//var_dump($data['report']['2016-05-02']);
		//die;

		$filename	= "Daily Cash Process - ". $site['siteName'] ." - ". date("F",strtotime("2014-$month-01"))." $year";

		$excel	= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xls");

		## the main working sheet.
		$sheet	= $excel->getActiveSheet();

		## prepare report.

		# prepare header.

		//first row header
		$sheet->setCellValue("A1", "Day");
		$sheet->mergeCells("A1:A3");

		$sheet->mergeCells("B1:G1"); //member
		$sheet->setCellValue("B1","Member");
		
		$sheet->mergeCells("H1:V1"); //pc day
		$sheet->setCellValue("H1","PC Day");

		$sheet->mergeCells("W1:AK1"); //pc night
		$sheet->setCellValue("W1","PC Night");

		$sheet->mergeCells("AL1:AN1"); //print
		$sheet->setCellValue("AL1","Print");

		$sheet->setCellValue("AO1","Scan");

		$sheet->setCellValue("AP1","Laminate");

		$sheet->mergeCells("AQ1:AS1"); //other
		$sheet->setCellValue("AQ1","Other");

		$sheet->mergeCells("AT1:AU1"); //day end
		$sheet->setCellValue("AT1","Day End");


		//second row header
		$sheet->mergeCells("B2:C2"); //total
		$sheet->setCellValue("B2","Total");

		$sheet->mergeCells("D2:E2"); //student
		$sheet->setCellValue("D2","Student");

		$sheet->mergeCells("F2:G2"); //adult
		$sheet->setCellValue("F2","Adult");		


		$sheet->mergeCells("H2:J2"); //total
		$sheet->setCellValue("H2","Total");

		$sheet->mergeCells("K2:M2"); //student
		$sheet->setCellValue("K2","Student Member");

		$sheet->mergeCells("N2:P2"); //
		$sheet->setCellValue("N2","Student NonMember");	

		$sheet->mergeCells("Q2:S2"); //
		$sheet->setCellValue("Q2","Adult Member");

		$sheet->mergeCells("T2:V2"); //
		$sheet->setCellValue("T2","Adult NonMember");			


		$sheet->mergeCells("W2:Y2"); //total
		$sheet->setCellValue("W2","Total");

		$sheet->mergeCells("Z2:AB2"); //student
		$sheet->setCellValue("Z2","Student Member");

		$sheet->mergeCells("AC2:AE2"); //adult
		$sheet->setCellValue("AC2","Student NonMember");	

		$sheet->mergeCells("AF2:AH2"); //student
		$sheet->setCellValue("AF2","Adult Member");

		$sheet->mergeCells("AI2:AK2"); //adult
		$sheet->setCellValue("AI2","Adult NonMember");

		$sheet->setCellValue("AL2","Total");
		$sheet->setCellValue("AM2","B/W");
		$sheet->setCellValue("AN2","Color");	


		//third row header
		$sheet->setCellValue("B3","RM");
		$sheet->setCellValue("C3","User");

		$sheet->setCellValue("D3","RM");
		$sheet->setCellValue("E3","User");

		$sheet->setCellValue("F3","RM");
		$sheet->setCellValue("G3","User");	
		
		$sheet->setCellValue("H3","RM");
		$sheet->setCellValue("I3","User");					
		$sheet->setCellValue("J3","Hr");					

		$sheet->setCellValue("K3","RM");
		$sheet->setCellValue("L3","User");					
		$sheet->setCellValue("M3","Hr");					

		$sheet->setCellValue("N3","RM");
		$sheet->setCellValue("O3","User");					
		$sheet->setCellValue("P3","Hr");					

		$sheet->setCellValue("Q3","RM");
		$sheet->setCellValue("R3","User");					
		$sheet->setCellValue("S3","Hr");					

		$sheet->setCellValue("T3","RM");
		$sheet->setCellValue("U3","User");					
		$sheet->setCellValue("V3","Hr");					

		$sheet->setCellValue("W3","RM");
		$sheet->setCellValue("X3","User");					
		$sheet->setCellValue("Y3","Hr");					

		$sheet->setCellValue("Z3","RM");
		$sheet->setCellValue("AA3","User");					
		$sheet->setCellValue("AB3","Hr");					

		$sheet->setCellValue("AC3","RM");
		$sheet->setCellValue("AD3","User");					
		$sheet->setCellValue("AE3","Hr");					

		$sheet->setCellValue("AF3","RM");
		$sheet->setCellValue("AG3","User");					
		$sheet->setCellValue("AH3","Hr");					

		$sheet->setCellValue("AI3","RM");
		$sheet->setCellValue("AJ3","User");					
		$sheet->setCellValue("AK3","Hr");					

		$sheet->setCellValue("AL3","RM");
		$sheet->setCellValue("AM3","RM");
		$sheet->setCellValue("AN3","RM");
		$sheet->setCellValue("AO3","RM");
		$sheet->setCellValue("AP3","RM");
		$sheet->setCellValue("AQ3","RM");

		$sheet->setCellValue("AR3","Utilities");
		$sheet->setCellValue("AS3","Description");
		$sheet->setCellValue("AT3","Total");				
		$sheet->setCellValue("AU3","Balance");				
		// foreach($cellHeader as $no=>$headerColname)
		// {
		// 	$sheet->setCellValue($currX.$currY,$headerColname);

		// 	## merge cells, except QPO.
		// 	if(!in_array($currX,Array("O","P","Q")))
		// 		$sheet->mergeCells($currX.$currY.":".$currX.($currY+3));
			
		// 	$currX++;
		// }

		// ## set performance columns.
		// $sheet->setCellValue("O3","Total Faulty Equipments");
		// $sheet->setCellValue("P3","Status (Pending/Resolved)");
		// $sheet->setCellValue("Q3","Description");


		// $allCells->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// $allCells->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		// $allCells->getAlignment()->setWrapText(true);
		// $allCells->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->setCellValue("B4", "Monthly Revenue (Previous Balance)");
		$sheet->setCellValue("AU4", $this->floatVal($data['balance']));
		
		$startDataX = "A";
		$startDataY = "5";
		//$balance = 0;
		$sum = 0;
		$totals = array();
		
		$floatArray = array(
			"B","D","F","H","K","N","Q","T","W","Z","AC","AF", "AI", "AL","AM","AN","AO","AP","AQ","AT","AU",
			);

		foreach(range(1, date('t', strtotime($year.'-'.$month.'-01'))) as $day){
			$date = $year.'-'.$month.'-'.$day;
			$date = date('Y-m-d', strtotime($date));
			$no = 0;
		
			$sheet->setCellValueExplicit($startDataX . $startDataY, date('d', strtotime($date)), PHPExcel_Cell_DataType::TYPE_STRING);
			
			//var_dump($this->floatVal($data['balance']));
			$sum +=	$report[$date]['total'];
			$balance = $data['balance'] + $sum;

			$totals[$no++] = 	"Total";
			$totals[$no++] += 	$this->floatVal($report[$date]['Membership']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['Membership']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['Membership']['student']['nonmember']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['Membership']['student']['nonmember']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['Membership']['adult']['nonmember']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['Membership']['adult']['nonmember']['total_users']);

			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['day']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['student']['member']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['day']['student']['member']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['student']['member']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['student']['nonmember']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['day']['student']['nonmember']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['student']['nonmember']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['adult']['member']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['day']['adult']['member']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['adult']['member']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['adult']['nonmember']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['day']['adult']['nonmember']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['day']['adult']['nonmember']['total_quantity']);			

			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['night']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['student']['member']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['night']['student']['member']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['student']['member']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['student']['nonmember']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['night']['student']['nonmember']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['student']['nonmember']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['adult']['member']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['night']['adult']['member']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['adult']['member']['total_quantity']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['adult']['nonmember']['total']);
			$totals[$no++] +=	$this->totalVal($report[$date]['PC']['night']['adult']['nonmember']['total_users']);
			$totals[$no++] +=	$this->floatVal($report[$date]['PC']['night']['adult']['nonmember']['total_quantity']);
				
			$totals[$no++] +=	$this->floatVal(($report[$date]['Print Color']['total'] ? : 0) + ($report[$date]['Black And White']['total'] ? : 0));
			$totals[$no++] +=	$this->floatVal($report[$date]['Print Color']['total']);
			$totals[$no++] +=	$this->floatVal($report[$date]['Black And White']['total']);

			$totals[$no++] +=	$this->floatVal($report[$date]['Scan']['total']);
			$totals[$no++] +=	$this->floatVal($report[$date]['Laminate']['total']);

			$totals[$no++] +=	$this->floatVal($report[$date]['Other']['total']);

			$totals[$no++] =	"";
			$totals[$no++] =	"";
			$totals[$no++] +=	$this->floatVal($report[$date]['total']);

			$totals[$no++] =	$this->floatVal($balance);



			$rowArray = array(
				$this->floatVal($report[$date]['Membership']['total']),
				$this->totalVal($report[$date]['Membership']['total_users']),
				$this->floatVal($report[$date]['Membership']['student']['nonmember']['total']),
				$this->totalVal($report[$date]['Membership']['student']['nonmember']['total_users']),
				$this->floatVal($report[$date]['Membership']['adult']['nonmember']['total']),
				$this->totalVal($report[$date]['Membership']['adult']['nonmember']['total_users']),

				$this->floatVal($report[$date]['PC']['day']['total']),
				$this->totalVal($report[$date]['PC']['day']['total_users']),
				$this->floatVal($report[$date]['PC']['day']['total_quantity']),
				$this->floatVal($report[$date]['PC']['day']['student']['member']['total']),
				$this->totalVal($report[$date]['PC']['day']['student']['member']['total_users']),
				$this->floatVal($report[$date]['PC']['day']['student']['member']['total_quantity']),
				$this->floatVal($report[$date]['PC']['day']['student']['nonmember']['total']),
				$this->totalVal($report[$date]['PC']['day']['student']['nonmember']['total_users']),
				$this->floatVal($report[$date]['PC']['day']['student']['nonmember']['total_quantity']),
				$this->floatVal($report[$date]['PC']['day']['adult']['member']['total']),
				$this->totalVal($report[$date]['PC']['day']['adult']['member']['total_users']),
				$this->floatVal($report[$date]['PC']['day']['adult']['member']['total_quantity']),
				$this->floatVal($report[$date]['PC']['day']['adult']['nonmember']['total']),
				$this->totalVal($report[$date]['PC']['day']['adult']['nonmember']['total_users']),
				$this->floatVal($report[$date]['PC']['day']['adult']['nonmember']['total_quantity']),			

				$this->floatVal($report[$date]['PC']['night']['total']),
				$this->totalVal($report[$date]['PC']['night']['total_users']),
				$this->floatVal($report[$date]['PC']['night']['total_quantity']),
				$this->floatVal($report[$date]['PC']['night']['student']['member']['total']),
				$this->totalVal($report[$date]['PC']['night']['student']['member']['total_users']),
				$this->floatVal($report[$date]['PC']['night']['student']['member']['total_quantity']),
				$this->floatVal($report[$date]['PC']['night']['student']['nonmember']['total']),
				$this->totalVal($report[$date]['PC']['night']['student']['nonmember']['total_users']),
				$this->floatVal($report[$date]['PC']['night']['student']['nonmember']['total_quantity']),
				$this->floatVal($report[$date]['PC']['night']['adult']['member']['total']),
				$this->totalVal($report[$date]['PC']['night']['adult']['member']['total_users']),
				$this->floatVal($report[$date]['PC']['night']['adult']['member']['total_quantity']),
				$this->floatVal($report[$date]['PC']['night']['adult']['nonmember']['total']),
				$this->totalVal($report[$date]['PC']['night']['adult']['nonmember']['total_users']),
				$this->floatVal($report[$date]['PC']['night']['adult']['nonmember']['total_quantity']),
				
				$this->floatVal(($report[$date]['Print Color']['total'] ? : 0) + ($report[$date]['Black And White']['total'] ? : 0)),
				$this->floatVal($report[$date]['Print Color']['total']),
				$this->floatVal($report[$date]['Black And White']['total']),

				$this->floatVal($report[$date]['Scan']['total']),
				$this->floatVal($report[$date]['Laminate']['total']),

				$this->floatVal($report[$date]['Other']['total']),

				"",
				"",

				$this->floatVal($report[$date]['total']),

				$this->floatVal($balance),				
				);

			//$columnArray = array_chunk($rowArray, 1);

			$sheet->fromArray( 
				$rowArray,   // The data to set
        		NULL,           // Array values with this value will not be set
        		'B'.$startDataY,            // Top left coordinate of the worksheet range where
             	true          			 //    we want to set these values (default is A1))
        	);

        	$sheet->getStyle('B'.$startDataY.":AU".$startDataY)
		    ->getNumberFormat()
		    ->setFormatCode(
		        PHPExcel_Style_NumberFormat::FORMAT_NUMBER
		    );

		// 	## merge cells, except QPO.

		 	foreach ($floatArray as $key => $value) {
		 		# code...
		 		$sheet->getStyle($value.$startDataY)
			    ->getNumberFormat()
			    ->setFormatCode(
			        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			    );		 		
		 	}
        	$startDataY++;
		}	

		//var_dump($totals);
		//var_dump($startDataY);

		$sheet->fromArray(
			$totals, NULL, 'A'.($startDataY), true
			);			

		 	foreach ($floatArray as $key => $value) {
		 		# code...
		 		$sheet->getStyle($value.$startDataY)
			    ->getNumberFormat()
			    ->setFormatCode(
			        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			    );
		 	}


		$ExcelHelper->execute();			
	}

	public function prCashAdvanceDownload($prID)
	{
		$pr = orm('expense/pr/pr')->find($prID);

		if(!$pr->isClosed())
			return 'PR must be closed.';

		$ca = $pr->getCashAdvance();

		$excel = \PHPExcel_IOFactory::createReader('Excel2007');
		$excel = $excel->load(path::files('expense/cash-advance-form.xlsx'));
		$ExcelHelper	= new model\report\PHPExcelHelper($excel, "Cash Advance Form ".$pr->prNumber.".xls");

		$sheet = $excel->getActiveSheet();
		$user = $pr->getRequestingUser();

		$sheet->setCellValue('D4', $user->getProfile()->userProfileFullName); // applicant name
		$sheet->setCellValue('D6', 'Manager '.$pr->getSite()->siteName); // microsite

		$sheet->getCell('B9')->setValue($ca->prCashAdvancePurpose); // purpose

		$itemY = 16;

		$categoriedItems = array();

		$categories = orm('expense/expense_category')->execute()->toList('expenseCategoryID', 'expenseCategoryName');

		foreach($pr->getItems() as $item)
			$categoriedItems[$item->expenseCategoryID][] = $item;

		foreach($categoriedItems as $categoryID => $items)
		{
			$cell = $sheet->getCell('B'.$itemY);
				$cell->getStyle()->getFont()->setBold(true)->setUnderline(true);
			$cell->setValue($categories[$categoryID]);
			$itemY++;

			foreach($items as $item)
			{
				$sheet->setCellValue('B'.$itemY, $item->expenseItemName);
				$sheet->setCellValue('I'.$itemY, $item->prItemTotal);

				$itemY++;
			}
		}

		// Project Director signature
		/*$lizaSignature = imagecreatefromjpeg(path::files('expense/liza-signature.jpg'));

		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setImageResource($lizaSignature);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setWidth(160);
		$objDrawing->setHeight($objDrawing->getHeight()-30);
		$objDrawing->setWorksheet($sheet);
		$objDrawing->setCoordinates('I32');
		// $objDrawing->setOffsetX($objDrawing->getOffsetX()+30);
		$objDrawing->setOffsetX(10);
		$objDrawing->setOffsetY(10);*/

		$sheet->setCellValue('B29', 'Ringgit Malaysia : '.$ca->prCashAdvanceAmount);

		$sheet->setCellValue('C33', $user->getProfile()->userProfileFullName);

		$sheet->setCellValue('B35', 'Date : '.date('d-m-Y', strtotime($pr->prCreatedDate)));

		$omApproval = $pr->getLevelApproval('om');
		$sheet->setCellValue('H35', 'Date : '.date('d-m-Y', strtotime($omApproval->prApprovalUpdatedDate))); 

		$ExcelHelper->execute();
	}

	//CHANGE HERE TO  RL able to be downloaded with full imformation once OM verified, thus easier to Finance verify it in 2 method, Hardcopy and on the system.
	public function rlSummaryGenerate($clusterID, $prType, $month, $year)
	{
		//var_dump($clusterID);
		$time = microtime(true);

		$cluster = orm('site/cluster')->find($clusterID);

		$monthName = date('F', strtotime('2015-'.$month.'-01'));

		// get rls
		$rlList = orm('expense/pr/reconcilation/reconcilation')
		// ->where('prReconcilationStatus', 1)
		->where('prReconcilationApprovalLevel >= 4')
		->where('pr.prType = ? AND pr.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = ?)', array($prType, $clusterID))
		//->where('MONTH(prReconcilationSubmittedDate)', $month)
		//->where('YEAR(prReconcilationSubmittedDate)', $year)
		->where('MONTH(prDate)', $month)
		->where('YEAR(prDate)', $year)
		->join('pr', 'pr.prID = pr_reconcilation.prID')
		->execute();

		$types = array(
			1 => 'Collection Money',
			2 => 'Cash Advance'
			);

		$filename = 'Pi1M '.$cluster->clusterName.' '.$types[$prType].' '.$monthName.'-'.$year;

		/**
		 * Functions
		 */
		$line = function(&$y, $x = 'A')
		{
			return $x.$y++;
		};

		$pos = function($x, $y)
		{
			return $x.$y;
		};

		$highlight = function($sheet, $pos, $boldOnly = false)
		{
			$font = $sheet->getStyle($pos)->getFont();

			if(!$boldOnly)
				$font->setUnderline(true);

			$font->setBold(true);
		};

		$background = function($sheet, $pos, $color)
		{
			$sheet->getStyle($pos)->applyFromArray(
			    array
			    (
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => $color)
			        )
			    )
			);
		};

		$setBorder = function($sheet, $range, $type, $thick = false)
		{
			$borders = $sheet->getStyle($range)->getBorders();

			$method = 'get'.ucwords($type);
			// $line = $type == 'outline' ? $borders->getOutline() : $borders->getAllBorders();
			$line = $borders->$method();

			if($thick)
				$line->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			else
				$line->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		};

		$setAlign = function($sheet, $range, $type)
		{
			$types = array(
				'left' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'center' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'right' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
				);

			$sheet->getStyle($range)
			->getAlignment()
			->setHorizontal($types[$type]);
		};

		// var_dump($cluster->getOps($clusterID));
		// die;
		$clusterArray = $cluster->getOps($clusterID);
		$opsName = $clusterArray['name'];
		//var_dump($opsName);
		//die;

		if($clusterArray['title'] == "Operation Semenanjung Malaysia")
			$SignatureImg = imagecreatefromjpeg(path::files('expense/saiful-signature.jpg'));
		else if ($clusterArray['title'] == "Operation East Malaysia")
			$SignatureImg = imagecreatefromjpeg(path::files('expense/diana-signature.jpg'));

		$signatureAdd = function($sheet, &$y, &$opsName = null, &$SignatureImg = null)
		{
			//var_dump($opsName);
			//die;
			// signature.
			$sheet->setCellValue('B'.$y, 'Acknowledged/Approved By:');
			$sheet->setCellValue('D'.$y, 'Checked by:');

			$y += 4;

			// $saifulSignature = imagecreatefromjpeg(path::files('expense/saiful-signature.jpg'));
			 $lizaSignature = imagecreatefromjpeg(path::files('expense/liza-signature.jpg'));

			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			// var_dump($clusterID);
			// die;
			// if($cluster->getOps($clusterID) == "Operation Semenanjung Malaysia")
				$objDrawing->setImageResource($SignatureImg);
			// else if ($cluster->getOps($clusterID) == "Operation East Malaysia")
				// $objDrawing->setImageResource($lizaSignature);

			$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setWidth(160);
			$objDrawing->setWorksheet($sheet);
			$objDrawing->setCoordinates('B'.($y-2));
			// $objDrawing->setOffsetX($objDrawing->getOffsetX()+30);
			$objDrawing->setOffsetX(10);
			$objDrawing->setOffsetY(10);

			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setImageResource($lizaSignature);
			$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setWidth(160);
			$objDrawing->setHeight($objDrawing->getHeight()-10);
			$objDrawing->setWorksheet($sheet);
			$objDrawing->setCoordinates('D'.($y-3));
			// $objDrawing->setOffsetX($objDrawing->getOffsetX()+30);
			$objDrawing->setOffsetX(10);
			$objDrawing->setOffsetY(10);

			$sheet->setCellValue('B'.($y), '_______________________');
			$sheet->setCellValue('D'.($y), '_______________________');

			$y++;

			$sheet->setCellValue('B'.$y, $opsName);
			$sheet->setCellValue('D'.$y, 'Yusliza Mad Yusop');

			$y++;

			$sheet->setCellValue('B'.$y, 'Operation Manager');
			$sheet->setCellValue('D'.$y, 'Operation Coordinator');
		};
		/**
		 * Functions ends.
		 */

		$excel	= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xls");

		$sheets = array();

		/**********************
		 * FIRST SHEET
		 **********************/
		$sheet = $sheets[0] = $excel->getSheet(0);
		$sheet->setTitle('Reconcilation List');

		$y = 11;

		// <> Cluster and date informaation
		$sheet->getCell("A$y")->setValue('RECONCILIATION LIST - FOR THE PURPOSE OF Pi1M\'s MONTHLY EXPENSES')->getStyle()->getFont()->setBold(true);
			$sheet->mergeCells("A$y:G$y");
			$y += 2;
		$sheet->setCellValue("A$y", 'Cluster : '.$cluster->clusterName);
			$sheet->mergeCells("A$y:B$y");
			$y++;
		$sheet->setCellValue("A$y", 'Month : '.$monthName.' '.$year);
			$sheet->mergeCells("A$y:B$y");
			$y += 2;

		// <> Table Header
		$headers = array('No', 'PR Date', 'PR No', 'Particular', 'Amount', 'GST', 'Total');

		$x = 'A';
		foreach($headers as $text)
		{
			if($text == 'Particular')
			{
				$sheet->mergeCells("$x$y:E$y");
				$sheet->setCellValue($pos($x++, $y), $text);
				$x++;
				continue;
			}
			$sheet->setCellValue($pos($x++, $y), $text);
		}

		$highlight($sheet, "A$y:H$y", true);

		// color header to blue
		$backgrounds[] = array($sheet, "A$y:H$y", '93CDDD');

		$headersStyle = $sheet->getStyle("A$y:H$y");
		$headersStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$headersStyle->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$y++;

		$expenseCategories = orm('expense/expense_category')->execute()->toList('expenseCategoryID', 'expenseCategoryName');

		// RL Loop
		$rlCategoryTotals = array();
		$allRlTotal = 0;
		$no = 1;
		$loopFirstY = $y;

		// site based pr.
		$rlSites = array();
		$sites = array();

		// summary by category (expenseCategoryID)
		$rlCategorySummary = array();

		foreach($rlList as $rl)
		{
			$x = 'A';

			$firstY = $y;

			$date = date('d/m/Y', strtotime($rl->prDate));
			$prNo = $rl->prNumber;
			$pr = $rl->getPr();
			$site = $pr->getSite();
			$siteName = $site->siteName;

			// cache the site first.
			$sites[$site->siteID] = $site;

			$rlSites[$site->siteID][] = $rl;

			// $prItems = orm('expense/pr/item')->where('prID', $rl->prID)->execute();
			$prItems = $rlItems = orm('expense/pr/reconcilation/item')
			->join('expense_item', 'expense_item.expenseItemID = pr_reconcilation_item.expenseItemID')
			->where('prReconcilationItemStatus', 1) // reconciled
			->where('prReconcilationID', $rl->prReconcilationID)->execute();

			// <> Lirst loop
			$sheet->setCellValue($pos('A', $y), $no++);
			$sheet->setCellValue($pos('B', $y), $date);
			$sheet->setCellValue($pos('C', $y), $prNo);
			$sheet->setCellValue($pos('D', $y), $siteName);
				$highlight($sheet, 'D'.$y);
			$sheet->mergeCells("D$y:E$y");
			$y++;

			$rlCategoryItems = array();
			$rlCategoryNames = array();

			// Prepare pr_items grouped by categories
			foreach($rlItems as $rlItem)
			{
				if(!isset($rlCategoryItems[$rlItem->prReconcilationCategoryID]))
					$rlCategoryItems[$rlItem->prReconcilationCategoryID] = array();

				$rlCategoryItems[$rlItem->prReconcilationCategoryID][] = $rlItem;

				if(!isset($rlCategoryTotals[$rlItem->prReconcilationCategoryID]))
					$rlCategoryTotals[$rlItem->prReconcilationCategoryID] = 0;

				if(!isset($rlCategoryNames[$rlItem->prReconcilationCategoryID]))
					$rlCategoryNames[$rlItem->prReconcilationCategoryID] = $expenseCategories[$rlItem->expenseCategoryID];

				$rlCategoryTotals[$rlItem->prReconcilationCategoryID] += $rlItem->prReconcilationItemTotal;
			}

			// <> Item loops.
			$rlItemTotal = 0;

			$rlAmountTotal = 0;

			$rlGstTotal = 0;

			foreach($rlCategoryItems as $prReconcilationCategoryID => $rlItems)
			{
				$rlCategoryTotal = 0;
				$categoryName = $rlCategoryNames[$prReconcilationCategoryID];

				$sheet->setCellValue($pos('D', $y), $categoryName); // categoryName
					$sheet->mergeCells("D$y:E$y");
				$highlight($sheet, 'D'.$y);

				foreach($rlItems as $rlItem)
					$rlCategoryTotal += $rlItem->prReconcilationItemTotal;

				// $sheet->setCellValue($pos('G', $y), $rlCategoryTotal);  // total amount
				$highlight($sheet, 'G'.$y, true);
				$y++;

				foreach($rlItems as $rlItem)
				{
					$num = strtolower(numberToRoman($no ? 1 : $no++));

					// prepare intermediate variables
					$amountTotal = $rlItem->prReconcilationItemAmount;
					$gstTotal = $rlItem->prReconcilationItemGst;
					$itemTotal = $rlItem->prReconcilationItemTotal;

					$rlItemTotal += $rlItem->prReconcilationItemTotal;
					$rlAmountTotal += $rlItem->prReconcilationItemAmount;
					$rlGstTotal += $rlItem->prReconcilationItemGst;

					$sheet->setCellValue($pos('D', $y), $num.'. '.$rlItem->prReconcilationItemName); // item name
					$sheet->setCellValue($pos('F', $y), $amountTotal); // sub total
					$sheet->setCellValue($pos('G', $y), $gstTotal); // gst total
					$sheet->setCellValue($pos('H', $y), $itemTotal); // item total

					$allGstTotal += $gstTotal;
					$allAmountTotal += $amountTotal;
					$allRlTotal += $itemTotal;

					$y++;
					
					// save rlCategorySummary
					if(!isset($rlCategorySummary[$rlItem->expenseCategoryID]))
					{
						$rlCategorySummary[$rlItem->expenseCategoryID] = array(
							'amount' => 0,
							'gst' => 0,
							'total' => 0
							);
					}

					$rlCategorySummary[$rlItem->expenseCategoryID]['amount'] += $amountTotal;
					$rlCategorySummary[$rlItem->expenseCategoryID]['gst'] += $gstTotal;
					$rlCategorySummary[$rlItem->expenseCategoryID]['total'] += $itemTotal;
				}



				// $allRlTotal += $rlCategoryTotal;
			}

			// total ammount.
			// <> RL total
			$sheet->setCellValue("F$y", $rlAmountTotal);
			$sheet->setCellValue("G$y", $rlGstTotal);
			$sheet->setCellValue("H$y", $rlItemTotal);
			$backgrounds[] = array($sheet, "F$y:H$y", "FFFF00");
			$highlight($sheet, 'F'.$y, true);
			$highlight($sheet, 'G'.$y, true);
			$highlight($sheet, 'H'.$y, true);
			$setBorder($sheet, "F$y:H$y", 'allBorders');

			$endY = $y;

			// give border to each big column
			$x = 'A';
			foreach(range(1, 8) as $xNo)
			{
				$range = "$x$firstY:$x$endY";

				if($xNo == 4)
				{
					$range = "$x$firstY:E$endY";
					$x++;
				}

				if($xNo == 5)
					continue;

				$setBorder($sheet, $range, 'outline');

				$x++;
			}
			$y++;
		}

		// alignment setting
		$sheet->getStyle("A$loopFirstY:".'C'.$y)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		// total amount.
		$y += 2;

		$sheet->setCellValue("E$y", 'Grand Amount ');
			$sheet->getStyle("E$y")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$sheet->setCellValue("F$y", $allAmountTotal);
		$sheet->setCellValue("G$y", $allGstTotal);
		$sheet->setCellValue("H$y", $allRlTotal);
		$setBorder($sheet, "F$y:H$y", 'allBorders', true);

		$y += 2;

		$signatureAdd($sheet, $y, $opsName, $SignatureImg);

		// background-color the whole sheet.
		$sheet->getStyle('A1:'."H$y")->applyFromArray(
		    array
		    (
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);



		/***********************************
		 * SECOND SHEET : SUMMARY
		 ***********************************/
		$sheet = $sheets[1] = $excel->createSheet(1);

		$sheet->setTitle('Summary');

		$y = 11;


		// <> info
		$sheet->getCell("A$y")->setValue('RECONCILIATION LIST - Summary')->getStyle()->getFont()->setBold(true);
		$sheet->setCellValue("A$y", 'RECONCILIATION LIST - Summary');
			$sheet->mergeCells("A$y:G$y");
			$y += 2;

		$sheet->setCellValue("A$y", 'Cluster : '.$cluster->clusterName);
			$sheet->mergeCells("A$y:B$y");
			$y++;
		$sheet->setCellValue("A$y", 'Month : '.$monthName.' '.$year);
			$sheet->mergeCells("A$y:B$y");
			$y += 2;

		// <> table header
		$firstY = $y;

		$sheet->setCellValue("A$y", 'No.');
		$sheet->setCellValue("B$y", "Particular");
			$sheet->mergeCells("B$y:D$y");

		$sheet->setCellValue("E$y", 'Amount (RM)');
		$sheet->setCellValue("F$y", 'Gst 6% (RM)');
		$sheet->setCellValue("G$y", 'Total (RM)');

		$setAlign($sheet, "E$y:G$y", 'center');

		$highlight($sheet, "A$y:G$y", true); // bold

		// color header to blue
		$backgrounds[] = array($sheet, "A$y:G$y", '93CDDD');

		$y++;

		// <> RL category summaries
		$no = 1;
		$categoryY = $y;

		foreach($expenseCategories as $expenseCategoryID => $name)
		{
			$summary = $rlCategorySummary[$expenseCategoryID];

			$sheet->setCellValue('A'.$y, ($no ? $no++ : 1).'.');
			$sheet->setCellValue('B'.$y, $name);
				$sheet->mergeCells('B'.$y.':D'.$y);
			$sheet->setCellValue('E'.$y, $summary['amount'] ? : 0);
			$sheet->setCellValue('F'.$y, $summary['gst'] ? : 0);
			$sheet->setCellValue('G'.$y, $summary['total'] ? : 0);

			$y++;
		}

		/*foreach($rlCategoryTotals as $categoryID => $amount)
		{
			$sheet->setCellValue('A'.$y, ($no ? $no++ : 1).'.');
			$sheet->setCellValue('B'.$y, $rlCategoryNames[$categoryID]);
				$sheet->mergeCells("B$y:F$y");
			$sheet->setCellValue('G'.$y, $amount);

			$y++;
		}*/

		$setAlign($sheet, "A$categoryY:A$y", 'center');
		$setAlign($sheet, "B$categoryY:B$y", 'left');
		$setAlign($sheet, "G$categoryY:H$y", 'center');

		// <> additional rows
		foreach(range(1, 25 - count($rlCategoryTotals)) as $no)
		{
			$sheet->mergeCells("B$y:D$y");
			$y++;
		}

		$sheet->mergeCells("B$y:D$y");

		$tableStyle = $sheet->getStyle("A$firstY:G$y");
		// $tableStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // align
		$tableStyle->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); // border

		$y++;

		$sheet->setCellValue('D'.$y, 'Grand Amount :');
		$sheet->setCellValue('E'.$y, $allAmountTotal);
		$sheet->setCellValue('F'.$y, $allGstTotal);
		$sheet->setCellValue('G'.$y, $allRlTotal);
		$backgrounds[] = array($sheet, "E$y:G$y", 'FFFF00');
		$setBorder($sheet, "E$y:G$y", 'allBorders');

		$highlight($sheet, "E$y:G$y", true);

		$y += 2;

		$signatureAdd($sheet, $y, $opsName, $SignatureImg);

		// background-color the whole sheet.
		$background($sheet, "A1:G$y", 'FFFFFF');

		$sheetNo = 2;

		/***********************************
		 * LAST SHEETS : SHEETS OF LOOP
		 ***********************************/
		foreach($rlSites as $siteID => $rlList)
		{
			foreach($rlList as $rl)
			{
				$sheet = $sheets[$sheetNo] = $excel->createSheet($sheetNo);

				$sheetNo++;

				$site = $sites[$siteID];

				$sheet->setTitle($site->siteName);

				$y = 11;

				// <> info
				$sheet->getCell("A$y")->setValue('RECONCILIATION LIST - Slip of Payment/Bill/Receipt - '.strtoupper($types[$prType]))->getStyle()->getFont()->setBold(true);
				// $sheet->setCellValue("A$y", 'RECONCILIATION LIST - Summary');
					$sheet->mergeCells("A$y:G$y");
					$y += 2;

				$sheet->setCellValue("A$y", 'Cluster : '.$cluster->clusterName);
					$sheet->mergeCells("A$y:B$y");
					$y++;
				$sheet->setCellValue("A$y", 'Month : '.$monthName.' '.$year);
					$sheet->mergeCells("A$y:B$y");
					$y++;
				$sheet->setCellValue("A$y", 'PI1M : '.$site->siteName);
					$sheet->mergeCells("A$y:B$y");
					$y += 2;

				// <> table header
				$firstY = $y;

				$sheet->setCellValue("A$y", 'No.');
				$sheet->setCellValue("B$y", "Category");
				$sheet->setCellValue("C$y", 'Particular');
					$sheet->mergeCells("C$y:F$y");
				$sheet->setCellValue("G$y", 'GST');
				$sheet->setCellValue("H$y", 'Amount (RM)');

				$highlight($sheet, "A$y:H$y", true); // bold
				// $background($sheet, "A$y:H$y", '93CDDD');
				$backgrounds[] = array($sheet, "A$y:H$y", '93CDDD');

				$setBorder($sheet, 'A'.$y.':H'.$y, 'allBorders');

				$y++;

				// <> excel preparation
				$no = 1;
				
				$startY = $y;

				$total = 0;

				$gstTotal = 0;

				foreach($rl->getReconciledCategories() as $category)
				{
					$categoryY = $y;

					$categoryID = $category->expenseCategoryID;

					$sheet->setCellValue('A'.$y, $no++);
					$sheet->setCellValue('B'.$y, $expenseCategories[$categoryID]);
					$highlight($sheet, 'A'.$y.':G'.$y, true);

					$categoryTotal = $category->getTotal();

					$categoryGstTotal = $category->getGst();

					$total += $categoryTotal;

					$gstTotal += $categoryGstTotal;

					$sheet->setCellValue('G'.$y, $categoryGstTotal);

					$sheet->setCellValue('H'.$y, $categoryTotal);

					foreach($category->getFiles() as $file)
					{
						$filePath = $file->getFilePath();

						$type = $file->prReconcilationFileType;

						switch($type)
						{
							case 'image/jpeg':
								$gdImage = imagecreatefromjpeg($filePath);
							break;
							case 'image/png':
								$gdImage = imagecreatefrompng($filePath);
							break;
							case 'image/bmp':
								$gdImage = imagecreatefromwbmp($filePath);
							break;
						}

						$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
						$objDrawing->setImageResource($gdImage);
						$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
						$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
						$objDrawing->setWidth(480);
						$objDrawing->setWorksheet($sheet);
						$objDrawing->setCoordinates('C'.$y);
						// $objDrawing->setOffsetX($objDrawing->getOffsetX()+30);
						$objDrawing->setOffsetX(10);
						$objDrawing->setOffsetY(10);

						$height = $objDrawing->getHeight();

						$y += round($height / 19);
					}

					$setBorder($sheet, "A$categoryY:A$y", 'outline');
					$setBorder($sheet, "B$categoryY:B$y", 'outline');
					$setBorder($sheet, "C$categoryY:F$y", 'outline');
					$setBorder($sheet, "G$categoryY:G$y", 'outline');
					$setBorder($sheet, "H$categoryY:H$y", 'outline');

					$y++;
				}

				// total amount.
				$sheet->setCellValue('F'.$y, 'Total Amount :');
					$setAlign($sheet, "F$y", 'right');
				$sheet->setCellValue('G'.$y, $categoryGstTotal);
				$sheet->setCellValue('H'.$y, $total);
					$setAlign($sheet, "G$firstY:H$y", 'center');
				$highlight($sheet, "F$y:G$y", true);
				$backgrounds[] = array($sheet, "G$y:H$y", "FFFF00");

				$y += 2;
				$sheet->setCellValue('A'.$y, 'Disclaimer : This excel is computer generated. No signature is required.');
				
				// color
				$background($sheet, "A1:H".$y, 'FFFFFF');
				// $backgrounds[] = array($sheet, 'A1:H'.$y, 'FFFFFF');
			}
		}


		/**************
		 * GLOBALS
		 **************/
		// set all latter setter
		foreach($backgrounds as $bg)
			$background($bg[0], $bg[1], $bg[2]);

		foreach($sheets as $no => $sht)
		{
			$y = 6;

			$gdImage = imagecreatefrompng(path::files('expense/nusuara-logo.png'));
			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setImageResource($gdImage);
			$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
			$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
			$objDrawing->setCoordinates('D2');
			$objDrawing->setWorksheet($sht);

			foreach(array('NUSUARA TECHNOLOGIES SDN BHD (599840-M)', 
				'Unit No. 2-19-01, Block 2, VSQ @ PJ City Centre, Jalan Utara',
				'46200 Petaling Jaya, Selangor Darul Ehsan',
				'Tel : +60 (3) 7451 8080               Fax : +60 (3) 7451 8081') as $text)
			{
				$sht->getCell("A$y")
					->setValue($text)
					->getStyle()
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
				$sht->mergeCells("A$y:G$y");
				$y++;
			}

			foreach(array('B', 'C', 'D', 'E', 'F', 'G', 'H') as $x)
				$sht->getColumnDimension($x)->setWidth('18');

			
		}

		$ExcelHelper->execute();
	}

	private function floatVal($val = null)
	{
		if(!$val)
			return number_format(0, 2, '.', '');
		else
			return number_format($val, 2, '.', '');
	}

	private function totalVal($val = null)
	{
		return !$val ? 0 : $val;
	}

}


?>