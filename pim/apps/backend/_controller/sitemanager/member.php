<?php
class Controller_Member
{
	public function lists($page = 1)
	{
		if(request::get("toggle")){
			model::load('site/member')->approveMember(request::get("toggle"));

			redirect::to("member/lists","Updated status.");
		}

		$where	= null;
		
		if(request::get("search"))
		{
			## ic.
			if(is_numeric(request::get("search")))
			{
				$where['site_member.userID IN (SELECT userID FROM user WHERE userIC = ?)']	= Array(request::get("search"));
			}
			## name.
			else
			{
				$where['site_member.userID IN (SELECT userID FROM user_profile WHERE userProfileFullName LIKE ?)']	= Array("%".request::get("search")."%");
			}
		}

		$data['user'] = model::load('site/member')->getPaginatedList(
			model::load('access/auth')->getAuthData('site','siteID'), 
			Array(
				'urlFormat'=>url::base("member/lists/{page}"),
				'currentPage'=>$page
			),$where
		);

		view::render("sitemanager/member/lists",$data);
	}
}