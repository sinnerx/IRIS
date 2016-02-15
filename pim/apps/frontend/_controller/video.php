<?php
class Controller_Video
{
	public function video_gallery($slug)
	{
		$data['video_rows'] = model::load("video/album")->getVideosBySlug($slug,1);
		$data['album'] = model::load("video/album")->getOneVideoAlbumBySlug($slug,1);
		
		view::render("video/video_gallery",$data);
	}

	public function album()
	{
		$page = request::get("page")?request::get("page"):1;

		## get siteID.
		$siteID	= model::load("site/site")->getSiteIDBySlug(request::named("site-slug"));

		$data['albums'] = model::load("video/album")->getVideoAlbums($siteID,$page,1);

		view::render("video/album",$data);
	}
}
?>