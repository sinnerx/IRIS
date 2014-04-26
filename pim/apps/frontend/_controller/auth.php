<?php

### hooked in routes.
class Controller_Auth
{
	public function index()
	{
		$slug	= request::named("site-slug");

		if(isset($slug))
		{
			### site-slug authorization.
			$flag	= model::load("site/services")->checkSiteSlug($slug);

			if(!$flag)
			{
				redirect::to("404","Couldn't find site : <b>".$slug."</b>","error");
			}
		}
	}
}


?>