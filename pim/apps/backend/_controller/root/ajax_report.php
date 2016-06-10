<?php

class Controller_Ajax_Report
{
	/**
	 * List of generated reports (and pending)
	 */
	public function monthlyActivityReports($year = null, $month = null)
	{
		$query = db::from('report_monthly');

		$query->where('reportMonthlyYear', $year);

		$query->where('reportMonthlyMonth', $month);

		$query->order_by('reportMonthlyUpdatedDate DESC');

		$reports = $query->get()->result();

		$report = db::from('report_monthly')->where('reportMonthlyStatus', 0)->get()->row();

		view::render('root/report/ajax/monthlyActivityReports', array(
			'reports' => $reports,
			'pending_report' => $report,
			'month' => $month,
			'year' => $year,
			'totalApprovalPendingReport' => model::load("blog/article")->getTotalApprovalPendingReport($year, $month),
			'totalNonrecentReport' => model::load('blog/article')->getTotalOfNonrecentReport($year, $month)
			));
	}

	/**
	 * Main method to generate report
	 */
	public function monthlyActivityGenerate($year, $month)
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

		$totalArticle = $this->queryArticle($month, $year)->select('count(*) as total')->get()->row('total');

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

	protected function queryArticle($month, $year)
	{
		db::from("activity_article");
		db::join("activity","activity_article.activityID = activity.activityID");
		db::join("article","activity_article.articleID = article.articleID");
		db::join('site', 'site.siteID = activity.siteID');
		db::where('activity_article.articleID IN (SELECT articleID FROM article WHERE articleStatus = ?)', array(1));
		db::where('activity.activityType', 1);
		db::where('year(activity.activityStartDate)', $year);
		$db = db::where('month(activity.activityStartDate)', $month);

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

			$articles = $this->queryArticle($month, $year)->get()->result();

			$report->reportMonthlyArticleTotal = count($articles);

			$report->save();

			foreach($articles as $no => $row)
			{
				PhpOffice\PhpWord\Media::resetElements();

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

						$tx = str_replace('&nbsp;', '', $tx);

						$tx = strip_tags(trim($tx));

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
}