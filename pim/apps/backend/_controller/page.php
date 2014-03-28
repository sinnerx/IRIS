<?php

Class Controller_Page
{
	public function index()
	{
		$siteID					= model::load("site")->getSiteByManager(null,"siteID");
		$data['res_page']		= model::load("page")->listBySite($siteID);
		$data['pageDefault']	= model::load("page")->getDefault();

		view::render("page/index",$data);
	}

	public function uploadImage($pageID)
	{
		$file	= input::file("pageImage");

		if($file)
		{
			$path	= path::asset("frontend/images/photo");
			$filename	= $pageID.time().".".$file->get("ext");

			if($file->move($path,$filename))
			{
				## upload into db.
				model::load("page")->addPhoto($pageID,$filename);

				$data['uploadedUrl']	= url::asset("frontend/images/photo/$filename");

				$this->template	= false;
				view::render("page/_imageUpload",$data);
			}
		}
	}

	public function test()
	{
		$this->template	= false;
		view::render("page/test");
	}
}


?>