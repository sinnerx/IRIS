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
	public function race($no = null)
	{
		$occR	= Array(
				1=>"Malay",
				2=>"Chinese",
				3=>"Indian",
				4=>"Others",
				// 4=>"Dayak",
				// 5=>"Iban",
				// 6=>"Bidayuh",
				// 7=>"Orang Ulu",
				// 8=>"Kadazan",
				// 9=>"Bajau",
				// 10=>"Murut",
				// 11=>"Temer",
				// 12=>"Chitty",
						);

		return $no?$occR[$no]:$occR;
	}

	public function race($no = null)
	{
		$occR	= Array(
				1=>"Malay",
				2=>"Chinese",
				3=>"Indian",
				4=>"Others",
				// 4=>"Dayak",
				// 5=>"Iban",
				// 6=>"Bidayuh",
				// 7=>"Orang Ulu",
				// 8=>"Kadazan",
				// 9=>"Bajau",
				// 10=>"Murut",
				// 11=>"Temer",
				// 12=>"Chitty",
						);

		return $no?$occR[$no]:$occR;
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

	public function district($state =null)
	{
		switch ($state) {
			case 1:
				$district	= Array(
				1=>"Johor Bahru",
				2=>"Batu Pahat",
				3=>"Kluang",
				4=>"Kulai Jaya",
				5=>"Muar",
				6=>"Kota Tinggi",
				7=>"Segamat",
				8=>"Pontian",
				9=>"Ledang"
						);
				break;

			case 2:
				$district	= Array(
				1=>"Sungai Petani",
				2=>"Alor Setar",
				3=>"Kulim",
				4=>"Kubang Pasu",
				5=>"Baling",
				6=>"Pendang",
				7=>"Langkawi",
				8=>"Yan",
				9=>"Sik",
				10=>"Padang Terap",
				11=>"Pokok Sena",
				12=>"Bandar Baharu"
						);
				break;

			case 3:
				$district	= Array(
				1=>"Kota Bharu",
				2=>"Pasir Mas",
				3=>"Tumpat",
				4=>"Bachok",
				5=>"Tanah Merah",
				6=>"Pasir Puteh",
				7=>"Kuala Krai",
				8=>"Machang",
				9=>"Gua Musang",
				10=>"Jeli"
						);
				break;

			case 4:
				$district	= Array(
				1=>"Melaka Tengah",
				2=>"Alor Gajah",
				3=>"Jasin"
						);
				break;

			case 5:
				$district	= Array(
				1=>"Seremban",
				2=>"Jempol",
				3=>"Port Dickson",
				4=>"Tampin",
				5=>"Kuala Pilah",
				6=>"Rembau",
				7=>"Jelebu"
						);
				break;

			case 6:
				$district	= Array(
				1=>"Kuantan",
				2=>"Temerloh",
				3=>"Bentong",
				4=>"Maran",
				5=>"Rompin",
				6=>"Pekan",
				7=>"Bera",
				8=>"Raub",
				9=>"Jerantut",
				10=>"Lipis",
				11=>"Cameron Highlands"
						);
				break;

			case 7:
				$district	= Array(
				1=>"Timur Laut Pulau Pinang",
				2=>"Seberang Perai Tengah",
				3=>"Seberang Perai Utara",
				4=>"Barat Daya Pulau Pinang",
				5=>"Seberang Perai Selatan"
						);
				break;

			case 8:
				$district	= Array(
				1=>"Kinta",
				2=>"Larut, Matang dan Selama",
				3=>"Manjung",
				4=>"Hilir Perak",
				5=>"Kerian",
				6=>"Batang Padang",
				7=>"Kuala Kangsar",
				8=>"Perak Tengah",
				9=>"Hulu Perak",
				10=>"Kampar",
				11=>"Muallim"
						);
				break;

			case 9:
				$district	= Array(
				1=>"Perlis"
					);
				break;

			case 10:
				$district	= Array(
				1=>"Gombak",
				2=>"Hulu Langat",
				3=>"Hulu Selangor",
				4=>"Klang",
				5=>"Kuala Langat",
				6=>"Kuala Selangor",
				7=>"Petaling",
				8=>"Sabak Bernam",
				9=>"Sepang"
						);
				break;

			case 11:
				$district	= Array(
				1=>"Besut",
				2=>"Dungun",
				3=>"Hulu Terengganu",
				4=>"Kemaman",
				5=>"Kuala Terengganu",
				6=>"Marang",
				7=>"Setiu",
				8=>"Kuala Nerus"
						);
				break;

			case 12:
				$district	= Array(
				1=>"Pantai Barat (Kota Kinabalu)",
				2=>"Tawau",
				3=>"Sandakan",
				4=>"Pedalaman (Keningau)",
				5=>"Kudat"
						);
				break;

			case 13:
				$district	= Array(
				1=>"Kuching",
				2=>"Miri",
				3=>"Sibu",
				4=>"Bintulu",
				5=>"Samarahan",
				6=>"Sarikei",
				7=>"Kapit",
				8=>"Mukah",
				9=>"Betong",
				10=>"Sri Aman",
				11=>"Serian",
				12=>"Limbang"
						);
				break;

			case 14:
				$district	= Array(
				1=>"Kuala Lumpur"
						);
				break;

			case 15:
				$district	= Array(
				1=>"Labuan"
						);
				break;

			case 16:
				$district	= Array(
				1=>"Putrajaya"
						);
				break;

			default:
				$district=array();
				break;
		}

		return $district;
	}

	public function parliament($state = null)
	{
		switch ($state) {
			case 9:
				$parliament	= Array(
				1=>"Padang Besar",
				2=>"Arau",
				3=>"Kangar"
						);
				break;

			case 2:
				$parliament	= Array(
				1=>"Langkawi",
				2=>"Jerlun",
				3=>"Kubang Pasu",
				4=>"Padang Terap",
				5=>"Pokok Sena",
				6=>"Alor Setar",
				7=>"Kuala Kedah",
				8=>"Pendang",
				9=>"Jerai",
				10=>"Sik",
				11=>"Merbok",
				12=>"Sungai Petani",
				13=>"Baling",
				14=>"Padang Serai",
				15=>"Kulim-Bandar Baharu"
						);
				break;

			case 3:
				$parliament	= Array(
				1=>"Tumpat",
				2=>"Pengkalan Chepa",
				3=>"Kota Bharu",
				4=>"Pasir Mas",
				5=>"Rantau Panjang",
				6=>"Kubang Kerian",
				7=>"Bachok",
				8=>"Ketereh",
				9=>"Tanah Merah",
				10=>"Pasir Puteh",
				11=>"Machang",
				12=>"Jeli",
				13=>"Kuala Krai",
				14=>"Gua Musang"
						);
				break;

			case 11:
				$parliament	= Array(
				1=>"Besut",
				2=>"Setiu",
				3=>"Kuala Nerus",
				4=>"Kuala Terengganu",
				5=>"Marang",
				6=>"Hulu Terengganu",
				7=>"Dungun",
				8=>"Kemaman"
						);
				break;

			case 7:
				$parliament	= Array(
				1=>"Kepala Batas",
				2=>"Tasek Gelugor",
				3=>"Bagan",
				4=>"Permatang Pauh",
				5=>"Bukit Mertajam",
				6=>"Batu Kawan",
				7=>"Nibong Tebal",
				8=>"Bukit Bendera",
				9=>"Tanjong",
				10=>"Jelutong",
				11=>"Bukit Gelugor",
				12=>"Bayan Baru",
				13=>"Balik Pulau"
						);
				break;

			case 8:
				$parliament	= Array(
				1=>"Gerik",
				2=>"Lenggong",
				3=>"Larut",
				4=>"Parit Buntar",
				5=>"Bagan Serai",
				6=>"Bukit Gantang",
				7=>"Taiping",
				8=>"Padang Rengas",
				9=>"Sungai Siput",
				10=>"Tambun",
				11=>"Ipoh Timur",
				12=>"Ipoh Barat",
				13=>"Batu Gajah",
				14=>"Kuala Kangsar",
				15=>"Pekan Beruas",
				16=>"Parit",
				17=>"Kampar",
				18=>"Gopeng",
				19=>"Tapah",
				20=>"Pasir Salak",
				21=>"Lumut",
				22=>"Bagan Datoh",
				23=>"Teluk Intan",
				24=>"Tanjung Malim"
						);
				break;

			case 6:
				$parliament	= Array(
				1=>"Cameron Highlands",
				2=>"Lipis",
				3=>"Raub",
				4=>"Jerantut",
				5=>"Indera Mahkota",
				6=>"Kuantan",
				7=>"Paya Besar",
				8=>"Pekan",
				9=>"Maran",
				10=>"Kuala Krau",
				11=>"Temerloh",
				12=>"Bentong",
				13=>"Bera",
				14=>"Rompin"
						);
				break;

			case 10:
				$parliament	= Array(
				1=>"Sabak Bernam",
				2=>"Sungai Besar",
				3=>"Hulu Selangor",
				4=>"Tanjong Karang",
				5=>"Kuala Selangor",
				6=>"Selayang",
				7=>"Gombak",
				8=>"Ampang",
				9=>"Pandan",
				10=>"Hulu Langat",
				11=>"Serdang",
				12=>"Puchong",
				13=>"Kelana Jaya",
				14=>"Petaling Jaya Selatan",
				15=>"Petaling Jaya Utara",
				16=>"Subang",
				17=>"Shah Alam",
				18=>"Kapar",
				19=>"Klang",
				20=>"Kota Raja",
				21=>"Kuala Langat",
				22=>"Sepang"
						);
				break;

			case 14:
				$parliament	= Array(
				1=>"Kepong",
				2=>"Batu",
				3=>"Wangsa Maju",
				4=>"Segambut",
				5=>"Setiawangsa",
				6=>"Titiwangsa",
				7=>"Bukit Bintang",
				8=>"Lembah Pantai",
				9=>"Seputeh",
				10=>"Cheras",
				11=>"Bandar Tun Razak"
						);
				break;

			case 16:
				$parliament	= Array(
				1=>"Putrajaya"
						);
				break;

			case 5:
				$parliament	= Array(
				1=>"Jelebu",
				2=>"Jempol",
				3=>"Seremban",
				4=>"Kuala Pilah",
				5=>"Rasah",
				6=>"Rembau",
				7=>"Telok Kemang",
				8=>"Tampin"
						);
				break;

			case 4:
				$parliament	= Array(
				1=>"Masjid Tanah",
				2=>"Alor Gajah",
				3=>"Tangga Batu",
				4=>"Bukit Katil",
				5=>"Kota Melaka",
				6=>"Jasin"
						);
				break;

			case 1:
				$parliament	= Array(
				1=>"Segamat",
				2=>"Sekijang",
				3=>"Labis",
				4=>"Pagoh",
				5=>"Ledang",
				6=>"Bakri",
				7=>"Muar",
				8=>"Parit Sulong",
				9=>"Ayer Hitam",
				10=>"Sri Gading",
				11=>"Batu Pahat",
				12=>"Simpang Renggam",
				13=>"Kluang",
				14=>"Sembrong",
				15=>"Mersing",
				16=>"Tenggara",
				17=>"Kota Tinggi",
				18=>"Pengerang",
				19=>"Tebrau",
				20=>"Pasir Gudang",
				21=>"Johor Bahru",
				22=>"Pulai",
				23=>"Gelang Patah",
				24=>"Kulai",
				25=>"Pontian",
				26=>"Tanjong Piai"
						);
				break;

			case 15:
				$parliament	= Array(
				1=>"Labuan"
						);
				break;

			case 12:
				$parliament	= Array(
				1=>"Kudat",
				2=>"Kota Marudu",
				3=>"Kota Belud",
				4=>"Tuaran",
				5=>"Sepanggar",
				6=>"Kota Kinabalu",
				7=>"Putatan",
				8=>"Penampang",
				9=>"Papar",
				10=>"Kimanis",
				11=>"Beaufort",
				12=>"Sipitang",
				13=>"Ranau",
				14=>"Keningau",
				15=>"Tenom",
				16=>"Pensiangan",
				17=>"Beluran",
				18=>"Libaran",
				19=>"Batu Sapi",
				20=>"Sandakan",
				21=>"Kinabatangan",
				22=>"Silam",
				23=>"Semporna",
				24=>"Tawau",
				25=>"Kalabakan"
						);
				break;

			case 13:
				$parliament	= Array(
				1=>"Mas Gading",
				2=>"Santubong",
				3=>"Petra Jaya",
				4=>"Bandar Kuching",
				5=>"Stampin",
				6=>"Kota Samarahan",
				7=>"Mambong",
				8=>"Serian",
				9=>"Batang Sadong",
				10=>"Batang Lupar",
				11=>"Sri Aman",
				12=>"Lubok Antu",
				13=>"Betong",
				14=>"Saratok",
				15=>"Tanjung Manis",
				16=>"Igan",
				17=>"Sarikei",
				18=>"Julau",
				19=>"Kanowit",
				20=>"Lanang",
				21=>"Sibu",
				22=>"Mukah",
				23=>"Selangau",
				24=>"Kapit",
				25=>"Hulu Rajang",
				26=>"Bintulu",
				27=>"Sibuti",
				28=>"Miri",
				29=>"Baram",
				30=>"Limbang",
				31=>"Lawas"
						);
				break;

			
			
			default:
				$parliament	= array();
				break;
		}

		return $parliament;
	}

	public function occupationGroup($no = null)
	{
		$occR	= Array(
				1=>"Pelajar",
				2=>"Suri-rumah",
				3=>"Kerja sendiri",
				4=>"Di bawah majikan",
				5=>"Tidak bekerja",
				6=>"Bersara",
				7=>"Bukan Pelajar",
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

	public function sortBy($field, $array, $direction = 'asc')
	{
	    usort($array, create_function('$a, $b', '
	        $a = $a["' . $field . '"];
	        $b = $b["' . $field . '"];

	        if ($a == $b)
	        {
	            return 0;
	        }

	        return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
	    '));

	    return $array;
	}
}
?>