<?php

class Controller_Main
{
	public function index($abc = "abc")
	{
		view::render("main/index");
	}

	public function why()
	{
		view::render("main/why");
	}
}

?>