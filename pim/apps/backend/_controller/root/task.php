<?php

class Controller_Task
{
	var $template	= false;

	## wriiten in 21/7, to use for updating siteRefID with the old siteID.
	public function updateSiteRefID()
	{
		if(request::get("skip"))
		{
			db::where("siteID",request::get("skip"));
			db::update("site",Array("siteRefID"=>"999999")); ## skip value.
			redirect::to("task/updateSiteRefID");
		}

		if(request::get("siteID"))
		{
			db::where("siteID",request::get("siteID"));
			db::update("site",Array("siteRefID"=>request::get("siteRefID")));
			redirect::to("task/updateSiteRefID");
		}

		if(request::get("showSkip"))
		{
			db::where("siteRefID",999999);
			db::update("site",Array("siteRefID"=>0));
			redirect::to("task/updateSiteRefID");
		}

		db::where("isNull(siteRefID) OR siteRefID = 0");
		db::limit(1); # 1 at a time.
		$data['row']	= db::get("site")->row();

		## get list of 
		db::where("siteRefID IS NOT NULL");
		$exists	= db::get("site")->result("siteRefID");

		## prepare list of csv.
		$data['csv']	= Array();
		foreach($this->getSiteIDCSV() as $n=>$line)
		{
			if($n == 0)
				continue;

			list($siteRefID,$siteName)	= explode(",",$line);
			if(isset($exists[$siteRefID]))
				continue;

			$data['csv'][$siteRefID]	= $siteName;
		}

		view::render("root/task/updateSiteRefID",$data);
	}

	private function getSiteIDCSV()
	{
		$file	= path::files("release2/site_id.csv");

		return str_getcsv(file_get_contents($file),"\n");
	}

	## controller to uploadUser.
	public function uploadUser()
	{
		if(!request::get("file"))
			return;
		set_time_limit(1000);
		$csv	= $this->getUserCSV("release2/".request::get("file"));

		## insert all.
		foreach($csv->data as $no=>$row)
		{
			## recorrect some data.
			list($d,$m,$y)	= explode("/",$row['lastlogin']);
			$row['lastlogin']	= "$y-$m-$d";

			list($d,$m,$y)	= explode("/",$row['datecreated']);
			$row['datecreated']	= "$y-$m-$d";

			list($d,$m,$y)	= explode("/",$row['DOB']);
			$row['DOB']	= "$y-$m-$d";

			## save.
			model::load("site/member")->importTemporaryUser($row);
		}

		view::render("root/task/uploadUser",$data);
	}

	private function getUserCSV($name)
	{
		$csv = new parseCSV(path::files($name));

		return $csv;
	}

	## run first, before launching the v2.
	public function migratePagePhoto()
	{
		db::from("page_photo");
		db::join("photo","page_photo.photoID = photo.photoID");

		$res_photo	= db::get()->result();

		foreach($res_photo as $row)
		{
			$pageID	= $row['pageID'];
			db::where("pageID",$pageID)->update("page",Array("pagePhoto"=>$row['photoName']));
		}
	}
}



?>