<?php

class Controller_Ajax_Activity
{
	public function join($activityID,$date = null)
	{
		$userID	= session::get("userID");
		$result	= model::load("activity/activity")->join($userID,$activityID,$date);

		if(!$result[0])
			return response::json(Array(false,$result[1]));

		## return true, along with image.
		return response::json(Array(true,Array("userProfileAvatarPhoto"=>model::load("image/services")->getPhotoUrl(authData("user.userProfileAvatarPhoto")))));
	}
}