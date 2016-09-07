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

		$this->addDefaultAlbum();

		view::render("sitemanager/image/album",$data);
	}

	public function addDefaultAlbum()
	{
		$albumRow = model::load("image/album")->_checkDefaultAlbum();

		if (!$albumRow) {
			## get siteID.
			$siteID	= model::load("access/auth")->getAuthData("site","siteID");
			## fixed data
			$defaultAlbumData = array(
				'albumCreatedDate' => date("Y").'-01-01 00:00:00',
				'albumName' => 'Interior Pi1M',
				'albumName2' => 'Exterior Pi1M',
				'albumDescription' => 'Interior Pi1M Album',
				'albumDescription2' => 'Exterior Pi1M Album'
			);
			## normal add.
			$albumID = model::load("image/album")->addDefaultSiteAlbum($siteID,0,$defaultAlbumData);

			if ($albumID) {
				## win
				redirect::to("image/album","Default album has successfully been added.");
			} else {
				## lose
				redirect::to("image/album","Failed to add default Album.");
			}
		}
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
				$albumID	= model::load("image/album")->addActivityAlbum($siteID, input::get("activityID"),input::get());
			else ## normal add.
				$albumID	= model::load("image/album")->addSiteAlbum($siteID,0,input::get());

			## win
			redirect::to("image/albumPhotos/$albumID","Album has successfully been added.");
		}
	}

	public function multipleUploadAlbumPhotos($siteAlbumID)
	{

		$data['row']		= model::load("image/album")->getSiteAlbum($siteAlbumID);
		$file			= input::file("photoName");
		//var_dump($_REQUEST);
		//var_dump($_FILES);
		//var_dump($file);
		//var_dump($file->get('name'));
		//die;
		$photoUpload = false;
		//$file = $file->get()[0];
		//var_dump($file);

		## win.
		# upload.
		$imagePhoto	= model::load("image/photo");
		//$description = "";

		$description = input::get("photoDescription")[0];

		// var_dump(input::get());
		// var_dump($description);
		// die;

		$path	= $imagePhoto->addSitePhoto($siteAlbumID,$file->get("name"),$description);

		// ## update cover photo if they aint exists yet.
		if(!$data['row']['albumCoverImageName'])
		{
			## update cover photo.
			model::load("image/album")->updateCoverPhoto($data['row']['albumID'],$path);
		}

		## move uploaded file.
		$upload	= $file->move(model::load("image/services")->getPhotoPath($path));
		// {"files": [
		//   {
		//     "name": "picture1.jpg",
		//     "size": 902604,
		//     "url": "http:\/\/example.org\/files\/picture1.jpg",
		//     "thumbnailUrl": "http:\/\/example.org\/files\/thumbnail\/picture1.jpg",
		//     "deleteUrl": "http:\/\/example.org\/files\/picture1.jpg",
		//     "deleteType": "DELETE"
		//   },
		//   {
		//     "name": "picture2.jpg",
		//     "size": 841946,
		//     "url": "http:\/\/example.org\/files\/picture2.jpg",
		//     "thumbnailUrl": "http:\/\/example.org\/files\/thumbnail\/picture2.jpg",
		//     "deleteUrl": "http:\/\/example.org\/files\/picture2.jpg",
		//     "deleteType": "DELETE"
		//   }
		// ]}		
		$baseUrl			= url::getProtocol().apps::config("base_url:frontend");
		$imagePhotoSmall 	= $baseUrl."/api/photo/small/". $path;
		$imagePhotoBig	 	= $baseUrl."/api/photo/big/". $path;

		$arrayJSON = array(
			"files" => 
				array(
					array(
				       'name' => $file->get("name"),
				       'size' => $file->get("size"),
				       'url' => $imagePhotoBig,
				       'thumbnailUrl' => $imagePhotoSmall,
				       'deleteUrl' => '',
				       'deleteType' => 'DELETE',
				    	)
					),
			);

		return json_encode($arrayJSON);

	}

	public function albumPhotos($siteAlbumID)
	{
		//print_r(input::get());
		//die;
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");
		$data['row']		= model::load("image/album")->getSiteAlbum($siteAlbumID);
		$data['res_photo']	= model::load("image/album")->getSitePhotos($siteID,$siteAlbumID);
		$data['maxSize']	= 2000000;

		$data['siteSlug']	= authData("site.siteSlug");
		//die;
		
		// var_dump($data);
		// die;
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