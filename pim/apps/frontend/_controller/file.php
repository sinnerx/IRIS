<?php
class Controller_File
{
	public function index()
	{
		## build extra condition for privacy.
		$privacy	= Array(1);

		if(authData("current_site.isMember"))
			$privacy[]	= 2;

		$data['latestFiles']	= model::load("file/file")->getLatestFiles(authData("current_site.siteID"), $privacy);

		view::render("file/index",$data);
	}

	public function view($fileID)
	{
		view::render("file/view");
	}
}