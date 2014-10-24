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
}