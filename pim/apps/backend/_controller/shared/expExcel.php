<?php

class Controller_ExpExcel
{
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

	public function rlSummaryGenerate($clusterID, $prType, $month, $year)
	{
		$time = microtime(true);

		$cluster = orm('site/cluster')->find($clusterID);

		$monthName = date('F', strtotime('2015-'.$month.'-01'));

		// get rls
		$rlList = orm('expense/pr/reconcilation/reconcilation')
		->where('prReconcilationStatus', 1)
		->where('pr.prType = ? AND pr.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = ?)', array($prType, $clusterID))
		->where('MONTH(prReconcilationSubmittedDate)', $month)
		->where('YEAR(prReconcilationSubmittedDate)', $year)
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

		$setBorder = function($sheet, $range, $type)
		{
			$borders = $sheet->getStyle($range)->getBorders();

			$method = 'get'.ucwords($type);
			// $line = $type == 'outline' ? $borders->getOutline() : $borders->getAllBorders();
			$line = $borders->$method();
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

		$signatureAdd = function($sheet, &$y)
		{
			// signature.
			$sheet->setCellValue('B'.$y, 'Acknowledged/Approved By:');
			$sheet->setCellValue('D'.$y, 'Checked by:');

			$y += 4;

			$saifulSignature = imagecreatefromjpeg(path::files('expense/saiful-signature.jpg'));
			$lizaSignature = imagecreatefromjpeg(path::files('expense/liza-signature.jpg'));

			$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setImageResource($saifulSignature);
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

			$sheet->setCellValue('B'.$y, 'Mohamad Saiful Sabran');
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
		$headers = array('No', 'PR Date', 'PR No', 'Particular', 'Amount (RM)', 'Amount (RM)');

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

		// color header to blue
		$backgrounds[] = array($sheet, "A$y:G$y", '93CDDD');

		$headersStyle = $sheet->getStyle("A$y:G$y");
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

			$rlCategories = array();
			$rlCategoryNames = array();

			// Prepare pr_items grouped by categories
			foreach($rlItems as $rlItem)
			{
				if(!isset($rlCategories[$rlItem->prReconcilationCategoryID]))
					$rlCategories[$rlItem->prReconcilationCategoryID] = array();

				$rlCategories[$rlItem->prReconcilationCategoryID][] = $rlItem;

				if(!isset($rlCategoryTotals[$rlItem->prReconcilationCategoryID]))
					$rlCategoryTotals[$rlItem->prReconcilationCategoryID] = 0;

				if(!isset($rlCategoryNames[$rlItem->prReconcilationCategoryID]))
					$rlCategoryNames[$rlItem->prReconcilationCategoryID] = $expenseCategories[$rlItem->expenseCategoryID];

				$rlCategoryTotals[$rlItem->prReconcilationCategoryID] += $rlItem->prReconcilationItemTotal;
			}

			// <> Item loops.
			$itemTotal = 0;

			foreach($rlCategories as $categoryID => $rlItems)
			{
				$rlCategoryTotal = 0;
				$categoryName = $rlCategoryNames[$categoryID];

				$sheet->setCellValue($pos('D', $y), $categoryName); // categoryName
					$sheet->mergeCells("D$y:E$y");
				$highlight($sheet, 'D'.$y);

				foreach($rlItems as $rlItem)
					$rlCategoryTotal += $rlItem->prReconcilationItemTotal;

				$sheet->setCellValue($pos('G', $y), $rlCategoryTotal);  // total amount
				$highlight($sheet, 'G'.$y, true);
				$y++;

				foreach($rlItems as $rlItem)
				{
					$num = strtolower(numberToRoman($no ? 1 : $no++));

					$itemTotal += $rlItem->prReconcilationItemTotal;

					$sheet->setCellValue($pos('D', $y), $num.'. '.$rlItem->prReconcilationItemName); // item name
					$sheet->setCellValue($pos('F', $y), $rlItem->prReconcilationItemTotal); // item total

					$y++;
				}

				$allRlTotal += $rlCategoryTotal;
			}

			// total ammount.
			// <> RL total
			$sheet->setCellValue("F$y", $itemTotal);
			$sheet->setCellValue("G$y", $itemTotal);
			$backgrounds[] = array($sheet, "F$y:G$y", "FFFF00");
			$highlight($sheet, 'F'.$y, true);
			$highlight($sheet, 'G'.$y, true);
			$setBorder($sheet, "F$y:G$y", 'allBorders');

			$endY = $y;

			// give border to each big column
			$x = 'A';
			foreach(range(1, 7) as $xNo)
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

		$sheet->setCellValue("E$y", 'Total amount :');
			$sheet->getStyle("E$y")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$sheet->setCellValue("F$y", $allRlTotal);
		$sheet->setCellValue("G$y", $allRlTotal);
		$setBorder($sheet, "F$y:G$y", 'allBorders');

		$y += 2;

		$signatureAdd($sheet, $y);

		// background-color the whole sheet.
		$sheet->getStyle('A1:'."G$y")->applyFromArray(
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
			$sheet->mergeCells("B$y:F$y");
		$sheet->setCellValue("G$y", 'Amount (RM)');

		$highlight($sheet, "A$y:G$y", true); // bold

		// color header to blue
		$backgrounds[] = array($sheet, "A$y:G$y", '93CDDD');

		$y++;

		// <> RL category summaries
		$no = 1;
		$categoryY = $y;
		foreach($rlCategoryTotals as $categoryID => $amount)
		{
			$sheet->setCellValue('A'.$y, ($no ? $no++ : 1).'.');
			$sheet->setCellValue('B'.$y, $rlCategoryNames[$categoryID]);
				$sheet->mergeCells("B$y:F$y");
			$sheet->setCellValue('G'.$y, $amount);

			$y++;
		}

		$setAlign($sheet, "A$categoryY:A$y", 'center');
		$setAlign($sheet, "B$categoryY:B$y", 'left');
		$setAlign($sheet, "G$categoryY:G$y", 'center');

		// <> additional rows
		foreach(range(1, 25 - count($rlCategoryTotals)) as $no)
		{
			$sheet->mergeCells("B$y:F$y");
			$y++;
		}

		$sheet->mergeCells("B$y:F$y");

		$tableStyle = $sheet->getStyle("A$firstY:G$y");
		// $tableStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // align
		$tableStyle->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); // border

		$y++;

		$sheet->setCellValue('F'.$y, 'Total Amount :');
		$sheet->setCellValue('G'.$y, $allRlTotal);
		$backgrounds[] = array($sheet, "G$y", 'FFFF00');

		$highlight($sheet, 'F'.$y, true);

		$y += 2;

		$signatureAdd($sheet, $y);

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
				$sheet->getCell("A$y")->setValue('RECONCILIATION LIST - Slip of Payment/Bill/Reciept')->getStyle()->getFont()->setBold(true);
				$sheet->setCellValue("A$y", 'RECONCILIATION LIST - Summary');
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
				$sheet->setCellValue("G$y", 'Amount (RM)');

				$highlight($sheet, "A$y:G$y", true); // bold
				$background($sheet, "A$y:G$y", '93CDDD');

				$setBorder($sheet, 'A'.$y.':G'.$y, 'outline');

				$y++;

				// <> excel preparation
				$no = 1;
				
				$startY = $y;

				$total = 0;

				foreach($rl->getReconciledCategories() as $category)
				{
					$categoryY = $y;

					$categoryID = $category->expenseCategoryID;

					$sheet->setCellValue('A'.$y, $no++);
					$sheet->setCellValue('B'.$y, $expenseCategories[$categoryID]);
					$highlight($sheet, 'A'.$y.':G'.$y, true);

					$categoryTotal = $category->getTotal();

					$total += $categoryTotal;

					$sheet->setCellValue('G'.$y, $categoryTotal);

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

					$y++;
				}

				// total amount.
				$sheet->setCellValue('F'.$y, 'Total Amount :');
					$setAlign($sheet, "F$y", 'right');
				$sheet->setCellValue('G'.$y, $total);
					$setAlign($sheet, "G$firstY:G$y", 'center');
				$highlight($sheet, "F$y:G$y", true);

				$y += 2;
				$sheet->setCellValue('A'.$y, 'Disclaimer : This excel is computer generated. No signature is required.');
				
				// color
				$backgrounds[] = array($sheet, 'A1:G'.$y, 'FFFFFF');
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

			foreach(array('B', 'C', 'D', 'E', 'F', 'G') as $x)
				$sht->getColumnDimension($x)->setWidth('18');

			
		}

		$ExcelHelper->execute();
	}
}


?>