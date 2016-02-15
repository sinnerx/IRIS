<?php
class Controller_Ajax_Image
{
	public function albumPhotos($id)
	{
		$data['res_photo']	= model::load("image/album")->getPhotos($id);
		$data['row']		= model::load("image/album")->getAlbum($id);

		view::render("shared/image/ajax/albumPhotos",$data);
	}

	public function albumAddPhoto($id)
	{
		view::render("shared/image/ajax/albumAddPhoto");
	}
}