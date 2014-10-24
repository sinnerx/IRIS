<?php
Class Controller_Video
{
	public function album($page = 1)
	{
		## get siteID.
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");

		$data['res_album']	= model::load("video/album")->getVideoAlbums($siteID,$page);

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
		$page = request::get("page");
		$siteID				= model::load("access/auth")->getAuthData("site","siteID");
		$data['row']		= model::load("video/album")->getOneVideoAlbum($videoAlbumID);
		$data['res_video']	= model::load("video/album")->getVideos($videoAlbumID,$page);

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

			redirect::to("video/albumVideos/$videoAlbumID#","Video has successfully been added. Video is awaiting approval by the Cluster Lead.");
		}

		view::render("sitemanager/video/albumVideos",$data);
	}

	public function updateVideo($videoID)
	{
		$siteID	= model::load("access/auth")->getAuthData("site","siteID");
		$data	= input::get();

		# check refID for the video
		if($data['videoRefID'])
		{
			parse_str( parse_url( $data['videoRefID'], PHP_URL_QUERY ), $my_array_of_vars );
			$data['videoRefID'] = $my_array_of_vars['v']?$my_array_of_vars['v']:$data['videoRefID'];
		}

		model::load("video/album")->updateVideo($videoID,$data,$siteID);
		$response = Array($data['videoName'],$data['videoRefID'],$data['videoType'],$videoID);

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

	public function disableVideo($videoID)
	{
		$response = model::load("video/album")->disableVideo($videoID);

		return response::json(Array($response['videoRefID']));
	}

	public function changeCoverVideo($videoAlbumID)
	{
		$video = input::get("videoName");
		model::load("video/album")->changeCoverVideo($videoAlbumID,$video);

		return $video;
	}

	public function disableAlbum($videoAlbumID)
	{
		$response = model::load("video/album")->disableAlbum($videoAlbumID);

		return $response;
	}

	public function enableAlbum($videoAlbumID)
	{
		$response = model::load("video/album")->updateAlbumStatus($videoAlbumID);

		if($response)
		{
			redirect::to("video/albumVideos/".$videoAlbumID,"The album has been enabled. ");
		}
		else
		{
			redirect::to("video/albumVideos/".$videoAlbumID,"There is an error. ");
		}
	}

	public function enableVideo($videoID)
	{
		$response = model::load("video/album")->updateVideoStatus($videoID);

		if($response)
		{
			redirect::to("video/albumVideos/".$response,"The video has been enabled. ");
		}
		else
		{
			redirect::to("video/albumVideos/".$response,"There is an error. ");
		}
	}
}