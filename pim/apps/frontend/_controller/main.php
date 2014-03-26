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
		$page	= model::load("page");
		$site	= model::load("site");
		$siteID	= $site->getSiteIDBySlug(request::named("site-slug"));
		$data['siteName']	= $site->getSiteName();

		## get tentang-kami.
		$defaultR	= $page->getDefault();
		db::from("page");
		db::where("siteID",$siteID);
		db::where(Array(
					"pageType"=>1,
					"pageDefaultType"=>1
						));
		$data['pageName']	= $defaultR[1]['pageDefaultName'];
		$data['pageText']	= db::get()->row("pageText");

		view::render("main/index",$data);
	}

	## site registration. example : pim.my/[site-slug]/registration
	public function registration()
	{
		$this->template	= "template_registration";
		$row_site		= model::load("site")->getSite();
		$data['siteName']	= $row_site['siteName'];
		$data['siteInfoDescription']	= $row_site['siteInfoDescription'];
		
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