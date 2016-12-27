<?php
## only used in a hook.
Class Controller_Template
{
	## hooked at pre_template.
	public function index()
	{
		## get authenticated data.
		$data	= model::load("access/auth")->getAuthData();

		$user			= model::load("user/user");
		$row_user		= $data['user'];

		$data['userFullName']	= ucfirst($row_user['userProfileFullName']);
		$data['userLevel']		= ucfirst($user->levelLabel($row_user['userLevel']));

		$data['siteHref']		= "";
<<<<<<< HEAD
		$data['dashboardTitle']	= "Dashboard";
=======
		$data['dashboardTitle']	= " Calent Dashboard";
>>>>>>> d0dc45820c6e15278b0e0a6e146f869a71265117
		if(session::get("userLevel") == 2)
		{
			#$row_site	= model::load("site/site")->getSiteByManager(session::get('userID'));
			$row_site	= $data['site'];
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
			pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
		}
	}
}


?>