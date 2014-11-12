<?php
namespace model\file;
use db, session, path;
/*
file:
	fileID [int]
	siteID [int]
	filePath [varchar]
	fileFolderID [int]
	filePrivacy [int]		## 1 = private, 2 = public
	fileName [varchar]
	fileSlug [varchar]
	fileOriginalSlug [varchar]
	fileType [varchar]
	fileSize [int]
	fileDescription [text]
	fileCreatedDate [datetime]
	fileCreatedUser [int]
	fileUpdatedDate [datetime]
	fileUpdatedUser [int]
*/
class File
{
	public function getFiles($siteID,$folderID,$privacy = Array(1,2,3))
	{
		db::where("siteID",$siteID);
		db::where("fileStatus",1);
		db::where("filePrivacy",$privacy);

		return db::where("fileFolderID",$folderID)->get("file")->result();
	}

	public function getFile($siteID,$fileID)
	{
		db::where("siteID",$siteID)->where("fileID",$fileID);
		db::join("user_profile","user_profile.userID = file.fileCreatedUser");
		return db::get("file")->row();
	}

	public function getLatestFiles($siteID,$privacy = Array(1,2,3),$limit = 3)
	{
		db::where("siteID",$siteID);
		db::where("fileStatus",1);
		db::where("filePrivacy",$privacy);
		db::order_by("fileCreatedDate","desc");
		db::limit($limit);

		return db::get("file")->result();
	}

	public function addFile($siteID,$folderID,$data)
	{
		$data	= Array(
			"siteID"=>$siteID,
			"fileFolderID"=>$folderID,
			"filePrivacy"=>$data['filePrivacy'],
			"fileName"=>$data['fileName'],
			"fileType"=>$data['fileType'],
			"fileSize"=>$data['fileSize'],
			"fileExt"=>$data['fileExt'],
			"fileDescription"=>$data['fileDescription'],
			"fileCreatedDate"=>now(),
			"fileStatus"=>1,
			"fileCreatedUser"=>session::get("userID")
						);

		db::insert("file",$data);

		return db::getLastID("file","fileID");
	}

	public function removeFile($siteID,$id)
	{
		## unlink from db.
		$row	= db::where("siteID",$siteID,"fileID",$id)->get("file")->row();

		if(!$row)
			return false;

		$path 	= path::files("site_files/$siteID/$id");

		## unlink.
		unlink($path);

		##
		db::delete("file",Array("siteID"=>$siteID,"fileID"=>$id));
	}

	public function updateHasFiles($folderID)
	{
		while($folderID != 0)
		{
			## find if current folder has files.
			// db::where("fileFolderhasFiles",1);
		}
	}
}