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

	public function changePassword()
	{
		if(form::submitted())
		{
			$userIC	= input::get("userIC");

			$rules	= Array(
					"_all"					=>"required:This field is required.",
					"userIC"				=>Array(
						"callback"			=>Array(model::load("user/user")->checkIC(input::get("userIC")),"IC not found.")
						),
					"userPassword_confirm"	=>Array(
						"callback"=>Array(input::get("userPassword_confirm") == input::get("userPassword"),"Please confirm the password")
						)
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("member/changePassword","Please complete the field.","error");
			}

			## change password.
			model::load("user/user")->changePasswordByIC(input::get("userIC"),input::get("userPassword"));

			## check member, whether he already registered, or just in temporary_user table.
			#$checkmember	= model::load("site/member")->checkMember(authData("site.siteID"),$userIC);

			redirect::to("member/changePassword","Successfully updated to '".input::get("userPassword")."'");
		}

		view::render("sitemanager/member/changePassword");
	}
}