<?php
namespace model;
use HTMLPurifier,HTMLPurifier_Config, model;
class Helper
{
	public function slugify($item)
	{
		$item 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $item);
		$item	= str_replace(" ", "-",$item);	# replace space with -
		$item	= strtolower($item);			# lowercase.

		return $item;
	}

	public function quarter($type, $no = null)
	{

		$listQuarter = Array(
			1 => Array(
				1 => "First Quarter",
				2 => "Second Quarter",
				3 => "Third Quarter",
				4 => "Fourth Quarter"
				),
			2 => Array(
				1 => "1st",
				2 => "2nd",
				3 => "3rd",
				4 => "4th",
				),
			3 => Array(
				1 => "JANUARY - MARCH",
				2 => "APRIL - JUNE",
				3 => "JULY - SEPTEMBER",
				4 => "OCTOBER - DECEMBER",
				),
			4 => Array(
				1 => Array(
					1 => "January",
					2 => "February",
					3 => "March",
					),
				2 => Array(
					1 => "April",
					2 => "May",
					3 => "June",
					),					
				3 => Array(
					1 => "July",
					2 => "August",
					3 => "September",
					),				
				4 => Array(
					1 => "October",
					2 => "November",
					3 => "December",
					),				
				),
			);		

		return !$no?$listQuarter[$type] : $listQuarter[$type][$no];
	}

	public function state($no = null)
	{
		$state	= Array(
				1=>"Johor",
				2=>"Kedah",
				3=>"Kelantan",
				4=>"Melaka",
				5=>"Negeri Sembilan",
				6=>"Pahang",
				7=>"Pulau Pinang",
				8=>"Perak",
				9=>"Perlis",
				10=>"Selangor",
				11=>"Terengganu",
				12=>"Sabah",
				13=>"Sarawak",
				14=>"Kuala Lumpur",
				15=>"Labuan",
				16=>"Putrajaya"
						);

		return !$no?$state:$state[$no];
	}

	public function occupationGroup($no = null)
	{
		$occR	= Array(
				1=>"Pelajar",
				2=>"Suri-rumah",
				3=>"Kerja sendiri",
				4=>"Di bawah majikan",
				5=>"Tidak bekerja",
				6=>"Bersara"
						);

		return $no?$occR[$no]:$occR;
	}

	public function monthYear($type,$firstParam = null,$secondParam = null)
	{
		switch($type)
		{
			case "month":
				$arrR	= Array(
					1=>"JANUARI",
					2=>"FEBRUARI",
					3=>"MAC",
					4=>"APRIL",
					5=>"MEI",
					6=>"JUN",
					7=>"JULAI",
					8=>"OGOS",
					9=>"SEPTEMBER",
					10=>"OKTOBER",
					11=>"NOVEMBER",
					12=>"DISEMBER"
							);

				if($firstParam)
				{
					return $arrR[$firstParam];
				}
			break;
			case "monthE":
				$arrR	= Array(
					1=>"JANUARY",
					2=>"FEBRUARY",
					3=>"MARCH",
					4=>"APRIL",
					5=>"MAY",
					6=>"JUNE",
					7=>"JULY",
					8=>"AUGUST",
					9=>"SEPTEMBER",
					10=>"OCTOBER",
					11=>"NOVEMBER",
					12=>"DECEMBER"
							);

				if($firstParam)
				{
					return $arrR[$firstParam];
				}
			break;
			case "year":
				$startY	= !$firstParam?date("Y")-5:$firstParam;
				$endY	= !$secondParam?date("Y")+5:$secondParam;

				for($i=$startY;$i<$endY;$i++)
				{
					$arrR[$i]	= $i;
				}
			break;
		}
		
		return $arrR;
	}

	## convert date range [startD,endD] to label (d/m/Y - d/m/Y)
	public function dateRangeLabel($dateR,$invert = false)
	{
		if(!$invert)
		{
			if(date("Y-m-d",strtotime($dateR[0])) == date("Y-m-d",strtotime($dateR[1]))) ## if same start and end date, just return one,
				return date("j M Y",strtotime($dateR[0]));
			else
				return date("j M Y",strtotime($dateR[0]))." - ".date("j M Y",strtotime($dateR[1]));
		}
		else
		{
			$dateR	= explode(" - ",$dateR);

			$newD	= Array();
			foreach($dateR as $d)
			{
				list($d,$m,$y)	= explode("/",$d);
				$newD[]	= "$y-$m-$d";
			}

			return $newD;
		}
	}

	## 
	public function hashPassword($val,$salt = null)
	{
		## create a simple salt if salt isn't passed..
		$salt	= $salt?$salt:sha1($val.substr($val, 0,4));
		return md5($salt.$val);
	}

	## temporary fix.
	public function purifyHTML($text,$count = null)
	{
		## just... return a stripped text..
		$stripped	= strip_tags($text);
		$stripped	= implode(" ",array_slice(explode(" ",$stripped),0,90))."...";
		
		return strip_tags($stripped);

		## replace all block closing tags with line break.
		$blocktags	= Array("p","div");

		foreach($blocktags as $tagname)
		{
			$opentags[]	= "<$tagname";
			$opentags_replace[]	= "|<br><$tagname";

			$closetags[]	= "</$tagname>";
			$closetags_replace = "<br>|";
		}

		## replace
		$text	= str_replace($opentags, $opentags_replace, $text);
		$text	= str_replace($closetags, $closetags_replace, $text);

		## replace any combined block.
		$text	= str_replace("<br>||<br>","<br>",$text);

		## replace any lonely opening with normal tags. so that later it will be stripped.
		$text	= str_replace("|<br>", " <br> ", $text);
		$text	= str_replace("<br>|"," <br> ",$text);

		## strip all tags except <br>
		$text	= strip_tags($text,"<br>");
		$text	= implode(" ",array_slice(explode(" ",$text),0,90))."...";
		$text	= trim($text);

		## replace first <br> with null, perhaps text started with <br> .
		$text	= strpos(trim($text), "<br>") === 0?substr($text,4):$text;

		## final replace any combined <br> to one.
		$text	= str_replace("<br><br>", "<br>", $text);

		return nl2br($text);
	}

	## extract date range into specific date, and assign data to each.
	public function extractDateRange(&$data,$val,$range)
	{
		$start	= date("Y-m-d",strtotime($range[0]));
		$end	= date("Y-m-d",strtotime($range[1]));

		$currT	= strtotime($start);

		while($currT <= strtotime($end))
		{
			if(!$data[date("j",$currT)])
			{
				$data[date("j",$currT)] = Array();
			}

			## add val into array.
			$data[date("j",$currT)][]	= $val;

			## increment.
			$currT	= strtotime("+1 day",$currT);
		}
	}

	## 
	public function buildDateBasedUrl($slug,$date,$prefix = null)
	{
		$yearmonth	= date("Y/m",strtotime($date));
		$uri	= $yearmonth."/".$slug;

		return $prefix?trim($prefix,"/")."/".$uri:$uri;
	}

	## return a replaced array based on data1
	public function replaceArray($data1,$data2)
	{
		$newD	= Array();
		foreach($data1 as $key=>$val)
		{
			$newD[$key]	= isset($data2[$key])?$data2[$key]:$data1[$key];
		}
		return $newD;
	}

	public function getImgFromText($text)
	{
		if(strpos($text,'<img') !== false)
		{
			$offset	= strpos($text,'<img');
			$length = (strpos($text,'alt',$offset)-4)-(strpos($text,'src')+2);
			$start = (strpos($text,'src=')+5);
			$img	= substr($text,$start,$length);
		}
		else
		{
			$img 	= model::load("image/services")->getPhotoUrl(null);
		}

		return $img;
	}

	function frontendDate($date,$format = "%d %B %Y")
	{
		return strftime($format,strtotime($date));
	}

	function frontendDatetime($datetime)
	{
		return $this->frontendTime($datetime).", ".$this->frontendDate($datetime);
	}

	function frontendTime($time)
	{
		return date("g:i a",strtotime($time));
	}
}
?>