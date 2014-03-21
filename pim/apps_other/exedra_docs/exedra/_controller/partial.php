<?php

class Controller_Partial
{
	private function components()
	{
		$component	= model::load("components");

		$data['componentR']		= $component->lists();

		view::render("partial/components",$data);
	}
}


?>