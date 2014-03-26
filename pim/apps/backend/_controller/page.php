<?php

Class Controller_Page
{
	public function index()
	{
		$siteID					= model::load("site")->getSiteByManager(null,"siteID");
		$data['res_page']		= model::load("page")->listBySite($siteID);
		$data['pageDefault']	= model::load("page")->getDefault();

		view::render("page/index",$data);
	}

	public function test()
	{
		$this->template	= false;
		view::render("page/test");
	}
}


?>