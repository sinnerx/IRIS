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

		return db::where("fileFolderID",$folderID)->get("file")->result("fileID");
	}

	public function getTotalDownload($files)
	{
		db::where("fileID",$files);
		$res	= db::get("file_download")->result("fileID",true);

		foreach($files as $fID)
		{
			$total[$fID]	= isset($res[$fID])?count($res[$fID]):0;
		}

		return $total;
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

	public function getTypeBasedIcon($type)
	{
		$lists	= Array(
			Array("pdf","pdf_icons.png"),
			Array("doc,docx,txt"=>"doc_icon.png")
						);

		$default	= "doc_icon.png";

		foreach($lists as $row)
		{
			if(in_array($type,explode(",",$row[0])))
			{
				return \url::asset("frontend/images/$row[1]");
			}
		}

		## not found just return default.
		return \url::asset("frontend/images/$default");
	}

	public function updatePrivacy($siteID,$id,$privacy = null)
	{
		if(!$privacy)
		{
			$nextPrivacyMap	= Array(1=>2,2=>3,3=>1);
			$privacy	= db::select("filePrivacy")->where("siteID",$siteID)->where("fileID",$id)->get("file")->row("filePrivacy");
			$privacy	= $nextPrivacyMap[$privacy];
		}

		db::where("siteID",$siteID)->where("fileID",$id);
		db::update("file",Array("filePrivacy"=>$privacy,"fileUpdatedDate"=>now()));

		return $privacy;
	}

	public function download($fileID)
	{
		## just create a download point.
		db::insert("file_download",Array(
			"fileID"=>$fileID,
			"userID"=>session::get("userID"),
			"fileDownloadCreatedDate"=>now(),
			"fileDownloadCreatedUser"=>session::get("userID")
			));
	}
}