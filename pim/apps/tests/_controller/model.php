<?php
class Controller_Model
{
	var $template	= false;
	public function tests($model_param = null)
	{
		return view::render("model/tests");
	}
}


?>