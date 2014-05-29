<?php
namespace model\image;
use session, model, db;
class Photo extends Services
{
	## main method to save photo into db.and return full path of photo.
	public function addUserPhoto($albumID,$name,$desc = null)
	{
		return $this->_add(1,$albumID,$name,$desc);
	}

	## main method to save photo into db.and return full path of photo.
	public function addSitePhoto($albumID,$name,$desc = null)
	{		
		return $this->_add(2,$albumID,$name,$desc);
	}

	private function _add($type,$albumID,$originalname,$desc)
	{
		$siteID		= model::load("site/site")->getSiteByManager(session::get("userID"),"siteID");

		## create photo name.
		$photonameR	= $this->_createPhotoFullname($originalname);
		$data	= Array(
				"siteID"=>$siteID,
				"photoType"=>$type,
				"albumID"=>$albumID,
				"photoName"=>$photonameR[0],
				"photoOriginalName"=>$photonameR[1],
				"photoDescription"=>$desc,
				"photoCreatedUser"=>session::get("userID"),
				"photoCreatedDate"=>now()
						);

		db::insert("photo",$data);

		## return photo name along with it's full path.
		return $this->getPhotoPath($photonameR[0]);
	}

	## create and format by date. example  year/month/day
	private function _createPhotoFullname($originalname)
	{
		# slugify.
		$filename	= model::load("helper")->slugify($originalname);

		$path	= date("Y/m/d");

		## create path if not exists.
		if(!is_dir($this->getPhotoPath($path)))
			mkdir($this->getPhotoPath($path),0755,true);

		$finalname	= $path."/".$filename;

		## append to filename.
		$check	= $this->checkSamePhoto($finalname);

		## create fullname.
		$fullname	= ($check?$path."/".count($check)."-".$filename:$finalname);

		return Array($fullname,$finalname);
	}

	private function checkSamePhoto($filename)
	{
		return db::where("photoOriginalName",$filename)->get("photo")->result();
	}
}



?>