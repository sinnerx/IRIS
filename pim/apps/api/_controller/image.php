<?php
class Controller_Image
{
	public function get($year,$month,$day,$name,$size)
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

		## get image.
		$photoName	= "$year/$month/$day/$name";
		$img	= model::load("image/services")->getResizedPhoto($photoName,$size);

		if(!$img)
			return false;

		$img->output();
	}
}