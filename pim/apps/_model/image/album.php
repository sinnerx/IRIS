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
		db::where("siteAlbumID",$siteAlbumID);
		db::join("album","album.albumID = site_album.albumID");
		return db::get("site_album")->row();
	}

	## add album.
	public function addSiteAlbum($siteID,$siteAlbumType,$data)
	{
		$albumID	= $this->_addAlbum($data);

		## insert site_album
		$data_sitealbum	= Array(
				"siteID"=>$siteID,
				"siteAlbumType"=>$siteAlbumType,
				"albumID"=>$albumID
								);

		db::insert("site_album",$data_sitealbum);
		$siteAlbumID	= db::getLastID("site_album","siteAlbumID");
		return $siteAlbumID;
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
		## if year.
		if($year)
		{
			db::where("year(albumCreatedDate)",$year);
		}

		## if month.
		if($month)
		{
			db::where("month(albumCreatedMonth)",$month);
		}

		## join-album.
		db::join("album","album.albumID = site_album.albumID");

		## get it
		return db::get("site_album")->result();
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
}