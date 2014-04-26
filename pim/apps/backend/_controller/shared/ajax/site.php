<?php
class Controller_Site
{
	var $template = false;
	### get site detail. used by site/index
	public function getDetail($siteID)
	{
		$site	= model::load("site/site");
		$data['row']	= $site->getSite($siteID);

		## get manager.
		$dataManager	= model::load("site/manager")->getManagersBySite($siteID);
		$data['manager1']	= $dataManager[0]['userProfileFullName']?:"null";
		$data['manager2']	= $dataManager[1]['userProfileFullName']?:"<span style='opacity:0.5;'>none</span>";

		view::render("shared/site/ajax/detail",$data);
	}
}



?>