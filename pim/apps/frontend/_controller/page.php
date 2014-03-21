<?php
Class Controller_Page
{
	var $template	= "default";
	public function index()
	{
		view::render("page/index");
	}
}

?>