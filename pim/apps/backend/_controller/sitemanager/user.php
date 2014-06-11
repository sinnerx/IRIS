<?php
class Controller_User
{
	public function lists($page = 1)
	{
		if(request::get("toggle")){
			model::load('site/member')->approveMember(request::get("toggle"));

			redirect::to("user/lists","Updated member.");
		}

		$data['user'] = model::load('site/member')->getPaginatedList(
			model::load('access/auth')->getAuthData('site','siteID'), 
			Array(
				'urlFormat'=>url::base("user/lists/{page}"),
				'currentPage'=>$page
			)
		);
		//echo '<pre>';print_r($data['user']);die;

		view::render("sitemanager/user/lists",$data);
	}
}