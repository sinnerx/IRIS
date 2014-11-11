<?php
class Controller_Ajax_File
{
	var $parameterNumberSkipValidation	= true;

	public function openFolder($folderId = 0)
	{
		$files		= model::load("file/folder")->openFolder(authData("site.siteID"),$folderId);
		$files['fileFolderID']	= $folderId;
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

			model::load("file/folder")->addFolder(authData("site.siteID"),$folderID,input::get());

			return response::json(Array("succes"=>"success"));
		}
	}

	public function deleteFile($type,$id)
	{
		switch($type)
		{
			case "folder":
			model::load("file/folder")->removeFolder(authData("site.siteID"),$id);
			break;
			case "file":
			model::load("file/file")->removeFile(authData("site.siteID"),$id);
			break;
		}
	}

	public function download($id)
	{
		$row	= model::load("file/file")->getFile(authData("site.siteID"),$id);

		$file_name	= $row['fileName'];

		$path	= path::files("site_files/".authData("site.siteID")."/".$id);

		header("Content-Disposition: attachment; filename=\"$file_name\"");
		echo readfile($path);
	}
}