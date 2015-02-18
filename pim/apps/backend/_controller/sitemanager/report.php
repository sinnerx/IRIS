<?php
class Controller_Report
{
	public function activityReport($year = null,$month = null)
	{

	
		$data['year'] = $year = $year ? : date("Y");
		$data['month'] = $month = $month ? : date("n");


		$siteID	  = authData('site.siteID');
		$siteName = authData ('site.siteName');

		$reports	= model::load("blog/article")->getReportBySiteID($siteID,$year,$month);
		$data['list'] = $reports;

		view::render("sitemanager/report/activityReport",$data);
	}

	

	public function generateActivityReport()
	{

		$siteID	  = authData('site.siteID');
		$siteName = authData ('site.siteName');
		$articleID = request::get("articleID");



		$reports	= model::load("blog/article")->getReportBySiteID($siteID,$year,$month,$articleID);



		foreach($reports as $no=>$report)
			{
		
			 	$dt = new DateTime($report['activityStartDate']);
				$date = $dt->format('dmY');
				$articleName =	$report['articleName'];
				$articleID = $report['articleID'];
				$articleText = $report['articleText'];
	

			}

		$fileName = $siteName." - ".$date." - ".$articleName;

		$word	= new \PhpOffice\PhpWord\PhpWord();
		$WordHelper	= new model\report\PHPWordHelper($word,$fileName.".docx");

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
		  
					if ($key == ($lastValue-1)){
						$finalHtml= $finalArray[$key];
						\PhpOffice\PhpWord\Shared\Html::addHtml($section, $finalHtml);
		  
					} else {
		
						$finalHtml= $finalArray[$key]."</p>";	
						\PhpOffice\PhpWord\Shared\Html::addHtml($section, $finalHtml);
						 
					}
					
		
				}  else {
		
					if ($key != ($lastValue-1)){
						foreach ($tags as $tag) {
			
		       				$imageLink = $tag->getAttribute('src');
		       				
		       				$section->addImage($imageLink);
		       	
						}
					}				
			
				}		
		}

	
     	$WordHelper->execute();
                     

	}

	
}


?>