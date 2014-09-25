<?php
class Controller_Video
{
	public function video_gallery($slug)
	{
		$data['video_rows'] = model::load("video/album")->getVideosBySlug($slug);
		$data['album'] = model::load("video/album")->getOneVideoAlbumBySlug($slug);
		
		view::render("video/video_gallery",$data);
	}

	public function album()
	{
		## get siteID.
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");

		$data['albums'] = model::load("video/album")->getVideoAlbums($siteID);

		view::render("video/album",$data);
	}
}
?>