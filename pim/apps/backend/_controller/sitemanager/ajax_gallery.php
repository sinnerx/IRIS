<?php
class Controller_Ajax_Gallery
{
	public function index()
	{
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");

		view::render("sitemanager/image/ajax/gallery",$data);
	}

	public function photoUpload($siteAlbumID = 0)
	{
		if(form::submitted())
		{
			$file	= input::file("photoName");

			## checking-checkinggg
			$photoUpload	= !$file?"Please choose a photo":false;
			$photoUpload	= !$photoUpload?(!$file->isExt("jpg,jpeg,png")?"Please choose the right photo":false):$photoUpload;

			## no photo uploaded.
			if($photoUpload)
			{
				echo "<script type='text/javascript'>alert('$photoUpload')</script>";
				return;
			}

			$path	= model::load("image/photo")->addSitePhoto(0,$file->get("name"),input::get("photoDescription"));

			$upload	= $file->move(model::load("image/services")->getPhotoPath($path));
			$imgUrl	= model::load("image/services")->getPhotoUrl($path);

			echo "<script type='text/javascript'>parent.pimgallery.photopicker.setNewPhotoUrl('$imgUrl','$path')</script>";
		}
	}

	public function photoList($siteAlbumID = 0,$page = 1)
	{
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");

		$pageConf	= Array(
					"urlFormat"=>url::base("ajax/gallery/photoList/$siteAlbumID/{page}"),
					"limit"=>8,
					"currentPage"=>$page,
							);

		$data['res_photos']	= model::load("image/album")->getSitePhotos($siteID,$siteAlbumID,$pageConf); ## get photo with no album.

		if($siteAlbumID != 0)
		{
			## get album row.
			$data['row_album']	= model::load("image/album")->getSiteAlbum($siteAlbumID);
		}

		view::render("sitemanager/image/ajax/photoList",$data);
	}

	public function albumList()
	{
		$siteID		= model::load("access/auth")->getAuthData("site","siteID");
		$data['res_album']	= model::load("image/album")->getSiteAlbums($siteID);

		view::render("sitemanager/image/ajax/albumList",$data);
	}

	public function deletePhoto($photoID)
	{
		$siteID	= authData("site.siteID");

		## delete.
		model::load("image/photo")->deleteSitePhoto($siteID,$photoID);

		return true;
	}

	public function undeletePhoto($photoID)
	{
		$siteID	= authData("site.siteID");

		## undelete.
		model::load("image/photo")->undeleteSitePhoto($photoID);

		return true;
	}

	public function deleteAlbum($siteAlbumID)
	{
		$siteID	= authData("site.siteID");

		model::load("image/album")->deleteSiteAlbum($siteID,$siteAlbumID);

		return true;
	}

	public function showEditAlbum($siteAlbumID)
	{
		$data['row']	= model::load("image/album")->getSiteAlbum($siteAlbumID);

		view::render("sitemanager/image/ajax/showEditAlbum",$data);
	}

	public function editAlbum($siteAlbumID)
	{
		if(form::submitted())
		{
			$rules	= Array("albumDescription"=>"required:Album description is required.");

			if(input::validate($rules))
			{
				return response::json(Array(false));
			}

			$data['albumDescription']	= input::get("albumDescription");

			model::load("image/album")->updateSiteAlbum($siteAlbumID,$data);

			return response::json(Array(true));
		}
	}
}


?>