<?php
class Controller_Ajax_Member
{	
	public function detail(){
		$data['user'] = model::load('site/member')->getUserMemberDetail(request::get('userID'));

		view::render('sitemanager/member/ajax/detail',$data);
	}
}
?>