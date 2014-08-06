<?php

class Controller_Partial
{
	public function __construct()
	{
		$this->currController	= controller::getCurrentController();
		$this->currMethod		= controller::getCurrentMethod();

		## get row of current site.
		##$this->row_site	= model::load("site/site")->getSiteBySlug(request::named("site-slug"));
		$this->row_site	= model::load("access/auth")->getAuthData("current_site");

		## authenticated user variable.
		$this->user		= model::load("access/auth")->getAuthData("user");

		return;
	}

	### hooked through pre_template. to pass initial data.
	public function index()
	{
		$data['row_site']	= $this->row_site;

		## pass row_site to default template.
		return $data;
	}

	private function pim_list()
	{
		#$data['siteListR']	= model::load("site/site")->lists();
		$data['stateR']		= model::load("helper")->state();
		$data['res_site']	= model::load("site/site")->getSiteByState();

		view::render("partial/pim_list",$data);
	}

	private function calendar()
	{
		$nonCalendarlist	= Array('activity/index');
		if(in_array($this->currController."/".$this->currMethod,$nonCalendarlist))
		{
			/*if(request::named("month"))
				return false;*/
		}
		view::render("partial/calendar");
	}

	private function top()
	{
		$site			= model::load("site/site");
		$data['links']	= $site->getLinks($site->getSiteIDBySlug());

		## if logged backend user is logged in.
		if(session::has("userID"))
		{
			$data['username']	= model::load("user/user")->get(session::get("userID"),"userProfileFullName");
		}

		view::render("partial/top",$data);
	}

	private function header()
	{
		#$data['siteName']	= model::load("site/site")->getSiteName();
		$data['siteName']	= $this->row_site['siteName'];
		$data['menuR']		= model::load("site/menu")->getTopMenu($this->row_site['siteID']);
		$data['componentR']	= model::load("site/menu")->getComponent($this->row_site['siteID']);
		$data['user']		= $this->user;

		view::render("partial/header",$data);
	}

	private function top_slideshow()
	{
		$data['res_slider']		= model::load("site/slider")->getSlider($this->row_site['siteID'],false);

		view::render("partial/top_slideshow",$data);
	}

	private function bottom_down()
	{
		$siteID = authData('current_site.siteID');
		$data['articles'] = model::load("blog/article")->getArticlesByCategoryID($siteID,1);
		$data['row_site']	= $this->row_site;
		$row_latestalbum	=model::load("image/album")->getSiteLatestAlbum($siteID);

		#$data['latestPhotoUrl']	=  model::load("image/services")->getPhotoUrl($row_latestalbum['albumCoverImageName']);
		$data['latestPhotoUrl']	= model::load("api/image")->buildPhotoUrl($row_latestalbum['albumCoverImageName'],"small");
		if($row_latestalbum)
		{
			$data['latestPhotoLink']	= model::load("helper")->buildDateBasedUrl($row_latestalbum['siteAlbumSlug'],$row_latestalbum['albumCreatedDate'],url::base("{site-slug}/galeri"));

		}
		else
		{
			$data['latestPhotoLink'] = null;
		}

		view::render("partial/bottom_down",$data);
	}

	private function landing_menu()
	{
		$data['state']	= model::load("helper")->state();

		## get site by state.
		#$res_site	= model::load("site/site")->getSiteByState();

		## fetch site.
		## select everything except manager siteID.
		$managerSiteID	= model::load("config")->get("configManagerSiteID");
		$res_site	= db::select("stateID,siteName,siteSlug")->where("siteID !=",$managerSiteID)->get("site")->result();

		## prepare the records
		foreach($res_site as $row)
		{
			$stateID			= $row['stateID'];

			$stateR[$stateID]['total']	= !isset($stateR[$stateID]['total'])?1:$stateR[$stateID]['total']+1;
			$stateR[$stateID]['records'][]	= $row;
		}
		
		$data['stateR']	= $stateR;

		view::render("partial/landing_menu",$data);
	}
}


?>