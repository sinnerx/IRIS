<?php
namespace model\video;
use db, session, model, pagination, url;

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
				"videoAlbumStatus"=>1,
				"videoAlbumSlug"=>$albumSlug,
				"videoAlbumCreatedDate"=>now(),
				"videoAlbumCreatedUser"=>session::get("userID")
						);

		# insert video_album
		db::insert("video_album",$data);

		return db::getLastID("video_album","videoAlbumID");
	}

	# alter same slug album.
	public function slugChecker($slug,$siteID,$videoAlbumID = null)
	{
		db::from("video_album");
		db::where("videoAlbumOriginalSlug",$slug);

		if($videoAlbumID)
		{
			db::where("videoAlbumID != ?",Array($videoAlbumID));
		}

		db::where("siteID",$siteID);
		
		if($result=db::get()->result())
		{
			$slug = $slug.'-'.(count($result)+1);
		}

		return $slug;
	}

	# select videos album by site.
	public function getVideoAlbums($siteID,$page = 1,$frontend = 0)
	{
		db::from("video_album");
		db::where("siteID",$siteID);

		if($frontend == 1)
		{
			db::where("videoAlbumStatus",1);
			$url = url::base("{site-slug}/video?page={page}");
			$limit = 4;
			$css = model::load('template/frontend')->pagination();
		}
		else
		{
			$url = url::base("video/album/{page}");
			$limit = 12;
			$css = model::load('template/cssbootstrap')->paginationLink();
		}

		db::order_by("videoAlbumCreatedDate","desc");

		## paginate based on current query built.
		pagination::setFormat($css);
		pagination::initiate(Array(
				"totalRow"=>db::num_rows(), 
				"limit"=>$limit,				
				"urlFormat"=>$url,
				"currentPage"=>$page
		));

		## limit, and offset.
		db::limit(pagination::get("limit"),pagination::recordNo()-1); 

		return db::get()->result();
	}

	# select one video album.
	public function getOneVideoAlbum($videoAlbumID)
	{
		db::from("video_album");
		db::where("videoAlbumID",$videoAlbumID);

		return db::get("video_album")->row();
	}

	# select one video album by slug.
	public function getOneVideoAlbumBySlug($slug)
	{
		db::from("video_album");
		db::where("videoAlbumSlug",$slug);

		return db::get("video_album")->row();
	}

	# get list of album(s) by albumID
	public function getVideos($videoAlbumID,$page = 1)
	{
		db::from("video");
		db::where("videoAlbumID",$videoAlbumID);
		db::order_by("videoCreatedDate","desc");

		## paginate based on current query built.
		pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
		pagination::initiate(Array(
				"totalRow"=>db::num_rows(), 
				"limit"=>8,				
				"urlFormat"=>url::base("video/albumVideos/$videoAlbumID?page={page}"),
				"currentPage"=>$page
		));

		## limit, and offset.
		db::limit(pagination::get("limit"),pagination::recordNo()-1); 

		return db::get()->result("videoID");
	}

	# get list of album(s) by slug
	public function getVideosBySlug($slug,$frontend = 0)
	{
		db::from("video_album");
		db::where("videoAlbumSlug",$slug);

		$album = db::get("video_album")->row();

		db::from("video");
		db::where("videoAlbumID",$album['videoAlbumID']);
		db::where("videoApprovalStatus",1);

		if($frontend == 1)
		{
			db::where("videoStatus",1);
		}

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
				"videoName"=>ucfirst($data['videoName']),
				"videoStatus"=>1,
				"videoApprovalStatus"=>0,
				"videoCreatedDate"=>now(),
				"videoCreatedUser"=>session::get("userID")
						);

		# insert video_album
		db::insert("video",$set);

		$videoID = db::getLastID("video","videoID");

		model::load("site/request")->create('video.add', $siteID, $videoID, Array());
	}

	public function updateVideo($videoID,$data,$siteID)
	{
		$data = Array(
						"videoName"=>$data['videoName'],
						"videoType"=>$data['videoType'],
						"videoRefID"=>$data['videoRefID'],
						"videoApprovalStatus"=>1
								);

		if(model::load("site/request")->checkRequest("video.add",$siteID,$videoID))
		{
			$this->_updateVideo($videoID,$data);
		}
		else
		{
			model::load("site/request")->create('video.update', $siteID, $videoID, $data);
		}
	}

	private function _updateVideo($videoID,$data)
	{
		db::where("videoID",$videoID);
		db::update("video",$data);
	}

	public function updateAlbum($videoAlbumID,$siteID,$data)
	{
		$originalSlug	= model::load("helper")->slugify($data['videoAlbumName']);
		$albumSlug	= $this->slugChecker($originalSlug,$siteID,$videoAlbumID);
		db::where("videoAlbumID",$videoAlbumID);
		db::update("video_album",Array(
						"videoAlbumName"=>$data['videoAlbumName'],
						"videoAlbumDescription"=>$data['videoAlbumDescription'],
						"videoAlbumOriginalSlug"=>$originalSlug,
						"videoAlbumSlug"=>$albumSlug
								));
	}

	public function getVideoAlbumCover($videoAlbumID,$frontend=0)
	{
		db::from("video");
		db::where("videoAlbumID",$videoAlbumID);
		if($frontend == 1){db::where("videoApprovalStatus",1);}
		db::order_by("videoCreatedDate","desc");

		return db::get("video")->row();
	}

	public function disableVideo($videoID)
	{
		db::where("videoID",$videoID);
		db::update("video",Array(
						"videoStatus"=>0
								));

		return true;
	}

	public function deleteVideo($videoID)
	{
		db::delete("video",Array("videoID"=>$videoID));

		return true;
	}

	public function disableAlbum($videoAlbumID)
	{
		db::where("videoAlbumID",$videoAlbumID);
		db::update("video_album",Array(
						"videoAlbumStatus"=>0
								));

		return true;
	}

	public function deleteAlbum($videoAlbumID)
	{
		db::delete("video",Array("videoAlbumID"=>$videoAlbumID));
		db::delete("video_album",Array("videoAlbumID"=>$videoAlbumID));

		return true;
	}

	public function buildVideoUrl($type = 1,$ref)
	{
		if($type == 1)
		{
			$url = "http://img.youtube.com/vi/".$ref."/0.jpg";
		}
		else
		{
			$url = url::asset("frontend/images/noimage.png");
		}
		return $url;
	}

	public function buildEmbedVideoUrl($type = 0,$ref = "")
	{
		if($type == 1)
		{
			$url = "http://www.youtube.com/embed/".$ref."?autoplay=1";
		}
		else if($type == 0)
		{
			$url = "http://localhost/digitalgaia/iris/pim/assets/frontend/images/noimage.png";
		}
		return $url;
	}

	public function updateAlbumStatus($videoAlbumID)
	{
		db::where("videoAlbumID",$videoAlbumID);
		db::update("video_album",Array(
						"videoAlbumStatus"=>1
								));

		return true;
	}

	public function updateVideoStatus($videoID)
	{
		db::where("videoID",$videoID);
		db::update("video",Array(
						"videoStatus"=>1
								));

		db::from("video");
		db::where("videoID",$videoID);
		$video = db::get("video")->row();
		
		return $video['videoAlbumID'];
	}

	public function changeCoverVideo($videoAlbumID,$videoName)
	{
		db::where("videoAlbumID",$videoAlbumID);
		db::update("video_album",Array("videoAlbumThumbnail"=>$videoName));

		return true;
	}

	public function createVideoAlbumLink($videoAlbumSlug,$siteSlug = null)
	{
		if(is_numeric($videoAlbumSlug))
		{
			$row	= db::where("videoAlbumID",$videoAlbumSlug)->get("video_album")->row();
			$videoAlbumSlug	= $row['videoAlbumSlug'];
			$siteSlug		= db::select("siteSlug")->where("siteID",$row['siteID'])->get("site")->row("siteSlug");
		}

		return url::createByRoute("video-album-view",Array(
			"site-slug"=>$siteSlug,
			"video-slug"=>$videoAlbumSlug
			),true);
	}
}


?>