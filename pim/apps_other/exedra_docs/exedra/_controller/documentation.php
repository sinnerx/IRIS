<?php

class Controller_Documentation
{
	var $template	= "documentation";
	public function subject($subj = "index",$topic = "index")
	{
		

		view::render("documentation/$subj/$topic",$data);
	}
}

?>