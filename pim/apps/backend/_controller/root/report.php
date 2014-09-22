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
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xlsx");

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

	public function generateMasterListing($month = 5)
	{
		$endDate	= date("Y-m-d");
		$startDate	= date("Y-m-d",strtotime("-$month months",strtotime($endDate)));

		$data = model::load("report/report")->getMasterListing($startDate,$endDate);

		$filename	= "Pi1M Data (NEW KPI) - ".date("j F Y",strtotime($endDate));

		## initiate both helper and php excel.
		$excel	= new \PHPExcel;
		$ExcelHelper	= new model\report\PHPExcelHelper($excel,$filename.".xlsx");

		## the main working sheet.
		$sheet	= $excel->getActiveSheet();

		#1 create main title.
		$sheet->setCellValue("F1","MASTER LISTING DATA");
		$sheet->getStyle("F1")->getFont()->setSize(18);
		$sheet->getRowDimension(1)->setRowHeight(25);
		$sheet->getRowDimension(4)->setRowHeight(20);
		$sheet->getRowDimension(5)->setRowHeight(20);

		#2 date.
		$sheet->setCellValue("A2","DATE : ".date("j F Y",strtotime($startDate))." - ".date("j F Y",strtotime($endDate))." ($month Months)");
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
}


?>