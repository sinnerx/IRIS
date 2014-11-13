<?php
namespace model\file;
use db, model, session;

class Folder
{
	## return both folder and files.
	public function openFolder($siteID,$folderID = 0,$privacy = Array(1,2,3))
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
			db::where("fileFolderPrivacy",$privacy);
			$result['folders']	= db::where("siteID",$siteID)->get("file_folder")->result('fileFolderID');
			$result['files']	= model::load("file/file")->getFiles($siteID,$folderID,$privacy);	
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

	## get total folder/files inside a folder.
	## return total files or folder for all the given folders.
	public function getTotalFiles($siteID,$folderIDs,$privacy = Array(1,2,3))
	{
		$folderIDs	= !is_array($folderIDs)?Array($folderIDs):$folderIDs;

		## files.
		db::select("fileID,fileFolderID");
		db::where("siteID",$siteID);
		db::where("fileFolderID",$folderIDs);
		db::where("filePrivacy",$privacy);
		db::where("fileStatus",1);

		$res_file = db::get("file")->result("fileFolderID",true);

		$result['files']	= Array();
		foreach($folderIDs as $folderID)
			$result['files'][$folderID]	= !isset($res_file[$folderID])?0:count($res_file[$folderID]);

		## folders
		db::select("fileFolderID,fileFolderParentID");
		db::where("siteID",$siteID);
		db::where("fileFolderParentID",$folderIDs);
		db::where("fileFolderPrivacy",$privacy);
		db::where("fileFolderStatus",1);

		$res_folders	= db::get("file_folder")->result("fileFolderParentID",true);

		$result['folders']	= Array();
		foreach($folderIDs as $folderID)
			$result['folders'][$folderID]	= !isset($res_folders[$folderID])?0:count($res_folders[$folderID]);

		return $result;
	}

	## lebih kurang macam update privacy untuk file.
	public function updatePrivacy($siteID,$id,$privacy = null)
	{
		if(!$privacy)
		{
			$nextPrivacyMap	= Array(1=>2,2=>3,3=>1);
			$privacy	= db::select("fileFolderPrivacy")->where("siteID",$siteID)->where("fileFolderID",$id)->get("file_folder")->row("fileFolderPrivacy");
			$privacy	= $nextPrivacyMap[$privacy];
		}

		db::where("siteID",$siteID)->where("fileFolderID",$id);
		db::update("file_folder",Array("fileFolderPrivacy"=>$privacy,"fileFolderUpdatedDate"=>now()));

		return $privacy;
	}
}




?>