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

			## authenticate user first.
			if(session::has("userID"))
			{
				## authenticate user.
				model::load("access/auth")->authUser(session::get("userID"));
			}

			## if not exists, 404. else, save it in authData.
			if(!model::load("access/auth")->authSite($slug))
			{
				redirect::to("404?site_not_found=".$slug);
			}

			## check logged user.
			if(!model::load("site/access")->checkLoggedAccess(controller::getCurrentController(),controller::getCurrentMethod()))
			{
				redirect::to("{site-slug}/404?tiada_akses=1");
			}
		}
	}
}


?>