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
	public function getPhotoUrl($filename,$photo_no_image = null)
	{
		$filename	= !$filename?(!$photo_no_image?$this->photo_no_image:$photo_no_image):"photo/$filename";

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

		$width		= $sizeSets['w']?:null;
		$height		= $sizeSets['h']?:null;

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
		$widthname		= !$width?"":"w$width";
		$heightname		= !$height?"":"h$height";

		$cachePath	= $this->reCreatePhotoName($cachePath,"-$widthname"."$heightname");

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
		{
			$mkdir = mkdir($cachePathS,0775,true);

			## failed mkdir.
			if(!$mkdir)
			{
				echo "Error getting image : please inform support. $cachePathS;";die;
			}
		}


		## save the cache.
		try{
			$img->save(path::asset($cachePath));
		}
		catch(Exception $e)
		{
			echo "Error retrieving image : ".$e->getMessage();
			die;
		}

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
				"page"=>Array(
							"w"=>660,
							"h"=>1000
							),
				"page_small"=>Array(
							"w"=>200,
							"h"=>500
							),
				"avatar"=>Array(
							"w"=>100,
							"h"=>200
								)
						);

		return !$name?$setsR:$setsR[$name];
	}
}

?>