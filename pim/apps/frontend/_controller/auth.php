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

			## if not exists, 404. else, save it in authData.
			if(!model::load("access/auth")->authSite($slug))
			{
				redirect::to("404","Couldn't find site : <b>".$slug."</b>","error");
			}
		}
	}
}


?>