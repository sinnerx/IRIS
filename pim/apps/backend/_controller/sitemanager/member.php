<?php
class Controller_Member
{
	public function lists($page = 1)
	{
		if(request::get("toggle")){
			model::load('site/member')->approveMember(request::get("toggle"));

			redirect::to("member/lists","Updated status.");
		}

		$data['user'] = model::load('site/member')->getPaginatedList(
			model::load('access/auth')->getAuthData('site','siteID'), 
			Array(
				'urlFormat'=>url::base("member/lists/{page}"),
				'currentPage'=>$page
			)
		);

		view::render("sitemanager/member/lists",$data);
	}
}