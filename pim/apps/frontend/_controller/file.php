<?php
class Controller_File
{
	public function index()
	{
		$data['latestFiles']	= model::load("file/file")->getLatestFiles(authData("current_site.siteID"));

		view::render("file/index",$data);
	}

	public function view($fileID)
	{
		view::render("file/view");
	}
}