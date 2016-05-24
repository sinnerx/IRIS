<?php
class Controller_Report
{
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
			$no++;
			$cellData = Array(
					"no"=>$no,
					"RO"=>"",
					"state"=>$row['stateName'],
					"district"=>"",
					"ust"=>"",
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

		$ExcelHelper->execute();
	}


	# gett all activity report -- root 
	public function getallActivityReport($year = null,$month = null)
	{
		$data['year'] = $year = $year ? : date("Y");
		$data['month'] = $month = $month ? : date("n");

		// check for non approved report.
		$data['totalApprovalPendingReport'] = model::load("blog/article")->getTotalApprovalPendingReport($year, $month);
		$data['totalNonrecentReport'] = model::load('blog/article')->getTotalOfNonrecentReport($year, $month);

		view::render("root/report/activityReport",$data);
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

	public function quarterlyReport(){

		set_time_limit(0);

		db::select("siteID");
		$allsite = db::get("site")->result('siteID');
		

		if(!is_dir(path::files('reportGeneration/QuarterlyActivities/')))
		mkdir(path::files('reportGeneration/QuarterlyActivities/'));

		$folderpath = path::files('reportGeneration/QuarterlyActivities/tmp/');
		if(!is_dir($folderpath))
		mkdir($folderpath);

		$counterSite = 0;
		//site loop
		//var_dump($allsite);
		//die;
		foreach ($allsite as $allSiteKey) {

			$data = array();
			$report	= model::load("report/report")->getQuarterlyReport($allSiteKey['siteID']);
			$siteKey = $report;

			//var_dump($siteKey);
			//die;

			// Creating the new document...
			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			$filename = "Test word file";
			$phpWord->addFontStyle('rStyle', array('bold' => true, 'size' => 20, 'allCaps' => true));
			$phpWord->addParagraphStyle('pStyle', array( 'align' => 'center', 'spaceAfter' => 100));
			$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));

			$header = array('size' => 16, 'bold' => true);
			//$WordHelper	= new model\report\PHPWordHelper($phpWord,$filename);
			//$WordHelper->execute();

			/* Note: any element you append to a document must reside inside of a Section. */
			$section = $phpWord->addSection();
			$section->addText(htmlspecialchars("PI1M Quarterly Report for MCMC"), 'rStyle', 'pStyle');			

				//var_dump($siteKey);
				//die;
			// Adding an empty Section to the document...
			$section->addPageBreak();
			$section->addText(htmlspecialchars($siteKey['siteName']),'rStyle', 'pStyle');
			$section->addPageBreak();


			//PI1M Performance
			// Adding Text element to the Section having font styled by default...
			$section->addText(
			    htmlspecialchars(
			        $siteKey['siteName']. '- PI1M Performance' 
			    ),$header
			);

			//pi1m performance
			$styleTable = array('borderSize' => 6, 'borderColor' => '999999');
			$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFF00');
			$cellRowContinue = array('vMerge' => 'continue');
			$cellColSpan = array('gridSpan' => 5, 'valign' => 'center');
			$cellHCentered = array('align' => 'center');
			$cellVCentered = array('valign' => 'center');

			$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
			$table = $section->addTable('Colspan Rowspan');

			$table->addRow();
			$cell1 = $table->addCell(8000, $cellColSpan);
			$textrun1 = $cell1->addTextRun($cellHCentered);
			$textrun1->addText(htmlspecialchars('2016'));

			// $table->addRow();
			// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('2014'), null, $cellHCentered);
			// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('May'), null, $cellHCentered);
			// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('June'), null, $cellHCentered);		

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('January'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('February'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('March'), null, $cellHCentered);

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('CashFlow'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Revenue'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			
			// foreach ($siteKey['revenue'] as $keyRevenue) {
			// 	# code...
			// 	$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			// }
			
			//$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			//$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);	

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Cost'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('NetBook'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Revenue'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		

			//Take Up
			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Take Up'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Total Member'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			


			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Active Wifi User'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);

			//PC Usage
			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('PC Usage'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Day PC User Total'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			


			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Night PC User Total'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		


			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Day PC Usage Total Hours'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Night PC Usage Total Hours'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);

			//Bandwidth Usage
			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Bandwidth Usage(in Mb)'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Day Average'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Day Peak'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Night Average'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);			

			$table->addRow();
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Night Peak'), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
			//$table->addCell(null, $cellRowContinue);



				# code...
				//var_dump($siteKey['siteID']);
				//break;

			

			//ICT Training
			if ($siteKey['Training']){
			$section->addPageBreak();
			$section->addText(htmlspecialchars($siteKey['siteName']. '- ICT Training'), $header);
			$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
			$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
			$styleCell = array('valign' => 'center');
			$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
			$fontStyle = array('bold' => true, 'align' => 'center');
			$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
			$table = $section->addTable('Fancy Table');
			$table->addRow(900);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Date'), $fontStyle);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Title'), $fontStyle);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Details'), $fontStyle);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Duration (Hours)'), $fontStyle);			
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Attendees'), $fontStyle);			
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Comment'), $fontStyle);

			foreach ($siteKey['Training'] as $keyTraining) {
							# code...
						$table->addRow();
					    $table->addCell(2000)->addText(htmlspecialchars($keyTraining['startDate']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyTraining['activityName']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyTraining['activityDescription']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyTraining['HourTraining']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyTraining['attendees']));
					    $table->addCell(2000)->addText(htmlspecialchars(""));
						}			


			}//end if
			//Events and Activites
			if ($siteKey['Event']){

			$section->addPageBreak();
			$section->addText(htmlspecialchars($siteKey['siteName']. '- Events and Activities'), $header);
			$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
			$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
			$styleCell = array('valign' => 'center');
			$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
			$fontStyle = array('bold' => true, 'align' => 'center');
			$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
			$table = $section->addTable('Fancy Table');
			$table->addRow(900);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Date'), $fontStyle);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Title'), $fontStyle);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Details'), $fontStyle);
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Duration (Days)'), $fontStyle);			
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Attendees'), $fontStyle);			
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Comment'), $fontStyle);		


			foreach ($siteKey['Event'] as $keyEvent) {
							# code...
						$table->addRow();
					    $table->addCell(2000)->addText(htmlspecialchars($keyEvent['startDate']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyEvent['activityName']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyEvent['activityDescription']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyEvent['HourTraining']));
					    $table->addCell(2000)->addText(htmlspecialchars($keyEvent['attendees']));
					    $table->addCell(2000)->addText(htmlspecialchars(""));
						}	
			}//end if
			//Activites Gallery
			$section->addPageBreak();
			$section->addText(htmlspecialchars($siteKey['siteName']. ' - Activities Gallery'), $header);
			//var_dump($siteKey);
			//die;
			if($siteKey['album']){

				foreach ($siteKey['album'] as $keyAlbum) {
					//var_dump(url::asset() . "/frontend/images/photo/" .$keyAlbum);

					$image = url::asset() . "/frontend/images/photo/" .$keyAlbum['albumName'];
					//var_dump(url::asset() . "/frontend/images/photo/" .$keyAlbum);
					//die;
					//var_dump($keyAlbum);
					//die;
					# code...http://localhost/digitalgaia/iris/pim/assets/frontend/images/photo/2015/08/17/congkak.jpg
					//if($keyAlbum && file_exists($image)){
					if($keyAlbum && @getimagesize($image)){
						//var_dump($image);
						//die;
						$section->addImage($image,array('width' => 200, 'height' => 200));
						$section->addText(htmlspecialchars($keyAlbum['albumDate']));
					}			
				 }//end foreach album
			}//end if


			//AJK PI1M
			$section->addPageBreak();
			$section->addText(htmlspecialchars($siteKey['siteName']. '- AJK PI1M'), $header);
			$imageajk = url::asset() . "/frontend/images/photo/" . $siteKey['ajk'];
					 if($siteKey['ajk'] != '' && @getimagesize($imageajk)){
					// 	//
						//var_dump($imageajk);

						$section->addImage($imageajk,array('width' => 200, 'height' => 200));
					}		

			// Saving the document as OOXML file...
			$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

			//var_dump($siteKey);
			//die;
			$objWriter->save($folderpath  . $siteKey['siteName'].'.docx');
				// if($counterSite == 1)
				// 	break;	

				// $counterSite++;	
		}//end site foreach
		//end site loop

		/*
		 * Note: it's possible to customize font style of the Text element you add in three ways:
		 * - inline;
		 * - using named font style (new font style object will be implicitly created);
		 * - using explicitly created font style object.
		 */

		$path = opendir($folderpath);

		$zipfilename = 'QUARTERLY ACTIVITES REPORT 2016 Jan-March' .'.zip';
		$zippath = path::files('reportGeneration/QuarterlyActivities/'.$zipfilename);

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

		


		//

		//$objWriter->save('php://output');
		// header('Content-Type: application/vnd.ms-word');	
		// header('Content-disposition: attachment; filename="QuarterlyReport.docx"');
		// //header('Content-Length: ' . filesize($folderpath . '/helloworld.docx'));				
		// readfile($folderpath . 'QuarterlyReport.docx');
		//unlink($folderpath . 'QuarterlyReport.docx');		


		view::render("root/report/quarterlyReport",$data);
	}

	public function generateQuarterReport(){
		
		if(form::submitted())
		{
			$folderpath = path::files('reportGeneration/QuarterlyActivities/');
			header('Content-Type: application/vnd.ms-word');	
			header('Content-disposition: attachment; filename="QUARTERLY ACTIVITES REPORT 2016 Jan-March.zip"');
			//header('Content-Length: ' . filesize($folderpath . '/helloworld.docx'));				
			readfile($folderpath . 'QUARTERLY ACTIVITES REPORT 2016 Jan-March.zip');
			//unlink($folderpath . 'QuarterlyReport.docx');
		}

	}
}

?>