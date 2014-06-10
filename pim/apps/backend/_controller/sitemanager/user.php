<?php
class Controller_User
{
	public function lists()
	{
		$data['user'] = model::load('site/member')->getPaginatedList(1,1);
		echo '<pre>';print_r($data);die;
		view::render("sitemanager/user/lists",$data);
	}
}