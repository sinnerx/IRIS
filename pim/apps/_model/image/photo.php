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

	## main method to save photo into db.and return photo name.
	public function addSitePhoto($siteAlbumID,$name,$desc = null)
	{
		$siteID		= model::load("access/auth")->getAuthData("site","siteID");

		## add photo.
		$addPhoto	= $this->_addPhoto($name,$desc);

		$data	= Array(
			"photoID"=>$addPhoto[1],
			"siteID"=>$siteID,
			"siteAlbumID"=>$siteAlbumID
				);

		db::insert("site_photo",$data);

		// return $this->_add(2,$albumID,$name,$desc);
		return $addPhoto[0]; ## return name.
	}

	private function _addPhoto($originalname,$desc)
	{
		$photoNameR	= $this->_createPhotoFullname($originalname);

		$data	= Array(
				"photoName"=>$photoNameR[0],
				"photoOriginalName"=>$photoNameR[1],
				"photoDescription"=>$desc,
				"photoCreatedUser"=>session::get("userID"),
				"photoCreatedDate"=>now()
						);

		db::insert("photo",$data);

		return Array($photoNameR[0],db::getLastID("photo","photoID"));
	}

	## create and format by date. example  year/month/day
	private function _createPhotoFullname($originalname)
	{
		# slugify.
		$originalname	= explode(".",$originalname);
			$ext		= array_pop($originalname);

		$filename	= model::load("helper")->slugify(implode(".",$originalname));

		## re-add ext.
		$originalname	.= ".".$ext;
		$filename		.= ".".$ext;

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