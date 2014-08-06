<?php
namespace model\image;
use session, model, db;
class Photo extends Services
{
	## main method to save photo into db.and return full path of photo.
	public function addUserPhoto($userAlbumID,$name,$desc = null)
	{
		$userID	= model::load("access/auth")->getAuthData("user","userID");

		#return $this->_add(1,$userAlbumID,$name,$desc);
		$addPhoto	= $this->_addPhoto($name,$desc);

		$data_userphoto	= Array(
					"photoID"=>$addPhoto[1],
					"userID"=>$userID,
					"userAlbumID"=>$userAlbumID
								);

		db::insert("user_photo",$data_userphoto);

		## return his photo.
		return $addPhoto[0];
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
			"siteAlbumID"=>$siteAlbumID,
			"sitePhotoStatus"=>1
				);

		db::insert("site_photo",$data);

		// return $this->_add(2,$albumID,$name,$desc);
		return $addPhoto[0]; ## return name.
	}

	public function deleteSitePhoto($siteID,$sitePhotoID)
	{
		db::where(Array(
				"sitePhotoID"=>$sitePhotoID,
				"siteID"=>$siteID,
				"sitePhotoStatus"=>1,
						))->update("site_photo",Array(
											"sitePhotoStatus"=>2 ## deleted.
													));
	}

	public function undeleteSitePhoto($siteID,$sitePhotoID)
	{
		db::where(Array(
				"sitePhotoID"=>$sitePhotoID,
				"sitePhotoStatus"=>2,
				"siteID"=>$siteID
						))->update("site_photo",Array(
											"sitePhotoID"=>1
													));
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
		{
			$mkdir = mkdir($this->getPhotoPath($path),0775,true);
			if(!$mkdir)
			{
				echo "Permission problem. please contact support through www.celcom1cbc.com";
				die;
			}
		}

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

	## return photo.photoName
	public function getSiteLatestPhoto($siteID)
	{
		db::select("photoName");
		db::where("siteID",$siteID);
		db::where("sitePhotoStatus",1);
		db::order_by("sitePhotoID","DESC");
		db::join("photo","site_photo.photoID = photo.photoID");
		db::limit(1);

		return db::get("site_photo")->row("photoName");
	}

	public function getSitePhoto($siteID,$sitePhotoID)
	{
		db::where("siteID",$siteID);
		db::where("sitePhotoID",$sitePhotoID);
		db::join("photo","site_photo.photoID = photo.photoID");

		return db::get("site_photo")->row();
	}

	public function changePhotoDescription($siteID,$sitePhotoID,$desc)
	{
		db::where("photoID IN (SELECT photoID FROM site_photo WHERE siteID = ? AND sitePhotoID = ?)",Array($siteID,$sitePhotoID));
		db::update("photo",Array(
						"photoDescription"=>$desc
								));
	}

	
}



?>