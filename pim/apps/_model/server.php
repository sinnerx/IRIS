<?php
namespace model;

class Server
{
	private static $announcement = null;

	public static function hasAnnouncement($date)
	{
		$file	= ROOT_FOLDER."/server/announcement.json";
		if(!file_exists($file))
			return false;

		$file	= json_decode(file_get_contents($file),true);
		$found = false;

		foreach($file as $dates=>$text)
		{
			list($start,$end)	= explode(" - ",$dates);
			$start	= trim($start);
			if(!$end)
			{
				if($start == $dates)
				{
					return $text;
					break;
				}

			}
			else
			{
				$end	= trim($end);

				if($start <= $date && $end >= $date)
				{
					return $text;
				}
			}


		}
	}

	## announcement acrooss the site.
	public static function setAnnouncement($text)
	{
		self::$announcement = $text;
	}

	public static function getAnnouncement()
	{
		return self::$announcement;
	}
}



?>