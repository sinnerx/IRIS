<?php
namespace model\file;
use db, model, session;

class Folder
{
	## return both folder and files.
	public function openFolder($siteID,$folderID = 0)
	{
		$result['header']	= Array();
		$result['folders']	= Array();
		$result['files']	= Array();

		$failed	= false;

		$result['header']	= $this->findParents($folderID);
		/*if($folders == "")
		{
			$parentID		= 0;
		}
		else
		{
			$parentID	= 0;
			foreach(explode("/",$folders) as $folder)
			{
				// $row		= db::where("fileFolderID",array_keys($res))->where("fileFolderParentID",$folder)->get("file_folder")->row();
				$res	= db::select("fileFolderID")->where("fileFolderID",$parentID)->get("file_folder")->result("fileFolderID");
				if(!$res)
				{
					$failed	= true;
					break;
				}

				$row	= db::where("fileFolderParentID",$folder)->where("fileFolderID",array_keys($res))->get("file_folder")->row();

				if(!$row)
				{
					$failed	= true;
				}

				$result['header'][$row['fileFolderName']]	= $row['fileFolderName'];

				$parentID	= $row['fileFolderID'];
			}
		}*/

		if(!$failed)
		{
			db::where("fileFolderStatus",1);
			db::where("fileFolderParentID",$folderID);
			$result['folders']	= db::where("siteID",$siteID)->get("file_folder")->result();
			$result['files']	= model::load("file/file")->getFiles($siteID,$folderID);	
		}

		// var_dump($result);

		return $result;
	}

	public function findParents($folderID)
	{
		$headers	= Array();
		## recursively traverse back looking for his anchestor.
		$no = 1;
		while(true)
		{
			if($folderID == 0)
				break;

			$row	= db::select("fileFolderParentID,fileFolderID,fileFolderName")->where("fileFolderID",$folderID)->get("file_folder")->row();

			if(!$row)
				break;

			$headers[]	= $row;

			$folderID	= $row['fileFolderParentID'];

			$no++;
			if($no > 100)
			{
				break;
			}
		}

		return $headers;
	}

	public function addFolder($siteID,$parentID,$data)
	{
		$data	= Array(
			"siteID"=>$siteID,
			"fileFolderParentID"=>$parentID,
			"fileFolderName"=>$data['fileFolderName'],
			"fileFolderPrivacy"=>$data['fileFolderPrivacy'],
			"fileFolderCreatedDate"=>now(),
			"fileFolderStatus"=>1,
			"fileFolderCreatedUser"=>session::get("userID"),
			);

		db::insert("file_folder",$data);
	}

	public function removeFolder($siteID,$folderID)
	{
		db::where("siteID",$siteID)->where("fileFolderID",$folderID)->update("file_folder",Array("fileFolderStatus"=>2));
	}
}




?>