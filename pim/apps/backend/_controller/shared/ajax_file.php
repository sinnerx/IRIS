<?php
class Controller_Ajax_File
{
	var $parameterNumberSkipValidation	= true;

	public function __construct()
	{
		$this->siteID = authData('user.userLevel') != 99 ? authData('site.siteID') : 0;
	}

	public function openFolder($folderId = 0)
	{
		$files		= model::load("file/folder")->openFolder($this->siteID,$folderId);
		$files['fileFolderID']	= $folderId;

		if($files['files'])
		{
			$files['totalDownloads']	= model::load("file/file")->getTotalDownload(array_keys($files['files']));
		}

		view::render("sitemanager/file/ajax/openFolder",$files);
	}

	public function newFolder($folderID = 0)
	{
		if(form::submitted())
		{
			$rules	= Array(
				"fileFolderName,fileFolderPrivacy"=>"required:Name And privacy are required."
				);

			if($error = input::validate($rules))
			{
				return response::json(Array("error"=>"Name and privacy are required"));
			}

			model::load("file/folder")->addFolder($this->siteID,$folderID,input::get());

			return response::json(Array("succes"=>"success"));
		}
	}

	public function deleteFile($type,$id)
	{
		switch($type)
		{
			case "folder":
			model::load("file/folder")->removeFolder($this->siteID, $id);
			break;
			case "file":
			model::load("file/file")->removeFile($this->siteID, $id);
			break;
		}
	}

	public function updatePrivacy($type,$id,$privacy = null)
	{
		$siteID	= $this->siteID;

		switch($type)
		{
			case "folder":
			$privacy = model::load("file/folder")->updatePrivacy($siteID,$id,$privacy);
			break;
			case "file":
			$privacy = model::load("file/file")->updatePrivacy($siteID,$id,$privacy);
			break;
		}

		## return privacy.
		echo model::load("template/icon")->privacy($privacy);
	}

	public function download($id)
	{
		$row	= model::load("file/file")->getFile($this->siteID, $id);

		$file_name	= $row['fileName'].".".$row['fileExt'];

		$path	= path::files("site_files/".$this->siteID."/".$id);

		header("Content-Disposition: attachment; filename=\"$file_name\"");
		readfile($path);
	}

	public function selectedAction($action)
	{
		$data	= json_decode(input::get("data"));

		foreach($data as $fileAction)
		{
			list($type,$id) = explode("_",$fileAction);

			switch($action)
			{
				case "delete":
				$this->deleteFile($type,$id);
				break;
				case "set-privacy":
				$this->updatePrivacy($type,$id,input::get("privacy"));
				break;
			}
		}
	}
}