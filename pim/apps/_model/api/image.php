<?php
namespace model\api;
use apps, url, model;
class Image
{
	## only to be used by frontend.
	public function buildPhotoUrl($photoName,$size)
	{
		if(!$photoName)
			return model::load("image/services")->getPhotoUrl(null);

		$baseUrl	= url::getProtocol().apps::config("base_url:frontend");
		return $baseUrl."/api/photo/$size/$photoName";
	}

	public function buildAvatarUrl($photoName)
	{
		if(!$photoName)
			return model::load("image/services")->getPhotoUrl(null);

		$baseUrl	= url::getProtocol().apps::config("base_url:frontend");
		return $baseUrl."/api/photo/avatar/$photoName";
	}
}



?>