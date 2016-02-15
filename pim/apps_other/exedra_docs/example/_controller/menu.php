<?php

class Controller_Menu
{
	private function top()
	{
		$data['top_data']	= "TOP DATA";
		view::render("menu/top",$data);
	}

	private function left()
	{
		view::render("menu/left_menu");
	}

	private function footerMessage()
	{
		$data	= Array("footer"=>"Footer one data");
		view::render("menu/footer",$data);
	}

	private function sub_footer()
	{
		view::render("menu/subfooter",Array("footer"=>"Footer two data"));
	}

	private function rbac()
	{
		
	}

	private function test()
	{
		return "what?";
	}
}

?>