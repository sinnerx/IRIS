<?php
Class Controller_Video
{
	public function album()
	{
		## get siteID.
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");

		$data['res_album']	= model::load("video/album")->getVideoAlbums($siteID);

		view::render("sitemanager/video/overview",$data);
	}

	public function addVideoAlbum()
	{
		if(form::submitted())
		{
			## get siteID.
			$siteID	= model::load("access/auth")->getAuthData("site","siteID");

			$rules	= Array(
					"videoAlbumName"=>"required:This field is required."
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("video/album#add","Error in your form.","error");
			}

			## add video album
			$videoAlbumID	= model::load("video/album")->addVideoAlbum($siteID,input::get());

			redirect::to("video/albumVideos/$videoAlbumID","Video album has successfully been added.");
		}
	}

	public function albumVideos($videoAlbumID)
	{
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");
		$data['row']		= model::load("video/album")->getOneVideoAlbum($videoAlbumID);
		$data['res_video']	= model::load("video/album")->getVideos($videoAlbumID);

		if(form::submitted())
		{
			$input = input::get();

			# check rules where any input is null
			$rules	= Array(
					"videoRefID,videoName"=>"required:This field is required."
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("video/albumVideos/$videoAlbumID#addVideo","Error in your form.","error");
			}

			# check refID for the video
			if($input['videoRefID'])
			{
				parse_str( parse_url( $input['videoRefID'], PHP_URL_QUERY ), $my_array_of_vars );
				$input['videoRefID'] = $my_array_of_vars['v']?$my_array_of_vars['v']:$input['videoRefID'];
			}

			# add video by album
			model::load("video/album")->addVideoByAlbum($videoAlbumID,$siteID,$input);

			redirect::to("video/albumVideos/$videoAlbumID#");
		}

		view::render("sitemanager/video/albumVideos",$data);
	}

	public function updateVideo($videoID)
	{
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");
		$data	= input::get();
		model::load("video/album")->updateVideo($videoID,$data,$siteID);
		$response = Array(nl2br($data['videoName']),$data['videoRefID'],$data['videoType'],$videoID);

		return response::json($response);
	}

	public function updateAlbum($videoAlbumID)
	{
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");
		$data	= input::get();
		model::load("video/album")->updateAlbum($videoAlbumID,$siteID,$data);
		$response = Array(nl2br($data['videoAlbumDescription']),$data['videoAlbumName'],$videoAlbumID);

		return response::json($response);
	}

	public function deleteVideo($videoID)
	{
		$response = model::load("video/album")->deleteVideo($videoID);

		return response::json(Array($response['videoRefID']));
	}

	public function deleteAlbum($videoAlbumID)
	{
		$response = model::load("video/album")->deleteAlbum($videoAlbumID);

		return $response;
	}
}