<?php
namespace model\image;
use db, session, model, pagination;
class album
{
	## get list of photo by album id
	public function getSitePhotos($siteID,$siteAlbumID,$paginationConf = null)
	{
		db::from("site_photo");
		db::where("site_photo.siteID",!$siteID?0:$siteID);
		db::where("siteAlbumID",$siteAlbumID);
		db::where("sitePhotoStatus",1); ## active only.

		## if got pagination..
		if($paginationConf)
		{
			pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
			## pass page and siteAlbumID (said) through html_number
			pagination::setFormat(Array("html_number"=>"<li><a href='{href}' data-page='{number}' data-said='$siteAlbumID'>{number}</a></li>"));
			pagination::initiate(Array(
							"totalRow"=>db::num_rows(),
							"urlFormat"=>$paginationConf['urlFormat'],
							"limit"=>$paginationConf['limit'],
							"currentPage"=>$paginationConf['currentPage']
									));

			db::limit($paginationConf['limit'],pagination::recordNo()-1);
		}

		db::order_by("sitePhotoID","desc");
		db::join("photo","photo.photoID = site_photo.photoID");
		return db::get()->result();
	}

	## get just album row.
	public function getSiteAlbum($siteAlbumID)
	{
		db::where("siteAlbumStatus",1);
		db::where("siteAlbumID",$siteAlbumID);
		db::join("album","album.albumID = site_album.albumID");
		return db::get("site_album")->row();
	}

	public function getSiteAlbumBySlug($slug,$year,$month)
	{
		db::where("siteAlbumStatus",1);
		db::where("siteAlbumSlug",$slug);
		db::where("site_album.albumID IN (SELECT albumID FROM album WHERE year(albumCreatedDate) = ? AND month(albumCreatedDate) = ? )",Array($year,$month));
		db::join("album","album.albumID = site_album.albumID");
		return db::get("site_album")->row();
	}

	## add album.
	public function addSiteAlbum($siteID,$siteAlbumType,$data)
	{
		$albumID	= $this->_addAlbum($data);

		$originalSlug	= model::load("helper")->slugify($data['albumName']);
		$siteAlbumSlug	= $this->refineSiteAlbumSlug($originalSlug,now());

		## insert site_album
		$data_sitealbum	= Array(
				"siteID"=>$siteID,
				"siteAlbumType"=>$siteAlbumType,
				"albumID"=>$albumID,
				"siteAlbumSlugOriginal"=>$originalSlug,
				"siteAlbumSlug"=>$siteAlbumSlug,
				"siteAlbumStatus"=>1
								);

		db::insert("site_album",$data_sitealbum);
		$siteAlbumID	= db::getLastID("site_album","siteAlbumID");
		return $siteAlbumID;
	}

	public function deleteSiteAlbum($siteID,$siteAlbumID)
	{
		db::where(Array(
				"siteID"=>$siteID,
				"siteAlbumID"=>$siteAlbumID,
				"siteAlbumStatus"=>1
						))->update("site_album",Array(
										"siteAlbumStatus"=>2
													));
	}

	## create slug for site album.
	public function refineSiteAlbumSlug($slug,$date,$siteAlbumID = null)
	{
		$year	= date("Y",strtotime($date));
		$month	= date("n",strtotime($date));

		db::where("siteAlbumSlugOriginal",$slug);
		db::where("albumID IN (SELECT albumID FROM album WHERE month(albumCreatedDate) = ? AND year(albumCreatedDate) = ?)",Array($month,$year));

		if($siteAlbumID)
		{
			db::where("siteAlbumID !=",$siteAlbumID);
		}

		$result	= db::get("site_album")->result();
		if(!$result)
			return $slug;

		return $slug."-".(count($result)+1);
	}

	private function _addAlbum($data)
	{
		$data 	= Array(
				"albumName"=>$data['albumName'],
				"albumDescription"=>$data['albumDescription'],
				"albumCreatedDate"=>now(),
				"albumCreatedUser"=>session::get("userID")
						);

		db::insert("album",$data);

		return db::getLastID("album","albumID");
	}

	## add activity album.
	public function addActivityAlbum($activityID,$data)
	{
		## create album.
		$siteAlbumID	= $this->addSiteAlbum($siteID,1,$data);

		## and relate.
		db::insert("activity_album",Array(
							"activityID"=>$activityID,
							"siteAlbumID"=>$siteAlbumID,
							"activityAlbumCreatedDate"=>now(),
							"activityAlbumCreatedUser"=>session::get("userID")
										));

		return $siteAlbumID;
	}

	## list all sites allbum.
	public function getSiteAlbums($siteID,$year = null,$month = null)
	{
		db::where("siteAlbumStatus",1);
		db::where("site_album.siteID",$siteID);
		db::select("site_album.*,album.*");
		## if year.
		if($year)
		{
			db::where("year(albumCreatedDate)",$year);
			db::select("month(albumCreatedDate) as month");
		}

		## if month.
		if($month)
		{
			db::where("month(albumCreatedDate)",$month);
		}

		## join-album.
		db::join("album","album.albumID = site_album.albumID");

		## get it
		# if got year group it by month.
		if($year && !$month)
		{
			return db::get("site_album")->result("month",true);
		}
		else if($month)
		{
			return db::get("site_album")->result("siteAlbumID");
		}
		else
		{
			return db::get("site_album")->result();
		}
	}

	public function getActivityAlbum($activityID)
	{
		db::select("album.*,site_album.*,user_profile.userProfileFullName");
		db::where("activityID",$activityID);
		db::join("site_album","site_album.siteAlbumID = activity_album.siteAlbumID");
		db::join("album","album.albumID = site_album.albumID");
		db::join("user_profile","user_profile.userID = albumCreatedUser");
		db::order_by("activityID","desc");

		return db::get("activity_album")->result();
	}

	public function updateCoverPhoto($albumID,$photoName)
	{
		db::where("albumID",$albumID);
		db::update("album",Array("albumCoverImageName"=>$photoName));
	}

	## album type.
	public function type()
	{
		$typeR	= Array(
				1=>"User album",
				2=>"Site album"
						);
	}

	public function getSiteAlbumTotalPhoto($siteAlbumID)
	{
		$total	= Array();

		if($siteAlbumID)
		{
			db::select("photoID,siteAlbumID");
			db::where("siteAlbumID",$siteAlbumID);

			$res	= db::get("site_photo")->result("siteAlbumID",true);

			if($res)
			{
				foreach($res as $siteAlbumID=>$sitePhotoR)
				{
					$total[$siteAlbumID]	= count($sitePhotoR);
				}

			}
		}

		return $total;
	}

	public function updateSiteAlbum($siteAlbumID,$data)
	{
		db::where("albumID IN (SELECT albumID FROM site_album WHERE siteAlbumID = ?)",Array($siteAlbumID));
		db::update("album",Array(
					"albumDescription"=>$data['albumDescription']
								));
	}

	public function getSiteLatestAlbum($siteID)
	{
		db::where("site_album.albumID IN (SELECT albumID FROM album WHERE albumCoverImageName is not ?)",Array(null));
		db::where("site_album.siteID",$siteID);
		db::where("siteAlbumStatus",1);

		db::join("album","album.albumID = site_album.albumID");
		db::order_by("siteAlbumID","desc");

		return db::get("site_album")->row();
	}

	public function changeCoverPhoto($siteID,$siteAlbumID,$photoName)
	{
		db::where("albumID IN (SELECT albumID FROM site_album WHERE siteID = ? AND siteAlbumID = ?)",Array($siteID,$siteAlbumID));
		db::update("album",Array("albumCoverImageName"=>$photoName));

		return true;
	}
}