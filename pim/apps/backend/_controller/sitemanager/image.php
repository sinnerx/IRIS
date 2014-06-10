<?php

class Controller_Image
{
	public function album()
	{
		## get siteID.
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");

		$data['res_album']	= model::load("image/album")->getSiteAlbums($siteID);

		if($activityID = request::get("activity"))
		{
			$data['activityName']	= model::load("activity/activity")->getActivity($activityID,"activityName");
			$data['activityID']		= $activityID;

			#$data['activityName']	= $row['activityName'];
		}

		view::render("sitemanager/image/album",$data);
	}

	public function addAlbum()
	{
		if(form::submitted())
		{
			## get siteID.
			$siteID	= model::load("access/auth")->getAuthData("site","siteID");

			$rules	= Array(
					"albumName,albumDescription"=>"required:This field is required."
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("image/album#add","Error in your form.","error");
			}

			## if got activityID
			if(input::get("activityID"))
				$albumID	= model::load("image/album")->addActivityAlbum(input::get("activityID"),input::get());
			else ## normal add.
				$albumID	= model::load("image/album")->addSiteAlbum($siteID,0,input::get());

			## win
			redirect::to("image/albumPhotos/$albumID","Album has successfully been added.");
		}
	}

	public function albumPhotos($siteAlbumID)
	{
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");
		$data['row']		= model::load("image/album")->getSiteAlbum($siteAlbumID);
		$data['res_photo']	= model::load("image/album")->getSitePhotos($siteID,$siteAlbumID);

		if(form::submitted())
		{
			$file			= input::file("photoName");

			## checking-checkinggg
			$photoUpload	= !$file?"Please choose a photo":false;
			$photoUpload	= !$photoUpload?(!$file->isExt("jpg,jpeg,png")?"Please choose the right photo":false):$photoUpload;

			## no photo uploaded.
			if($photoUpload)
			{
				input::repopulate();
				redirect::to("",$photoUpload,"error");
			}

			## win.
			# upload.
			$imagePhoto	= model::load("image/photo");

			## add photo to db.
			$path	= $imagePhoto->addSitePhoto($siteAlbumID,$file->get("name"),input::get("photoDescription"));

			## update cover photo if they aint exists yet.
			if(!$data['row']['albumCoverImageName'])
			{
				## update cover photo.
				model::load("image/album")->updateCoverPhoto($data['row']['albumID'],$path);
			}

			## move uploaded file.
			$upload	= $file->move(model::load("image/services")->getPhotoPath($path));

			if(!$upload)
			{
				var_dump($upload);die();
			}

			redirect::to("image/albumPhotos/$siteAlbumID#");
		}

		view::render("sitemanager/image/albumPhotos",$data);
	}

	/*public function albumAddPhoto($id)
	{
		view::render("sitemanager/image/albumaddPhoto",$data);
	}*/

	public function galleryPicker()
	{
		view::render("sitemanager/image/galleryPicker");
	}

	public function galleryPicker_upload($siteAlbumID = 0)
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

			echo "<script type='text/javascript'>parent.ajxgal.setNewPhotoUrl('$imgUrl')</script>";
		}
	}

	public function ajaxGallery()
	{
		$this->template	= false;
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");
		$data['res_album']	= model::load("image/album")->getSiteAlbums($siteID);
		$data['res_photos']	= model::load("image/album")->getSitePhotos($siteID,0); ## get photo with no album.

		view::render("sitemanager/image/ajax/gallery",$data);
	}

	public function ajaxPhotos($siteAlbumID,$page = 1)
	{
		
	}
}


?>