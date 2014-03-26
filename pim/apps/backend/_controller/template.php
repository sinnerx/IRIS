<?php

Class Controller_Template
{
	## hooked at pre_template.
	public function index()
	{
		$user			= model::load("user");
		$row_user		= $user->get(session::get("userID"));

		$data['userFullName']	= ucfirst($row_user['userProfileFullName']);
		$data['userLevel']		= ucfirst($user->levelLabel($row_user['userLevel']));
		$data['dashboardTitle']	= session::get("userLevel") == 2?model::load("site")->getSiteByManager(null,"siteName"):"P1M Dashboard";

		## return data to template.
		return $data;
	}
}


?>