<?php

class Controller_Error
{
	public function index($siteID = null, $errorMsg = null)
	{
		view::render("error/index",$data);
	}
}