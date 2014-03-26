<?php

class Controller_Site
{
	### get site detail. used by site/index
	public function getDetail($siteID)
	{
		$site	= model::load("site");
		$data['row']	= $site->getSite($siteID);
		view::render("site/ajax/detail",$data);
	}
}

?>