<?php

class Controller_Ajax_Report
{
	/**
	 * List of generated reports (and pending)
	 */
	public function monthlyActivityReports($year = null, $month = null, $category = null)
	{
		//var_dump($category);
		//die;
		$query = db::from('report_monthly');

		$query->where('reportMonthlyYear', $year);

		$query->where('reportMonthlyMonth', $month);

		if($category)
			$query->where('reportMonthlyCategory', $category);

		$query->order_by('reportMonthlyUpdatedDate DESC');

		$reports = $query->get()->result();

		$report = db::from('report_monthly')->where('reportMonthlyStatus', 0)->get()->row();

		view::render('root/report/ajax/monthlyActivityReports', array(
			'reports' => $reports,
			'pending_report' => $report,
			'month' => $month,
			'year' => $year,
			'totalApprovalPendingReport' => model::load("blog/article")->getTotalApprovalPendingReport($year, $month, $category),
			'totalNonrecentReport' => model::load('blog/article')->getTotalOfNonrecentReport($year, $month, $category)
			));
	}	

	public function quarterlyActivityReports($year = null, $quarter = null)
	{
		//var_dump($category);
		//die;
		$query = db::from('report_quarterly');

		$query->where('reportQuarterlyYear', $year);

		if($quarter)
			$query->where('reportQuarterlyName', $quarter);

		
		$query->order_by('reportQuarterlyUpdatedDate DESC');

		$reports = $query->get()->result();

		$report = db::from('report_quarterly')->where('reportQuarterlyStatus', 0)->get()->row();

		view::render('root/report/ajax/quarterlyActivityReports', array(
			'reports' => $reports,
			'pending_report' => $report,
			'quarter' => $quarter,
			'year' => $year
			));
	}

	/**
	 * Main method to generate report
	 */
	public function monthlyActivityGenerate($year, $month, $category = null)
	{
		set_time_limit(0);
		error_reporting(E_ALL & ~E_NOTICE);
		ini_set('display_errors', 'on');

		// check whether there're currently pending generation or not.
		$generation = db::from('report_monthly')
		->where('reportMonthlyStatus', 0)
		->get()->row();

		if($generation)
		{
			return json_encode(array(
				'status' => 'failed',
				'message' => 'Unable to generate another report while processing one.'
				));
		}

		$totalArticle = $this->queryArticle($month, $year, $category)->select('count(*) as total')->get()->row('total');

		if($totalArticle == 0)
		{
			return json_encode(array(
				'status' => 'failed',
				'message' => 'There is not report for this month yet.'
				));
		}

		$id = db::insert('report_monthly', array(
			'reportMonthlyStatus' => 0,
			'reportMonthlyStatusState' => 'initiating',
			'reportMonthlyMonth' => $month,
			'reportMonthlyYear' => $year,
			'reportMonthlyCategory' => $category,
			'reportMonthlyArticleCompleted' => 0,
			'reportMonthlyArticleTotal' => $totalArticle,
			'reportMonthlyCreatedDate' => now(),
			'reportMonthlyUpdatedDate' => now()
			))->getLastInsertId();

		session_write_close();

		response::close(json_encode(array(
			'status' => 'success'
			)));

		// begin
		$this->monthlyActivityProcess($id);
	}

/**
	 * Main method to generate report
	 */
	public function quarterlyActivityGenerate($year, $quarter = null)
	{
		set_time_limit(0);
		error_reporting(E_ALL & ~E_NOTICE);
		ini_set('display_errors', 'on');

		// check whether there're currently pending generation or not.
		$generation = db::from('report_quarterly')
		->where('reportQuarterlyStatus', 0)
		->get()->row();

		if($generation)
		{
			return json_encode(array(
				'status' => 'failed',
				'message' => 'Unable to generate another report while processing one.'
				));
		}

		$quarterExist = db::from('report_quarterly')
		->where('reportQuarterlyName', $quarter)
		->where('reportQuarterlyYear', $year)
		->get()->row();

		if($quarterExist)
		{
			return json_encode(array(
				'status' => 'failed',
				'message' => 'Quarterly report already generated.'
				));
		}		

		db::select("count(siteID) as total");
		db::order_by("siteID", "ASC");
		//db::where("siteID", 68);
					//db::limit(101, 30);
		$totalSite = db::get("site")->row('total');

		$id = db::insert('report_quarterly', array(
			'reportQuarterlyStatus' => 0,
			'reportQuarterlyStatusState' => 'initiating',
			'reportQuarterlyName' => $quarter,
			'reportQuarterlyYear' => $year,
			'reportQuarterlySiteCompleted' => 0,
			'reportQuarterlySiteTotal' => $totalSite,
			'reportQuarterlyCreatedDate' => now(),
			'reportQuarterlyUpdatedDate' => now()
			))->getLastInsertId();

		session_write_close();

		response::close(json_encode(array(
			'status' => 'success'
			)));

		// begin
		$this->quarterlyReport($id);
	}	

	protected function queryArticle($month, $year, $category)
	{
		//var_dump($category);
		//die;
		if($category){

			db::from("article");
			db::where('article.articleStatus = ?', array(1));

			db::join('site', 'site.siteID = article.siteID');
			db::join('article_category', 'article.articleID = article_category.articleID');

			db::where('categoryID = ?', array($category));
			//db::where('article.activityType', 1);
			//db::where('article.siteID', 1);	

			db::where('year(article.articlePublishedDate)',$year);
			$db = db::where('month(article.articlePublishedDate)',$month);
			//var_dump($db->get());
			//die;					
		}
		else{
			db::from("activity_article");
			db::join("activity","activity_article.activityID = activity.activityID");
			db::join("article","activity_article.articleID = article.articleID");

			db::where('activity_article.articleID IN (SELECT articleID FROM article WHERE articleStatus = ?)', array(1));

			db::join('site', 'site.siteID = activity.siteID');
		
			db::where('activity.activityType', 1);
			//db::where('activity.siteID', 1);	

			db::where('year(activity.activityStartDate)', $year);
			$db = db::where('month(activity.activityStartDate)', $month);					
		}
		
		return $db;
	}

	// process of the report
	public function monthlyActivityProcess($reportId)
	{
		// to avoid session deadlock
		session_write_close();

		set_time_limit(0);
		ini_set('memory_limit','800M');

		$report = orm('report/monthly')->find($reportId);

		$year = $report->reportMonthlyYear;

		$month = $report->reportMonthlyMonth;

		$category = $report->reportMonthlyCategory;
		//var_dump($category);
		//die;
		if($report->reportMonthlyStatusState != 'initiating')
			return;

		$report->reportMonthlyStatusState = 'processing';

		$report->save();

		try
		{
			// begin the process.
			$docsPath = path::files('reportGeneration/reportMonthly/'.$reportId.'/docs');

			$imageFolder = path::files('reportGeneration/reportMonthly/image_caches');

			if(!is_dir($docsPath))
				mkdir($docsPath, 0777, true);

			if(!is_dir($imageFolder))
				mkdir($imageFolder);

			$articles = $this->queryArticle($month, $year, $category)->get()->result();

			$report->reportMonthlyArticleTotal = count($articles);

			$report->save();

			foreach($articles as $no => $row)
			{
				PhpOffice\PhpWord\Media::resetElements();

				if($category)
					$date = date('Y-m-d', strtotime($row['articlePublishedDate']));
				else
					$date = date('Y-m-d', strtotime($row['activityStartDate']));

				$fileName = $row['siteName']." - ".$date." - ".$row['articleName'];

				// credit : http://stackoverflow.com/questions/2021624/string-sanitizer-for-filename
				$fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName);
				$fileName = mb_ereg_replace("([\.]{2,})", '', $fileName);

				$word = new \PhpOffice\PhpWord\PhpWord;

				$word->addTitleStyle('rStyle', array(
					'bold' => true,
					'size' => 11,
					'allCaps' => true),
					array('align' => 'center'));

				$section = $word->addSection();

				$month = date('F', strtotime('2016-'.$month.'-01'));

				$section->addTitle('LAPORAN AKTIVITI PI1M '.$row['siteName'].' '.$row['articleName'].' '.$month.'-'.$year, 'rStyle');

				$section->addText("\n");
				
				/// strip except paragraph
				$text = strip_tags($row['articleText'], '<p><img>');

				// give line break
				$text = str_replace('</p>', "\n", $text);

				// now strip all except img text
				$text = strip_tags($text, '<img>');

				foreach(explode("\n", $text) as $txt)
				{
					// to insert img in between
					// then add text after that.
					foreach(explode('<img', $txt) as $no => $tx)
					{
						// remerge img.
						$tx = $no != 0 ? '<img'.$tx : $tx;

						if($no != 0)
						{
							try
							{
								$dom = new \DOMDocument;

								@$dom->loadHTML($tx);

								// since we assume there's only one img tag here.
								$imgs = $dom->getElementsByTagName('img');
								$img = $imgs->item(0);

								$link = $img->getAttribute('src');

								$image = new \abeautifulsite\SimpleImage($link);

								$imagePath = $imageFolder.'/'.md5($link);

								if(!file_exists($imagePath))
									$image->best_fit(300, 300)->save($imagePath);

								// insert image 
								$section->addImage($imagePath, array('align' => 'center'));
							}
							catch(\Exception $e)
							{
								// just skip adding this image.
							}
						}

						$tx = str_replace('&nbsp;', ' ', $tx);

						$tx = strip_tags(trim($tx));

						// $tx = htmlspecialchars($tx);

						$search = array('&ldquo;', '&rdquo;', '&lsquo;', '&rsquo;', '&ndash;', '&mdash;', '&hellip;', '&ensp;', '&emsp;');

						$replace = array("'", "'", "'", "'", '-', '-', '...', ' ', ' ');

						$tx = str_replace($search, $replace, $tx);

						\PhpOffice\PhpWord\Shared\Html::addHtml($section, '<p>'.htmlspecialchars($tx).'</p>');
					}
				}

				$writer = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');

				$writer->save($docsPath.'/'.$fileName.'.docx');

				$report->articleCompletionIncrement();
			}

			// http://stackoverflow.com/questions/19161611/generate-a-pseudo-random-6-character-string-from-an-integer
			$hash = function($num)
			{
				$scrambled = (240049382 * $num + 37043083) % 308915753;

		    	return base_convert($scrambled, 10, 26);
			};

			$report->reportMonthlyZipName = '['.$hash($reportId).'] MONTHLY ACTIVITIES REPORT '.$month.'-'.$year.'.zip';

			$report->updateState('zipping');

			// zip the reports all the reports.
			$reportZip = new ZipArchive;

			$path = path::asset('backend/reports/monthly-activities/'.$report->reportMonthlyZipName);

			$reportZip->open($path, ZIPARCHIVE::CREATE);

			$dirHandle = opendir($docsPath);

			while($file = readdir($dirHandle))
			{
				if(in_array($file, array('.', '..')))
					continue;

				$reportZip->addFile($docsPath.'/'.$file, $file);
			}

			$reportZip->close();

			$report->reportMonthlyZipSize = filesize($path);

			$report->updateState('completed');
		}
		catch(\Exception $e)
		{
			$report->reportMonthlyStatus = 2;

			$report->updateState($e->getMessage());
		}
	}

public function quarterlyReport($reportId){

		session_write_close();

		set_time_limit(0);
		ini_set('memory_limit','800M');

		$reportQuarterlyTable = orm('report/quarterly')->find($reportId);
		//var_dump($report);

		$year = $reportQuarterlyTable->reportQuarterlyYear;

		$quarter = $reportQuarterlyTable->reportQuarterlyName;

		//var_dump($category);
		//die;
		if($reportQuarterlyTable->reportQuarterlyStatusState != 'initiating')
			return;

		$reportQuarterlyTable->reportQuarterlyStatusState = 'processing';

		$reportQuarterlyTable->save();

		try{

			// begin the process.
			$docsPath = path::files('reportGeneration/reportQuarterly/'.$reportId.'/docs');

			//$imageFolder = path::files('reportGeneration/reportQuarterly/image_caches');

			if(!is_dir($docsPath))
				mkdir($docsPath, 0777, true);

			//site list
			db::select("siteID");
			db::order_by("siteID", "ASC");
			//db::where("siteID", 68);
			//db::limit(101, 30);
			$allsite = db::get("site")->result('siteID');
			//var_dump($allsite);
			//die;
			//$report->reportQuarterlySiteTotal = count($allsite);

			//$report->save();

			$counterSite = 0;
			//site loop
			//var_dump($allsite);
			//die;
			foreach ($allsite as $allSiteKey) {
				\PhpOffice\PhpWord\Media::resetElements();
				$data = array();
				//public function getQuarterlyReport($siteID = null, $year = null, $quarter = null)
				$report	= model::load("report/report")->getQuarterlyReport($allSiteKey['siteID'], $year, $quarter);
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
				$section = $phpWord->addSection(array('orientation' => 'landscape'));
				$section->addText(htmlspecialchars("PI1M Quarterly Report for MCMC"), 'rStyle', 'pStyle');			
				$section->addText(htmlspecialchars("(". model::load("helper")->quarter(3, $quarter) ." ". $year .")"), 'rStyle', 'pStyle');			

				
					//var_dump($siteKey);
					//die;
				// Adding an empty Section to the document...
				
				$section->addText(htmlspecialchars($siteKey['siteName']),'rStyle', 'pStyle');

				$footer = $section->addFooter();
				
				$footer->addPreserveText(htmlspecialchars( $siteKey['siteName'].' CBC, '. $year .' '. model::load("helper")->quarter(2, $quarter).' Quarter Report'), array('align' => 'left'));	
				$footer->addPreserveText(htmlspecialchars('Page {PAGE} of {NUMPAGES}.'), array('align' => 'right'));
				$section->addPageBreak();


				//PI1M Performance
				// Adding Text element to the Section having font styled by default...
				$section->addText(
				    htmlspecialchars(
				        'PI1M Performance' 
				    ),$header
				);

				//pi1m performance
				$styleTable = array('borderSize' => 6, 'borderColor' => '999999');
				$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF', 'bold' => true);
				$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '66BBFF');
				$cellRowContinue = array('vMerge' => 'continue');
				$cellColSpan = array('gridSpan' => 5, 'valign' => 'center');
				$cellHCentered = array('align' => 'center');
				$cellHeaderCentered = array('align' => 'center', 'bold' => true);
				$cellVCentered = array('valign' => 'center');
				$widthCell = 4000;

				$phpWord->addTableStyle('Colspan Rowspan', $styleTable, $styleFirstRow);
				$table = $section->addTable('Colspan Rowspan');

				$table->addRow();
				$cell1 = $table->addCell(20000, $cellColSpan);
				$textrun1 = $cell1->addTextRun($cellHCentered);
				$textrun1->addText(htmlspecialchars($year), $cellHeaderCentered);

				// $table->addRow();
				// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('2014'), null, $cellHCentered);
				// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('May'), null, $cellHCentered);
				// $table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('June'), null, $cellHCentered);		

				$table->addRow();
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), $cellHeaderCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), $cellHeaderCentered, $cellHeaderCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(model::load("helper")->quarter(4, $quarter)[1]), $cellHeaderCentered, $cellHeaderCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(model::load("helper")->quarter(4, $quarter)[2]), $cellHeaderCentered, $cellHeaderCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(model::load("helper")->quarter(4, $quarter)[3]), $cellHeaderCentered, $cellHeaderCentered);

				$table->addRow();
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars('CashFlow'), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars('Revenue (RM)'), null, $cellHCentered);
				//var_dump($allSiteKey['siteID']);
				//var_dump($siteKey['revenue']);
				//die;
				if($siteKey['revenue']){
					foreach($siteKey['revenue'] as $value){
						$value ? $revenue = $value : $revenue = '0';
						//var_dump($totalmember);
						$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars($revenue), null, $cellHCentered);
					}
				}
				else {
					$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
					$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);				
					$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);				
				}
				
				//$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
				//$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);	

				$table->addRow();
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars('Cost'), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);

				$table->addRow();
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars('NetBook'), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);	

				//Take Up
				$table->addRow();
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars('Take Up'), null, $cellHCentered);
				$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars('Total Member'), null, $cellHCentered);
				//var_dump($siteKey['totalmember']['2016']['2']);
				if($siteKey['totalmember']){
					//var_dump($siteKey['totalmember']);
					//die;
					foreach($siteKey['totalmember'] as $value){
						$value ? $totalmember = $value : $totalmember = '0';
						//var_dump($totalmember);
						$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars($totalmember), null, $cellHCentered);
					}
				}	
				else{
					$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
					$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
					$table->addCell($widthCell, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);				
				}		
				


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
				if($siteKey['usagetotal']){
					foreach($siteKey['usagetotal'] as $value){
						$value ? $usagetotal = $value : $usagetotal = '0';
						//var_dump($totalmember);
						$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars($usagetotal), null, $cellHCentered);
					}			
				}
				else{
					$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
					$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
					$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);					
				}
			


				$table->addRow();
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Night PC User Total'), null, $cellHCentered);
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		


				$table->addRow();
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
				$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('Day PC Usage Total Hours'), null, $cellHCentered);
				if($siteKey['usagehour']){
					foreach($siteKey['usagehour'] as $value){
						$value ? $usagehour = $value : $usagehour = '0';
						//var_dump($totalmember);
						$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars($usagehour), null, $cellHCentered);
					}	
				}	
				else{
					$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);
					$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);		
					$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars(''), null, $cellHCentered);				
				}	


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
				$widthTraining = 2200;
				if ($siteKey['Training']){
					$section->addPageBreak();
					$section->addText(htmlspecialchars('ICT Training'), $header);
					$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
					$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
					$styleCell = array('valign' => 'center');
					$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
					$fontStyle = array('bold' => true, 'align' => 'center');
					$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
					$table = $section->addTable('Fancy Table');
					$table->addRow(900);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Date'), $fontStyle);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Title'), $fontStyle);
					//$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Type'), $fontStyle);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Details'), $fontStyle);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Duration (Hours)'), $fontStyle);			
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Attendees'), $fontStyle);			
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Comment'), $fontStyle);

					foreach ($siteKey['Training'] as $keyTraining) {
									# code...
								$table->addRow();
							    $table->addCell($widthTraining)->addText(htmlspecialchars($keyTraining['startDate']));
							    $table->addCell($widthTraining+200)->addText(htmlspecialchars($keyTraining['activityName']));
							    //$table->addCell($widthTraining+200)->addText(htmlspecialchars(''));
							    $desc = strlen($keyTraining['activityDescription']) > 200 ? substr($keyTraining['activityDescription'],0,200)."..." : $keyTraining['activityDescription'];
							    $table->addCell($widthTraining+500)->addText(htmlspecialchars($desc));
							    $table->addCell($widthTraining)->addText(htmlspecialchars($keyTraining['HourTraining']));
							    $table->addCell($widthTraining)->addText(htmlspecialchars($keyTraining['attendees']));
							    $table->addCell($widthTraining)->addText(htmlspecialchars(""));
								}			

					$table->addRow(900);
					$table->addCell($widthTraining, $styleCell)->addText('');
					$table->addCell($widthTraining, array('gridSpan' => 2, 'valign' => 'center'))->addText(htmlspecialchars("Total ".$siteKey['countTraining']));
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars($siteKey['totalHourTraining']));
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars($siteKey['totalAttendeesraining']));
					$table->addCell($widthTraining, $styleCell)->addText('');
				}//end if


				//Events and Activites
				if ($siteKey['Event']){

					$section->addPageBreak();
					$section->addText(htmlspecialchars('Events and Activities'), $header);
					$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
					$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
					$styleCell = array('valign' => 'center');
					$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
					$fontStyle = array('bold' => true, 'align' => 'center');
					$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
					$table = $section->addTable('Fancy Table');
					$table->addRow(900);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Date'), $fontStyle);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Title'), $fontStyle);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Details'), $fontStyle);
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Duration (Days)'), $fontStyle);			
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Attendees'), $fontStyle);			
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars('Comment'), $fontStyle);		


					foreach ($siteKey['Event'] as $keyEvent) {
									# code...
								$table->addRow();
							    $table->addCell($widthTraining)->addText(htmlspecialchars($keyEvent['startDate']));
							    $table->addCell($widthTraining+200)->addText(htmlspecialchars($keyEvent['activityName']));
							    $desc = strlen($keyEvent['activityDescription']) > 200 ? substr($keyEvent['activityDescription'],0,200)."..." : $keyEvent['activityDescription'];
							    $table->addCell($widthTraining+500)->addText(htmlspecialchars($desc));
							    $table->addCell($widthTraining)->addText(htmlspecialchars($keyEvent['dayEvent']));
							    $table->addCell($widthTraining)->addText(htmlspecialchars($keyEvent['attendees']));
							    $table->addCell($widthTraining)->addText(htmlspecialchars(""));
								}	

					$table->addRow(900);
					$table->addCell($widthTraining, $styleCell)->addText('');
					$table->addCell($widthTraining, array('gridSpan' => 2, 'valign' => 'center'))->addText(htmlspecialchars("Total ".$siteKey['countEvent']));
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars($siteKey['totalDaysEvent']));
					$table->addCell($widthTraining, $styleCell)->addText(htmlspecialchars($siteKey['totalAttendeesEVent']));
					$table->addCell($widthTraining, $styleCell)->addText('');						
				}//end if
				//Activites Gallery
				$section->addPageBreak();
				$section->addText(htmlspecialchars('Activities Gallery'), $header);
				//var_dump($siteKey);
				//die;



				if($siteKey['album']){
					$tableStyle = array(
						'cellMarginRight' => 100,
						//'cellMarginTop' => 100,
						//'cellMarginBottom' => 100,
						'cellMarginLeft' => 100,
					);
					$phpWord->addTableStyle('Table Image Activities',$tableStyle);
					$table = $section->addTable('Table Image Activities'); 
					$table->addRow();
					$counterImage = 0;				
					//var_dump($siteKey['album']);
					//die;
					$counter = 0;
					foreach ($siteKey['album'] as $keyAlbum) {
						//var_dump(url::asset() . "/frontend/images/photo/" .$keyAlbum);
						//var_dump($keyAlbum);

						if($counter == 4){
							$table->addRow();
							$counter = 0;
						}


						$imagetmp = url::asset() . 
						"/frontend/images/photo/" .$keyAlbum['albumCoverImageName'];
						//var_dump($imagetmp);
						//die;

						$baseUrl	= url::getProtocol().apps::config("base_url:frontend");
						//var_dump($baseUrl);
						//die;
						$image = $baseUrl."/api/photo/small/". $keyAlbum['albumCoverImageName']. "";					
						//$image = model::load("image/services")->getResizedPhoto($keyAlbum['albumCoverImageName']);
						//var_dump(url::asset() . "/frontend/images/photo/" .$keyAlbum);
						//die;
						//var_dump($image);
						//die;
						# code...http://localhost/digitalgaia/iris/pim/assets/frontend/images/photo/2015/08/17/congkak.jpg
						//if($keyAlbum && file_exists($image)){
						if($keyAlbum && @getimagesize($image)){
							//var_dump($image);
							//if($height < $width){
								list($width, $height) = getimagesize($image); 
								$Dwidth = 150;
								$ratio = $Dwidth / $width;
								$Dheight = $height * $ratio;

								//if ($width > $height) {
									//die;
									// $section->addImage($image,array(
									// 	'width' => $Dwidth, 'height' => $Dheight,
									// 	//'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
								 //        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
								 //        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
									// 	));
									// $section->addText(htmlspecialchars($keyAlbum['albumName']));
									// $section->addText(htmlspecialchars($keyAlbum['albumDate']));


									$cell = $table->addCell(300);
									$textrun = $cell->createTextRun();
									
				        			$textrun->addImage($image,array(
										'width' => $Dwidth, 'height' => 150));
									//$textrun->addText($keyAlbum['albumName']);

									$image_text = $cell->addTable('image text'); 
									$image_text->addRow();
									$image_date = $image_text->addCell(1300);
									$textrun_date = $image_date->createTextRun();

									$date = $keyAlbum['albumDate'];
									$textrun_date->addText(date_format(new DateTime($date), 'd M Y'));									

									$image_title = $image_text->addCell(1700);
									//$image_title->addText('testing');
									$image_title->addText(htmlspecialchars($keyAlbum['albumName']));

									$counter++;
								//}// height < width
												
							//$section->addTextBreak();
									
						}	

						//$counter++;
					 }//end foreach album
				}//end if
				//die;

				/*$date = "2016-02-03";

				print_r($date);
				echo "<br><br>";
				print_r(new DateTime($date));
				echo "<br><br>";
				print_r("Date: " . date_format(new DateTime($date), 'd M Y'));*/

				//AJK PI1M
				$section->addPageBreak();
				$section->addText(htmlspecialchars('AJK PI1M'), $header);
				$imageajk = url::asset() . "/frontend/images/photo/" . $siteKey['ajk'];
						 if($siteKey['ajk'] != '' && @getimagesize($imageajk)){
						// 	//
							//var_dump($imageajk);
							list($width, $height) = getimagesize($imageajk); 
							$Dwidth = 550;
							$ratio = $Dwidth / $width;
							$Dheight = $height * $ratio;					 	
							$section->addImage($imageajk,array('width' => $Dwidth, 'height' => $Dheight));

						}		


				// Saving the document as OOXML file...
				$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

				//var_dump($siteKey);
				//die;

				// remove all kinds of possibly invalid characters and restrict to characters, digits and whitespace ...
			    $fileName = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $siteKey['siteName']);
			    // ... and remove control characters
			    $fileNameReal  = preg_replace('/[\x00-\x1F\x7F]/', '', $fileName);



				// if(!is_dir(path::files('reportGeneration/QuarterActivities/')))
				// 	mkdir(path::files('reportGeneration/QuarterActivities/'));

				// $folderpath = path::files('reportGeneration/QuarterActivities/tmp/');

				// if(!is_dir($folderpath))
				// 	mkdir($folderpath);


				//var_dump($folderpath);
				//die;
				//$objWriter->save($folderpath  . $siteKey['siteName'].'.docx');
				//ob_clean();
				$objWriter->save($docsPath .'/' . $fileNameReal.'.docx');
				//var_dump($report);

				$reportQuarterlyTable->siteCompletionIncrement();

				// 	if($counterSite == 5)
				// 		break;	

				// 	$counterSite++;	
				 //\PhpOffice\PhpWord\Media::resetElements();
			}//end site foreach
			//end site loop
			

			/*
			 * Note: it's possible to customize font style of the Text element you add in three ways:
			 * - inline;
			 * - using named font style (new font style object will be implicitly created);
			 * - using explicitly created font style object.
			 */


			


			//

			//$objWriter->save('php://output');
			// header('Content-Type: application/vnd.ms-word');	
			// header('Content-disposition: attachment; filename="QuarterlyReport.docx"');
			// //header('Content-Length: ' . filesize($folderpath . '/helloworld.docx'));				
			// readfile($folderpath . 'QuarterlyReport.docx');
			//unlink($folderpath . 'QuarterlyReport.docx');		


			//redirect::to("report/showQuarterlyReportPage","","");
			// http://stackoverflow.com/questions/19161611/generate-a-pseudo-random-6-character-string-from-an-integer
			$hash = function($num)
			{
				$scrambled = (240049382 * $num + 37043083) % 308915753;

		    	return base_convert($scrambled, 10, 26);
			};

			$reportQuarterlyTable->reportQuarterlyZipName = '['.$hash($reportId).'] QUARTERLY REPORT '.model::load("helper")->quarter(1, $quarter).'-'.$year.'.zip';

			$reportQuarterlyTable->updateState('zipping');

			// zip the reports all the reports.

			// begin the process.
			if(!is_dir(path::asset('backend/reports/quarterly-activities/')))
				mkdir(path::asset('backend/reports/quarterly-activities/'), 0777, true);
								
			$reportZip = new ZipArchive;

			$path = path::asset('backend/reports/quarterly-activities/'.$reportQuarterlyTable->reportQuarterlyZipName);
			
			$reportZip->open($path, ZIPARCHIVE::CREATE);

			$dirHandle = opendir($docsPath);

			while($file = readdir($dirHandle))
			{
				if(in_array($file, array('.', '..')))
					continue;

				$reportZip->addFile($docsPath.'/'.$file, $file);
			}

			$reportZip->close();

			$reportQuarterlyTable->reportQuarterlyZipSize = filesize($path);

			$reportQuarterlyTable->updateState('completed');

		}//end try

		catch(\Exception $e)
		{
			$reportQuarterlyTable->reportQuarterlyStatus = 2;

			$reportQuarterlyTable->updateState($e->getMessage());
		}		
		


		
	}	
}