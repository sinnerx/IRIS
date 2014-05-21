<?php

class Controller_Partial
{
	public function __construct()
	{
		$currController	= controller::getCurrentController();
		$currMethod		= controller::getCurrentMethod();

		## get row of current site.
		$this->row_site	= model::load("site/site")->getSiteBySlug(request::named("site-slug"));
		return;
	}

	### hooked through pre_template. to pass initial data.
	public function index()
	{
		$data['row_site']	= $this->row_site;

		## pass row_site to default template.
		return $data;
	}

	private function pim_list()
	{
		#$data['siteListR']	= model::load("site/site")->lists();
		$data['stateR']		= model::load("helper")->state();
		$data['res_site']	= model::load("site/site")->getSiteByState();

		view::render("partial/pim_list",$data);
	}

	private function calendar()
	{
		view::render("partial/calendar");
	}

	private function top()
	{
		$site			= model::load("site/site");
		$data['links']	= $site->getLinks($site->getSiteIDBySlug());

		## if logged backend user is logged in.
		if(session::has("userID"))
		{
			$data['username']	= model::load("user/user")->get(session::get("userID"),"userProfileFullName");
		}

		view::render("partial/top",$data);
	}

	private function header()
	{
		$data['siteName']	= model::load("site/site")->getSiteName();
		$data['menuR']		= model::load("site/menu")->getTopMenu();
		$data['componentR']	= model::load("site/menu")->getComponent();

		view::render("partial/header",$data);
	}

	private function top_slideshow()
	{
		$data['res_slider']		= model::load("site/slider")->getSlider(model::load("site/site")->getSiteIDBySlug(),false);

		view::render("partial/top_slideshow",$data);
	}

	private function bottom_down()
	{
		$data['row_site']	= $this->row_site;

		view::render("partial/bottom_down",$data);
	}

	private function landing_menu()
	{
		$data['state']	= model::load("helper")->state();

		## get site by state.
		$res_site	= model::load("site/site")->getSiteByState();

		## fetch site.
		$res_site	= db::select("stateID,siteName,siteSlug")->get("site")->result();

		## prepare the records
		foreach($res_site as $row)
		{
			$stateID			= $row['stateID'];

			$stateR[$stateID]['total']	= !isset($stateR[$stateID]['total'])?1:$stateR[$stateID]['total']+1;
			$stateR[$stateID]['records'][]	= $row;
		}
		
		$data['stateR']	= $stateR;

		view::render("partial/landing_menu",$data);
	}
}


?>