<?php
class Controller_Redirect
{
	public function userActivity($type,$userActivityID)
	{
		$link	= model::load("user/activity")->getFrontendLink($type,$userActivityID);

		## create link based on type.
		redirect::to($link);

	}
}