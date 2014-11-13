<?php

class Controller_Ajax_File
{
	var $template = false;
	public function __construct()
	{
		$this->siteID	= authData("current_site.siteID");
	}

	public function openFolder($folderID = 0)
	{
		## build extra condition for privacy.
		$privacy	= Array(1);

		if(authData("current_site.isMember"))
			$privacy[]	= 2;

		$files	= model::load("file/folder")->openFolder($this->siteID,$folderID,$privacy);

		if($files['folders'])
		{
			$files['totalFiles']	= model::load("file/folder")->getTotalFiles(authData("current_site.siteID"),array_keys($files['folders']),$privacy);
		}

		// echo "<pre>";
		// print_r($files);
		$files['fileFolderID']	= $folderID;
		view::render("file/ajax/openFolder",$files);
	}

	public function openFile($file)
	{
		$row	= model::load("file/file")->getFile($this->siteID,$file);

		// $typeBasedImage		= url::asset("frontend/images/pdf_icons.png");
		$typeBasedImage		= model::load("file/file")->getTypeBasedIcon($row['fileExt']);
		$row['image_url']	= in_array($row['fileExt'],Array("jpg","jpeg","png","bmp"))?url::base("{site-slug}/ajax/file/image/$file"):$typeBasedImage;

		$row['header']	= model::load("file/folder")->findParents($row['fileFolderID']);
		view::render("file/ajax/openFile",$row);
	}

	public function image($fileID)
	{
		$row	= model::load("file/file")->getFile(authData("current_site.siteID"),$fileID);

		$path	= path::files("site_files/".authData("current_site.siteID")."/".$fileID);

		header("Content-Type: $row[fileType]");
		// header("Content-Length: " . filesize($name));
		echo readfile($path);
	}

	public function downloadFile($id)
	{
		$row	= model::load("file/file")->getFile(authData("current_site.siteID"),$id);

		$file_name	= $row['fileName'].".".$row['fileExt'];

		$path	= path::files("site_files/".authData("current_site.siteID")."/".$id);

		header("Content-Disposition: attachment; filename=\"$file_name\"");
		echo readfile($path);
	}
}



?>