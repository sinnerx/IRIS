<?php
## only used in a hook.
Class Controller_Template
{
	## hooked at pre_template.
	public function index()
	{
		$user			= model::load("user/user");
		$row_user		= $user->get(session::get("userID"));

		$data['userFullName']	= ucfirst($row_user['userProfileFullName']);
		$data['userLevel']		= ucfirst($user->levelLabel($row_user['userLevel']));

		$data['siteHref']		= "";
		$data['dashboardTitle']	= "P1M Dashboard";
		if(session::get("userLevel") == 2)
		{
			$row_site	= model::load("site/site")->getSiteByManager(session::get('userID'));
			$siteSlug	= $row_site['siteSlug'];

			$data['siteHref']		= url::base("../$siteSlug");
			$data['dashboardTitle']	= $row_site['siteName'];
		}

		## get menu
		$data['menu']	= model::load("access/menu")->get(session::get("userLevel"));

		## return data to template.
		return $data;
	}

	## hooked at pre_controller.
	public function formatting()
	{
		$controller	= controller::getCurrentController();
		$method		= controller::getCurrentMethod();

		## format the pagination with bootstrap pagination.
		if(in_array($controller."/".$method,Array("root/user/lists","manager/lists","root/site/index")))
		{
			pagination::setFormat(Array(
						"html_wrapper"=>"<ul class='pagination pagination-sm m-t-none m-b-none pull-right'>{content}</ul>",
						"html_number"=>"<li><a href='{href}'>{number}</a></li>",
						"html_number_active"=>"<li><a href='{href}' style='color:red;'>{number}</a></li>",
						"html_previous"=>"<li><a href='{href}'><i class='fa fa-chevron-left'></i></a></li>",
						"html_next"=>"<li><a href='{href}'><i class='fa fa-chevron-right'></i></a></li>",
						"limit"=>10,
						"number_limit"=>3
									));
		}
	}
}


?>