<?php
Class Model_Site
{
	public function lists($stateID = null)
	{
		db::from("site");

		if($state)
		{
			db::where("stateID",$stateID);
		}

		$result	= db::get()->result();

		return $result;
	}

	## return site, site_info by siteID (or by current site slug, if null.)
	public function getSite($siteID = null)
	{
		$siteID	= !$siteID?$this->getSiteIDBySlug():$siteID;

		db::from("site");
		db::where("site.siteID",$siteID);
		db::join("site_info","site.siteID = site_info.siteID");
		db::join("user_profile","user_profile.userID IN (SELECT userID FROM site_manager WHERE site.siteID = site_manager.siteID)");

		return db::get()->row();
	}

	## used in hooked controller auth, and site/add
	public function checkSiteSlug($slug	= null)
	{
		## get slug from param or named request (if null param)
		$slug	= !$slug?request::named("site-slug"):$slug;
		$slug	= trim($slug);

		## query.
		$result	= db::from("site")->where("siteSlug",$slug)->get()->result();	
		return !$result?false:true;
	}

	public function getSiteIDBySlug($slug = null)
	{
		$slug	= !$slug?request::named("site-slug"):$slug;

		$slug	= trim($slug);
		db::from("site");
		db::where("siteSlug",$slug);
		$siteID	= db::get()->row('siteID');

		return $siteID?$siteID:false;
	}

	## return along with state
	public function getSiteName($slug = null)
	{
		$slug	= !$slug?request::named("site-slug"):$slug;
		db::select("siteName,stateID")->from("site")->where("siteSlug",$slug);
		$row	= db::get()->row();

		return $row['siteName'].", Selangor";
	}

	## used in site/add
	public function checkManager($email)
	{
		db::from("user")->where(Array("userEmail"=>$email));
		$row	= db::get()->row();

		## no record.
		if(!$row)
		{
			return Array(false,"Couldn't find email record.");
		}

		## email to level 2 type user
		if($row['userLevel'] != 2)
		{
			return Array(false,"This is email belong not to a site manager.");
		}

		## check if manager already exists for some site.
		db::from("site_manager");
		db::where(Array(
					"userID"=>$row['userID'],
					"siteManagerStatus"=>1
						));

		$row_site	= db::get()->row();
		if($row_site)
		{
			return Array(false,"This manager already registered to another site.");
		}

		return Array(true,$row['userID']);
	}

	## return row of site and site_info. if column is filled, return that site column value instead
	public function getSiteByManager($userID = null,$column = null)
	{
		## if not set, get from session.
		$userID	= !$userID?session::get("userID"):$userID;

		db::from("site");
		db::where("site.siteID IN (SELECT siteID FROM site_manager WHERE userID = '$userID' AND siteManagerStatus = '1')");
		db::join("site_info","site.siteID = site_info.siteID");

		##
		$row	= db::get()->row($column);
		return $row;
	}

	## update table site_info only.
	public function updateSiteInfo($id,$data)
	{
		$data	= Array(
				"siteInfoLatitude"=>$data['siteInfoLatitude'],
				"siteInfoLongitude"=>$data['siteInfoLongitude'],
				"siteInfoPhone"=>$data['siteInfoPhone'],
				"siteInfoAddress"=>$data['siteInfoAddress'],
				"siteInfoDescription"=>$data['siteInfoDescription'],
				"siteInfoFax"=>$data['siteInfoFax']
						);

		db::where("siteID",$id);
		db::update("site_info",$data);
	}

	## get manager info. return array of columns.
	public function getManagerInfo($siteID = null,$columns = null)
	{
		$siteID		= !$siteID?$this->getSiteIDBySlug():$siteID;
		$columns	= !$columns?"*":$columns;

		db::select($columns);
		db::from("user");
		db::where("user.userID IN (SELECT userID FROM site_manager WHERE siteID = '$siteID')");
		db::join("user_profile","user_profile.userID = user.userID");

		return db::get()->row();
	}

	## create site. used by site/add
	public function createSite($data)
	{
		## create site first.
		$data_site	= Array(
					"siteName"=>$data['siteName'],
					"siteSlug"=>$data['siteSlug'],
					"siteCreatedDate"=>now(),
					"siteCreatedUser"=>session::get("userID"),
					"stateID"=>$data['stateID']
							);

		db::insert("site",$data_site);

		$siteID	= db::getLastID("site","siteID");

		## create site_manager.
		$data_manager	= Array(
					"siteID"=>$siteID,
					"userID"=>$data['userID'],
					"siteManagerCreatedDate"=>now(),
					"siteManagerCreatedUser"=>session::get("userID"),
					"siteManagerStatus"=>1
								);

		db::insert("site_manager",$data_manager);

		## create site_info
		$data_siteinfo	= Array(
					"siteID"=>$siteID,
					"siteInfoPhone"=>$data['siteInfoPhone'],
					"siteInfoFax"=>$data['siteInfoFax'],
					"siteInfoLatitude"=>$data['siteInfoLatitude'],
					"siteInfoLongitude"=>$data['siteInfoLongitude'],
					"siteInfoAddress"=>$data['siteInfoAddress'],
					"siteInfoDescription"=>$data['siteInfoDescription']
								);

		db::insert("site_info",$data_siteinfo);

		## create menu for the site.
		### comp 1 ### pages
		# create page type 1, about-us.
		$page		= model::load("page");
		$menu		= model::load("menu");

			$data_page	= Array(
						"pageApprovedStatus"=>1,
						"pageName"=>"",
						"pageSlug"=>"",
						"pageDefaultType"=>1, # about-us and about us
								);

			## insert and return pageID
			$pageID		= $page->create($siteID,1,$data_page);

		# create menu
		$data_menu	= Array(
					"menuName"=>"Tentang Kami",
					"menuType"=>1,#top menu
					"menuNo"=>2,
					"menuRefID"=>$pageID
							);
		$menu->create($siteID,1,$data_menu);

			# create page type 2, about-manager, children to the about-us.
			$data_page	= Array(
						"pageParentID"=>$pageID,
						"pageApprovedStatus"=>1,
						"pageName"=>"",
						"pageSlug"=>"",
						"pageDefaultType"=>2
								);
			$page->create($siteID,1,$data_page);

		### comp 2 ### main landing.
		$data_menu	= Array(
					"menuName"=>"Utama",
					"menuType"=>1,
					"menuNo"=>1
							);

		$menu->create($siteID,2,$data_menu);

		### comp 3 ### activity
		$data_menu	= Array(
					"menuName"=>"Activiti",
					"menuType"=>1,
					"menuNo"=>3
							);
		$menu->create($siteID,3,$data_menu);

		### comp 4 ### members.
		$data_menu	= Array(
					"menuName"=>"Ruangan Ahli",
					"menuType"=>1,
					"menuNo"=>4
							);
		$menu->create($siteID,4,$data_menu);

		### comp 5 ### contact us.
		$data_menu	= Array(
					"menuName"=>"Hubungi Kami",
					"menuType"=>1,
					"menuNo"=>5
							);
		$menu->create($siteID,5,$data_menu);

	}

	public function getSlider($siteID,$all = true)
	{
		db::from("site_slider");

		if(!$all)
		{
			db::where("siteSliderStatus",1);
		}

		db::where("siteID",$siteID);
		return db::get()->result();
	}

	public function addSlider($siteID,$data)
	{
		$data	= Array(
				"siteID"=>$siteID,
				"siteSliderType"=>1,
				"siteSliderTarget"=>1,
				"siteSliderStatus"=>1,
				"siteSliderName"=>$data['siteSliderName'],
				"siteSliderImage"=>$data['siteSliderImage'],
				"siteSliderLink"=>$data['siteSliderLink'],
				"siteSliderCreatedDate"=>now(),
				"siteSliderCreatedUser"=>session::get("userID")
						);

		db::insert("site_slider",$data);
	}

	public function removeSlider($id)
	{
		db::where("siteSliderID",$id);
		db::update("site_slider",Array("siteSliderStatus"=>0));
		#db::delete("site_slider",Array("siteSliderID"=>$id));
	}

	public function toggleSlider($id)
	{
		$current	= db::select("siteSliderStatus")->where("siteSliderID",$id)->get("site_slider")->row("siteSliderStatus");
		$status		= $current == 1?0:1;

		db::where("siteSliderID",$id);
		db::update("site_slider",Array("siteSliderStatus"=>$status));
	}
}


?>