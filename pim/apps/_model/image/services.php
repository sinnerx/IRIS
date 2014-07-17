<?php
namespace model\image;
use path, url, abeautifulsite\SimpleImage as SimpleImage;

class Services
{
	var $photo_no_image	= "noimage.png";

	## return path to location for site photo.
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

	## return path to cached folder.
	public function getPhotoCachePath($filename = null)
	{
		return "frontend/images/cache/site_photo".($filename?"/$filename":"");
	}

	## return the SimpleImage object, for site_photo.
	public function getResizedPhoto($photoName,$sizeName)
	{
		## site.
		return $this->_getResizedPhoto($photoName,$sizeName,"site");
	}

	## main worker function.
	private function _getResizedPhoto($photoName,$sizeName,$type)
	{
		## get width and height based on sizeName
		$sizeSets	= $this->sizeSets($sizeName);
		if(!$sizeSets)
			return false;

		$width		= $sizeSets['w'];
		$height		= $sizeSets['h'];

		if($type == "site")
		{
			$cachePath	= $this->getPhotoCachePath($photoName);
			$photoPath	= $this->getPhotoPath($photoName);
		}
		## later for user photo. (if ever got)
		else
		{

		}

		#$cachePathR	= explode("\\",$cachePath);
		#$newPhotoName	= array_pop($cachePathR);

		$cachePath	= $this->reCreatePhotoName($cachePath,"-w$width"."h$height");

		## check if image already exists in cache, just return the cached one.
		if(file_exists(path::asset($cachePath)))
		{
			$img	= new SimpleImage(path::asset($cachePath));
			return $img;
		}

		## else create new image, along with their cache.
		$img		= new SimpleImage($photoPath);

		## best fit.
		$img->best_fit($width,$height);

		## save the file to frontend/cache/$type
		## create path if not exists.
		$cachePathR	= explode("/",$cachePath);
		array_pop($cachePathR);

		$cachePathS	= path::asset(implode("/",$cachePathR));

		if(!is_dir($cachePathS))
			mkdir($cachePathS,0755,true);

		## save the cache.
		$img->save(path::asset($cachePath));

		return $img;
	}

	public function reCreatePhotoName($original,$additional_str)
	{
		$pathR	= explode("/",$original);
		$photoName	= array_pop($pathR);

		## remove extension.
		$photoNameR	= explode(".",$photoName);
		$ext		= array_pop($photoNameR);

		$photoName  = implode(".",$photoNameR).$additional_str.".".$ext;

		return implode("/",$pathR)."/".$photoName;
	}

	public function sizeSets($name = null)
	{
		$setsR	= Array(
				"small"=>Array(
							"w"=>200,
							"h"=>200
								),
				"big"=>Array(
							"w"=>700,
							"h"=>400
								),
						);

		return !$name?$setsR:$setsR[$name];
	}
}

?>