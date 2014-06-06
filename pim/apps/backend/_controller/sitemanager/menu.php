<?php
class Controller_Menu
{
	public function index()
	{
		$data['topMenu']	= model::load("site/menu")->getTopMenu(model::load("access/auth")->getAuthData("site","siteID"));
		$data['component']	= model::load("site/menu")->getComponent();
		#cho
		#print_r($topMenu);

		view::render("sitemanager/menu/index",$data);
	}
}