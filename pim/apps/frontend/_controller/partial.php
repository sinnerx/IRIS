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

		view::render("partial/pim_list",$data);
	}

	private function calendar()
	{
		view::render("partial/calendar");
	}

	private function top()
	{
		view::render("partial/top");
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
		view::render("partial/top_slideshow");
	}

	private function bottom_down()
	{
		view::render("partial/bottom_down");
	}
}


?>