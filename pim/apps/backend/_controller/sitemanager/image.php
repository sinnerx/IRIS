<?php

class Controller_Image
{
	public function album()
	{
		## get siteID.
		$siteID	= model::load("site/site")->getSiteByManager(session::get("userID"),"siteID");

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
			$siteID	= model::load("site/site")->getSiteByManager(session::get("userID"),"siteID");

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
				model::load("image/album")->addActivityAlbum(input::get("activityID"),input::get());
			else ## normal add.
				model::load("image/album")->addSiteAlbum($siteID,0,input::get());

			## win
			redirect::to("image/album","Album has successfully been added.");
		}
	}

	public function albumPhotos($id)
	{
		$data['albumID']	= $id;
		$data['row']		= model::load("image/album")->getAlbum($id);
		$data['res_photo']	= model::load("image/album")->getPhotos($id);

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
			$fpath	= $imagePhoto->addSitePhoto($id,$file->get("name"),input::get("photoDescription"));

			## move uploaded file.
			$upload	= $file->move($fpath);

			if(!$upload)
			{
				var_dump($upload);die();
			}

			redirect::to("image/albumPhotos/$id#");
		}

		view::render("sitemanager/image/albumPhotos",$data);
	}

	public function albumAddPhoto($id)
	{
		view::render("sitemanager/image/albumaddPhoto",$data);
	}
}


?>