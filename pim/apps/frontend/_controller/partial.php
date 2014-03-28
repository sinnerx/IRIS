<?php

class Controller_Partial
{
	public function __construct()
	{
		$currController	= controller::getCurrentController();
		$currMethod		= controller::getCurrentMethod();

		return;
	}

	private function pim_list()
	{
		$data['siteListR']	= model::load("site")->lists();
		$data['stateR']		= model::load("helper")->state();

		view::render("partial/pim_list",$data);
	}

	private function calendar()
	{
		view::render("partial/calendar");
	}

	private function top()
	{
		$site			= model::load("site");
		$data['links']	= $site->getLinks($site->getSiteIDBySlug());

		view::render("partial/top",$data);
	}

	private function header()
	{
		$data['siteName']	= model::load("site")->getSiteName();
		$data['menuR']		= model::load("menu")->getTopMenu();
		$data['componentR']	= model::load("menu")->getComponent();

		view::render("partial/header",$data);
	}

	private function top_slideshow()
	{
		$site				= model::load("site");
		$data['res_slider']	= $site->getSlider($site->getSiteIDBySlug(),false);

		view::render("partial/top_slideshow",$data);
	}

	private function bottom_down()
	{
		view::render("partial/bottom_down");
	}

	private function landing_menu()
	{
		$data['state']	= model::load("helper")->state();

		## fetch site.
		$res_site	= db::select("stateID,siteName,siteSlug")->get("site")->result();

		## prepare the records
		foreach($res_site as $row)
		{
			$stateID			= $row['stateID'];

			$stateR[$stateID]['total']	= !isset($stateR[$stateID][$total])?1:$stateR[$stateID][$total]+1;
			$stateR[$stateID]['records'][]	= $row;
		}

		$data['stateR']	= $stateR;

		view::render("partial/landing_menu",$data);
	}
}


?>