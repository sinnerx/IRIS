<?php
Class Controller_Main
{
	## main landing page. example : pim.my
	public function landing()
	{
		view::render("main/landing");
	}

	## site landing page. example : pim.my/[site-slug]
	public function index()
	{
		$site	= model::load("site");
		$siteID	= $site->getSiteIDBySlug(request::named("site-slug"));
		$data['siteName']	= $site->getSiteName();

		view::render("main/index",$data);
	}

	## site registration. example : pim.my/[site-slug]/registration
	public function registration()
	{
		$this->template	= "template_registration";

		$data['siteName']	= model::load("site")->getSiteName();
		
		view::render("main/registration",$data);
	}

	## site contact-us : pim.my/[site-slug]/contact-us
	public function contact()
	{
		$site					= model::load("site");
		$data['row']			= $site->getSite();
		$row_manager			= $site->getManagerInfo(null,'userEmail');
		$data['managerEmail']	= $row_manager['userEmail'];


		view::render("main/contact_us",$data);
	}
}


?>