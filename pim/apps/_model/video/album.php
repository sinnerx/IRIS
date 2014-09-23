<?php
namespace model\video;
use db, session, model, pagination;

class album
{
	## add video album.
	public function addVideoAlbum($siteID,$data)
	{
		$originalSlug	= model::load("helper")->slugify($data['videoAlbumName']);
		$albumSlug	= $this->slugChecker($originalSlug,$siteID);

		$data 	= Array(
				"siteID"=>$siteID,
				"videoAlbumName"=>$data['videoAlbumName'],
				"videoAlbumDescription"=>$data['videoAlbumDescription'],
				"videoAlbumOriginalSlug"=>$originalSlug,
				"videoAlbumSlug"=>$albumSlug,
				"videoAlbumCreatedDate"=>now(),
				"videoAlbumCreatedUser"=>session::get("userID")
						);

		# insert video_album
		db::insert("video_album",$data);

		return db::getLastID("video_album","videoAlbumID");
	}

	# alter same slug album.
	public function slugChecker($slug,$siteID)
	{
		db::from("video_album");
		db::where("videoAlbumOriginalSlug",$slug);
		db::where("siteID",$siteID);
		
		if($result=db::get()->result())
		{
			$slug = $slug.'-'.(count($result)+1);
		}

		return $slug;
	}

	# select videos album by site.
	public function getVideoAlbums($siteID)
	{
		db::from("video_album");
		db::where("siteID",$siteID);

		return db::get()->result();
	}

	# select one video album.
	public function getOneVideoAlbum($videoAlbumID)
	{
		db::from("video_album");
		db::where("videoAlbumID",$videoAlbumID);

		return db::get("video_album")->row();
	}

	# get list of album(s)
	public function getVideos($videoAlbumID,$paginationConf = null)
	{
		db::from("video");
		db::where("videoAlbumID",$videoAlbumID);
		db::order_by("videoCreatedDate","desc");

		return db::get()->result();
	}

	# add a video by album
	public function addVideoByAlbum($videoAlbumID,$siteID,$data)
	{
		$set 	= Array(
				"videoAlbumID"=>$videoAlbumID,
				"videoType"=>$data['videoType'],
				"videoRefID"=>$data['videoRefID'],
				"videoName"=>$data['videoName'],
				"videoApprovalStatus"=>0,
				"videoCreatedDate"=>now(),
				"videoCreatedUser"=>session::get("userID")
						);

		# insert video_album
		db::insert("video",$set);

		$videoID = db::getLastID("video","videoID");

		model::load("site/request")->create('video.add', $siteID, $videoID, Array());
	}

	public function updateVideo($videoID,$data)
	{
		db::where("videoID",$videoID);
		db::update("video",Array(
						"videoName"=>$data['videoName'],
						"videoType"=>$data['videoType'],
						"videoRefID"=>$data['videoRefID']
								));
	}

	public function updateAlbum($videoAlbumID,$siteID,$data)
	{
		$albumSlug	= $this->slugChecker($data['videoAlbumName'],$siteID);
		db::where("videoAlbumID",$videoAlbumID);
		db::update("video_album",Array(
						"videoAlbumName"=>$data['videoAlbumName'],
						"videoAlbumDescription"=>$data['videoAlbumDescription'],
						"videoAlbumOriginalSlug"=>$data['videoAlbumName'],
						"videoAlbumSlug"=>$albumSlug
								));
	}

	public function getVideoAlbumCover($videoAlbumID)
	{
		db::from("video");
		db::where("videoAlbumID",$videoAlbumID);
		db::order_by("videoCreatedDate","desc");

		return db::get("video")->row();
	}

	public function deleteVideo($videoID)
	{
		db::from("video");
		db::where("videoID",$videoID);
		db::order_by("videoCreatedDate","desc");

		$video = db::get("video")->row();

		if(db::delete("video",Array("videoID"=>$videoID)))
		{
			return $this->getVideoAlbumCover($video['videoAlbumID']);
		}
		else
		{
			return false;
		}
	}

	public function deleteAlbum($videoAlbumID)
	{
		if(db::delete("video",Array("videoAlbumID"=>$videoAlbumID)))
		{
			if(db::delete("video_album",Array("videoAlbumID"=>$videoAlbumID)))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}


?>