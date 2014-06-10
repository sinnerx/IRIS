<?php
class Controller_Menu
{
	public function index()
	{
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");

		if(form::submitted())
		{
			$menuR	= json_decode(input::get("menuUpdateList"),true);
			model::load("site/menu")->updateTopMenu($siteID,$menuR);

			redirect::to("","Site Menu has been updated");
		}

		$data['topMenu']	= model::load("site/menu")->getTopMenu(model::load("access/auth")->getAuthData("site","siteID"));
		$data['component']	= model::load("site/menu")->getComponent();
		#cho
		#print_r($topMenu);
		#$data['topMenu']	= model::load("site/menu")->getTopMenu2(model::load("access/auth")->getAuthData("site","siteID"));

		view::render("sitemanager/menu/index",$data);
	}
}