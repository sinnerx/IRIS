<?php
class Controller_Ajax_Member
{	
	public function detail(){
		$data['user'] = model::load('site/member')->getUserMemberDetail(request::get('userID'),model::load('access/auth')->getAuthData('site','siteID'));

		view::render('sitemanager/member/ajax/detail',$data);
	}
}
?>