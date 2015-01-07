<?php
class Controller_Redirect
{
	public function userActivity($type,$userActivityID)
	{
		$link	= model::load("user/activity")->getFrontendLink($type,$userActivityID);

		## create link based on type.
		redirect::to($link);

	}

	public function general($type)
	{
		switch($type)
		{
			case "profile":
			$userID	= request::get("user");

			$link = model::load("site/member")->createProfileLink($userID);

			if(!$link)
				redirect::to("404");

			redirect::to($link);
			break;
		}
	}

	public function link()
	{
		$link	= request::get("link");
		
		## if no protocol provided.
		if(strpos($link, "http") !== 0 && strpos($link, "https") !== 0)
			$link = "http://$link";

		redirect::to("$link");
	}

	/**
	 * A proxy from facebook, to help redirect to localhost based link.
	 */
	public function fbProxified()
	{
		$gets = array();

		// rebuild _get from fb.
		foreach(request::get() as $key=>$val)
		{
			// rebuild.
			if($key == "link")
			{
				$redirectUrl = base64_decode($val);
			}
			else
			{
				$gets[] = $key."=".$val;
			}
		}

		$parsedUrl = parse_url($redirectUrl);

		// rebuild!
		$redirectUrl = $redirectUrl. (isset($parsedUrl['query']) ? "&" : "?"). implode("&", $gets);

		redirect::to($redirectUrl);
	}
}