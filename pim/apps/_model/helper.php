<?php
namespace model;
use HTMLPurifier,HTMLPurifier_Config;
class Helper
{
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

		/*$total	= Array();
		$iwant	= $wordsCount;
		$sum	= Array();

		## explode by <
		foreach(explode("<",$html) as $tagg)
		{
			## reached total
			if(count($total)+1 > $iwant)
			{
				break;
			}
			
			$tag	= trim($tagg);
			## check closing existence.
			if(strpos($tag, ">") !== false)
			{
				## if within the same atom itself got the closing tag, skip count.
				if($tag[strlen($tag)-1] == ">")
				{
					$sum[]	= $tagg;
					## skip not count as words.
					continue;
				}

				$wordR	= explode(">",$tag);

				## remove first '>'
				$before	= array_shift($wordR);

				## loops all probability because of having many '>'
				$words	= str_replace(">"," ", $wordR[0]);

				## explode by space and push the words.
				$wordsR	= explode(" ",$words);
				$first	= false;
				$empty	= 0;
				foreach($wordsR as $word)
				{
					if($word != "")
					{
						if($first == false)
						{
							$first	= $word;
						}

						## reached total
						$total[]	= $word;
					}
					else
					{
						if($first == false)
						{
							$empty++;
						}
					}
				}
				if(count($total) > $iwant)
				{
					$sum[]	= $before.">".str_repeat(" ", $empty).$first;
				}
				else
				{
					$sum[]	= $tagg;
				}
			}
			## not found closing at all.
			else
			{
				$sum[]	= $tagg;
				## push words.
				foreach(explode(" ",$tag) as $word)
				{
					if($word != "")
					{
						## reached total
						$total[]	= $word;
					}
				}
			}
		}

		## use library HTMLPurifier.
		define("HTMLPURIFIER_PREFIX","vendor/spekkionu/htmlpurifier");
		$purifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());

		return $purifier->purify(implode("<",$sum).($wordsCount?"...":""));*/
	}
}
?>