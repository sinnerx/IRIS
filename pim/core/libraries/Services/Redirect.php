<?php
class Redirect
{
	## redirect to location.
	private static $flashMessageR	= Array();
	public function to($loc = Null,$message = Null,$type = "success")
	{
		## prepare base url, if relative, redirect using url::base. if absolute. just use it.
		$base_url	= strpos($loc,'//') === 0 || strpos($loc, 'http://') === 0 || strpos($loc, 'https://') === 0?$loc:url::base($loc);

		$protocol	= !apps::config("secure")?"http":"https";	## secure check, else use normal http
		$url		= !$loc || $loc == ""?$protocol."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']:$base_url;
	
		#$url	= $protocol."://".$url;
		
		## execute store message, if got any message in flashMessage;
		if($message)
		{
			self::message($message,$type);
		}

		if(apps::environment() != "dev" || !error::check())
		{
			self::messageStore();
			header("location:$url");
			die();
		}
	}

	## an alias to flash::set()
	public function withFlash($data,$msg = null)
	{
		flash::set($data,$msg);
	}

	public function message($name,$message = "success")
	{
		if(in_array($message, Array("success","error")))
		{
			$type		= $message;
			$message	= $name;
			$name		= "_main";

			switch($type)
			{
				case "success":
				$message	= "<div class='alert alert-success'>$message</div>";
				break;
				case "error":## for the case of returning back to itself. NEVER USE IT TO RETURN TO OTHER. RED
				$message	= "<div class='alert alert-danger'>$message</div>";
				break;
			}
		}

		self::$flashMessageR[]	= Array($name,$message);
	}

	private function messageStore()
	{
		if(count(self::$flashMessageR) == 0)
		{
			return;
		}

		foreach(self::$flashMessageR as $messageR)
		{
			flash::set($messageR[0],$messageR[1]);
		}
	}	
}


?>