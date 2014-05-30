<?php
namespace model\image;
use db, session;
class album
{
	## get list of photo by album id
	public function getPhotos($albumID)
	{
		db::where("albumID",$albumID);
		return db::get("photo")->result();
	}

	## get just album row.
	public function getAlbum($albumID)
	{
		db::where("albumID",$albumID);
		return db::get("album")->row();
	}

	## add album.
	public function addSiteAlbum($siteID,$siteAlbumType,$data)
	{
		$data	= Array(
				"albumType"=>2,
				"albumName"=>$data['albumName'],
				"albumDescription"=>$data['albumDescription'],
				"albumCreatedDate"=>now(),
				"albumCreatedUser"=>session::get("userID")
						);

		db::insert("album",$data);

		$albumID	= db::getLastID("album","albumID");

		## insert site_album
		$data_sitealbum	= Array(
				"siteID"=>$siteID,
				"siteAlbumType"=>$siteAlbumType,
				"albumID"=>$albumID
								);

		db::insert("site_album",$data_sitealbum);

		return $albumID;
	}

	## add activity album.
	public function addActivityAlbum($activityID,$data)
	{
		## create album.
		$albumID	= $this->addSiteAlbum($siteID,1,$data);

		## and relate.
		db::insert("activity_album",Array(
							"activityID"=>$activityID,
							"albumID"=>$albumID,
							"activityAlbumCreatedDate"=>now(),
							"activityAlbumCreatedUser"=>session::get("userID")
										));
	}

	## list all sites allbum.
	public function getSiteAlbums($siteID,$year = null,$month = null)
	{
		db::where("albumType",2);

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
		db::select("album.*,user_profile.userProfileFullName");
		db::where("activityID",$activityID);
		db::join("album","album.albumID = activity_album.albumID");
		db::join("user_profile","user_profile.userID = albumCreatedUser");
		db::order_by("activityID","desc");

		return db::get("activity_album")->result();
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