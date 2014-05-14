<?php
namespace model\site;
use db, model, session, request as reqs;
class Site
{
	## update table site_info only.
	public function updateSiteInfo($id,$data)
	{
		## if the updater one is not root.
		if(session::get("userLevel") != 99)
		{
			## approval part. make site request.
			model::load("site/request")->create(3,$id,$id,$data);
		}
		## else, directly update.
		else
		{
			db::where("siteID",$id);
			db::update("site",Array(
							"siteName"=>$data['siteName'],
							"siteSlug"=>$data['siteSlug'],
							"stateID"=>$data['stateID'],
							"siteUpdatedDate"=>now(),
							"siteUpdatedUser"=>session::get("userID")
									));

			$data	= Array(
					"siteInfoLatitude"=>$data['siteInfoLatitude'],
					"siteInfoLongitude"=>$data['siteInfoLongitude'],
					"siteInfoPhone"=>$data['siteInfoPhone'],
					"siteInfoAddress"=>$data['siteInfoAddress'],
					"siteInfoDescription"=>$data['siteInfoDescription'],
					"siteInfoFax"=>$data['siteInfoFax'],
					"siteInfoTwitterUrl"=>$data['siteInfoTwitterUrl'],
					"siteInfoFacebookUrl"=>$data['siteInfoFacebookUrl'],
					"siteInfoEmail"=>$data['siteInfoEmail']
							);

			db::where("siteID",$id);
			db::update("site_info",$data);
		}
	}
	
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
		db::join("site_info","site.siteID = site_info.siteID");/*
		db::join("user_profile","user_profile.userID IN (SELECT userID FROM site_manager WHERE site.siteID = site_manager.siteID)");
		db::join("user","user.userID = user_profile.userID");*/

		return db::get()->row();
	}

	public function getSiteIDBySlug($slug = null)
	{
		$slug	= !$slug?reqs::named("site-slug"):$slug;

		$slug	= trim($slug);
		db::from("site");
		db::where("siteSlug",$slug);
		$siteID	= db::get()->row('siteID');

		return $siteID?$siteID:false;
	}

	public function getSiteBySlug($slug)
	{
		db::from("site");
		db::where("siteSlug",$slug);
		db::join("site_info","site_info.siteID = site.siteID");

		return db::get()->row();
	}

	## return along with state
	public function getSiteName($slug = null)
	{
		$stateR	= model::load("helper")->state();
		$slug	= !$slug?reqs::named("site-slug"):$slug;
		db::select("siteName,stateID")->from("site")->where("siteSlug",$slug);
		$row	= db::get()->row();

		return $row['siteName'].", ".$stateR[$row['stateID']];
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
		foreach($data['userID'] as $userID)
		{
			$data_manager	= Array(
					"siteID"=>$siteID,
					"userID"=>$userID,
					"siteManagerCreatedDate"=>now(),
					"siteManagerCreatedUser"=>session::get("userID"),
					"siteManagerStatus"=>1
								);

			db::insert("site_manager",$data_manager);
		}

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
		$page		= model::load("page/page");
		$menu		= model::load("site/menu");

			$data_page	= Array(
						"pageApprovedStatus"=>1,
						"pageName"=>"",
						"pageSlug"=>"",
						"pageText"=>"Page ini masih baru. Sila kemaskini terlebih dahulu",
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
						"pageText"=>"Page ini masih baru. Sila kemaskini terlebih dahulu",
						"pageSlug"=>"",
						"pageDefaultType"=>2 #tentang pengurus
								);
			$page->create($siteID,1,$data_page);

			# create page type 2, ajk-kampung, children to about-us
			$data_page	= Array(
						"pageParentID"=>$pageID,
						"pageApprovedStatus"=>1,
						"pageText"=>"Page ini masih baru. Sila kemaskini terlebih dahulu",
						"pageName"=>"",
						"pageSlug"=>"",
						"pageDefaultType"=>3 #ajk kampung.
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

		return $this->getSite($siteID);
	}

	//get links like twitter, facebook and email.
	public function getLinks($siteID)
	{
		db::select("siteInfoTwitterUrl,siteInfoFacebookUrl,siteInfoEmail");
		db::where("siteID",$siteID);
		return db::get("site_info")->row();
	}

	public function updateSiteImage($siteID,$filename)
	{
		db::where("siteID",$siteID);
		db::update("site_info",Array("siteInfoImage"=>$filename));
	}

	## cluster lead.
	public function getSitesByClusterlead($userID)
	{
		db::from("site")
		->where("siteID IN (SELECT siteID FROM cluster_site WHERE clusterID IN (SELECT clusterID FROM cluster_lead WHERE userID = '$userID' AND clusterLeadStatus = '1'))");

		return db::get();
		#db::from("site")->where("")
	}

	public function getAllSiteWithCluster($clusterID = null)
	{
		db::select("site.*,clusterSiteID,clusterID");
		db::from("site");
		if($clusterID)
		{
			db::where("clusterID",$clusterID);
		}
		db::order_by("stateID");
		db::join("cluster_site","cluster_site.siteID = site.siteID");

		return db::get()->result('stateID',true);
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

	public function unassignUser($siteID,$userID)
	{
		db::where(Array(
					"siteID"=>$siteID,
					"userID"=>$userID
						));

		## just update.
		db::update("site_manager",Array("siteManagerStatus"=>0,"siteManagerUpdatedUser"=>session::get("userID"),"siteManagerUpdatedDate"=>now()));
	}

	public function assignManager($siteID,$userID)
	{
		## create record, if already exists use it.
		$siteManagerID	= db::select("siteManagerID")->where("userID",$userID)->get("site_manager")->row("siteManagerID");

		$data	= Array("siteManagerStatus"=>1,
						"siteID"=>$siteID,
						"userID"=>$userID,
						"siteManagerCreatedDate"=>now(),
						"siteManagerCreatedUser"=>session::get("userID"));

		if($siteManagerID)
		{
			db::where("siteManagerID",$siteManagerID);
			db::update("site_manager",$data);
			return;
		}

		## else create.
		db::insert("site_manager",$data);
	}

	public function getSiteByState($stateID = null)
	{
		if($stateID)
		{
			db::where("stateID",$stateID);
		}

		return db::get("site")->result("stateID",true);
	}
}

?>