<?php
class Controller_Image
{
	public function get($year,$month = null,$day = null,$name = null,$size = null)
	{
		## get trailing.
		#$trail	= explode("/",request::named("trail"));

		## get first parameter for this api.
		#$firstParam	= $trail[0];

		## first load image path
		#$row_image	= model::load("image/photo")->getSitePhoto($siteID,$sitePhotoID);

		## not found. return error.
		/*if(!$row_image)
		{
			return false;
		}*/

		## page photo.
		if(!$day)
		{
			$size		= $year;
			$photoName	= $month;
		}
		## site photo.
		else
		{
			## get image.
			$photoName	= "$year/$month/$day/$name";
		}
		$img	= model::load("image/services")->getResizedPhoto($photoName,$size);

		if(!$img)
			return false;

		$img->output();
		die;
	}
}