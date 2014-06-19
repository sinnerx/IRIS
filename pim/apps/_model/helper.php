<?php
namespace model;
use HTMLPurifier,HTMLPurifier_Config;
class Helper
{
	public function slugify($item)
	{
		$item 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $item);
		$item	= str_replace(" ", "-",$item);	# replace space with -
		$item	= strtolower($item);			# lowercase.

		return $item;
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
			return date("d/m/Y",strtotime($dateR[0]))." - ".date("d/m/Y",strtotime($dateR[1]));
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
}
?>