<?php
class Controller_Config
{
	public function index()
	{
		if(request::get("create"))
		{
			model::load("config")->createConfig();
			redirect::to("config/index","Config record created");
		}

		## get row of config.
		$data['row_conf']	= model::load("config")->get();

		## configManagerSiteID name.
		$siteID	= $data['row_conf']['configManagerSiteID'];

		$data['configManagerSiteName']	= "Null";
		if($siteID != 0)
		{
			$row_site	= model::load("site/site")->getSite($siteID);
			$data['configManagerSiteName']	= $row_site['siteName'];
		}

		view::render("root/config/index",$data);
	}

	public function update()
	{
		if(form::submitted())
		{
			$column	= input::get("column");
			$value	= input::get("value");

			if($value == "")
			{
				redirect::to("config/index","Cannot have an empty value","error");
			}

			model::load("config")->set($column,$value);

			redirect::to("config/index","Updated value for $column to $value");
		}
	}
}