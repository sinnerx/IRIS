<?php
class Controller_Video
{
	public function video_gallery($slug)
	{
		$data['video_rows'] = model::load("video/album")->getVideosBySlug($slug);
		$data['album'] = model::load("video/album")->getOneVideoAlbumBySlug($slug,1);
		
		view::render("video/video_gallery",$data);
	}

	public function album()
	{
		## get siteID.
		$siteID	= model::load("site/site")->getSiteIDBySlug(request::named("site-slug"));

		$data['albums'] = model::load("video/album")->getVideoAlbums($siteID,1);

		view::render("video/album",$data);
	}
}
?>