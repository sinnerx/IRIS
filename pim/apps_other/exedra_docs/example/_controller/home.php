<?php
class Controller_Home
{
	var $template	= "default";
	var $model		= Array("example"=>Array("user","post"),
							"ception"=>Array("user","facebook"));

	public function __construct()
	{
	}

	public function example($param1 = Null,$param2 = Null)
	{
		view::render("home/dashboard");
	}

	public function test($lol = 1)
	{
	}

	public function formtest($var)
	{
	}

	private function ception()
	{
		echo "Wait.. am i within another controller...? <b style='color:red;'>A controllerception...</b>";
	}
}


?>