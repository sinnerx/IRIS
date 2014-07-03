<?php
class Controller_Gallery
{
	public function index()
	{
		$year	= request::named("year",date("Y"));

		$siteID	= authData("current_site.siteID");
		$res_album	= model::load("image/album")->getSiteAlbums($siteID,$year);

		// loop res_album prepares list of siteAlbumID.
		$albumIDR	= Array();
		foreach($res_album as $month=>$list_album)
		{
			foreach($list_album as $row_album)
			{
				if(!in_array($row_album['siteAlbumID'],$albumIDR))
					$albumIDR[]	= $row_album['siteAlbumID'];
			}
		}

		$data['photoTotalR']	= model::load("image/album")->getSiteAlbumTotalPhoto($albumIDR);
		$data['res_album']		= $res_album;

		$data['year']	= $year;
		view::render("gallery/index",$data);
	}

	public function index_month()
	{
		$year	= request::named("year",date("Y"));
		$month	= request::named("month",date("m"));
		$month	= (int)$month;

		$siteID	= authData("current_site.siteID");

		$res_album	= model::load("image/album")->getSiteAlbums($siteID,$year,$month);

		if($res_album)
			$data['photoTotalR']	= model::load("image/album")->getSiteAlbumTotalPhoto(array_keys($res_album));

		$data['res_album']	= $res_album;
		$data['year']		= $year;
		$data['month']		= $month;
		view::render("gallery/index_month",$data);
	}

	public function albumView($idOrSlug,$year = null,$month = null)
	{
		$siteID	= authData("current_site.siteID");

		## get album data.
		if(is_numeric($idOrSlug))
		{
			$row_album	= model::load("image/album")->getSiteAlbum($idOrSlug);
		}
		else
		{
			$row_album	= model::load("image/album")->getSiteAlbumBySlug($idOrSlug,$year,(int)$month);
		}

		## get photos.
		$res_photo	= model::load("image/album")->getSitePhotos($siteID,$row_album['siteAlbumID']);


		$data['res_photo']	= $res_photo;
		$data['row_album']	= $row_album;
		view::render("gallery/albumView",$data);
	}
}