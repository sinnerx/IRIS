<?php
namespace model\image;
use path, url;

class Services
{
	var $photo_no_image	= "noimage.png";

	## return path to location for photo.
	public function getPhotoPath($filename = null)
	{
		return path::asset("frontend/images/photo".($filename?"/$filename":""));
	}

	## return absolute url for the photo.
	public function getPhotoUrl($filename)
	{
		$filename	= !$filename?$this->photo_no_image:"photo/$filename";

		return url::asset("frontend/images/".$filename);
	}
}

?>