<?php
namespace model\site;
use db, model, session, request as reqs, apps;
class Site extends \Origami
{
	/**
	 * Orm information.
	 */
	protected $table = "site";
	protected $primary = "siteID";

	## ORM : site_info
	public function info()
	{
		return $this->getOne(array('site_info', 'siteInfoID'), 'siteID');
	}

	## ORM : cluster
	public function getCluster()
	{
		db::from("cluster_site");
		db::where("siteID", $this->siteID);
		return db::get()->row('clusterID');
	}

	## ORM : site_newsletter.
	public function getSiteNewsletter()
	{
		$siteNL = $this->getOne('site/newsletter', 'siteID');
		if(!$siteNL)
		{
			$siteNL = model::orm('site/newsletter')
			->create()
			->set('siteID', $this->siteID)
			->save();
		}

		$siteNL->initiate();

		return $siteNL;
	}

	## ORM : Get site's latest articles
	public function getLatestArticles()
	{
		$latestArticles = model::orm('blog/article')->
		limit(3)->
		order_by('articleID', 'desc')->
		where('siteID', $this->siteID)->
		where('articleStatus', 1)->
		execute();

		return $latestArticles;
	}

	## ORM : get site's latest activities
	public function getLatestActivities()
	{
		$latestActivities = model::orm('activity/activity')
		->where('siteID', $this->siteID)
		->where('activityApprovalStatus', 1)
		->order_by('activityID', 'desc')
		->limit(3)
		->execute();

		return $latestActivities;
	}

	/**
	 * ORM : Get all members that has mail.
	 * @return \site\member
	 */
	public function getMailedMembers()
	{
		$members = model::orm("site/member")
		->where('siteID', $this->siteID)
		->where('site_member.userID IN (SELECT userID FROM user WHERE userEmail != "")')
		->join('user', 'user.userID = site_member.userID')
		->execute();

		return $members;
	}

	/**
	 * ORM : Initiate non initiated default pages for this site.
	 */
	public function initiateDefaultPages(\Origamis $defaults)
	{
		$pageParentID = null;

		foreach($defaults as $default)
		{
			if(db::where('pageDefaultType', $default->pageDefaultType)->where('siteID', $this->siteID)->get('page')->row())
				continue;

			// pageParentID for default pages is mengenai kami one.
			if(!$pageParentID)
			{
				$pageParentID = db::where('pageDefaultType', 1)->where('siteID', $this->siteID)->get('page')->row('pageID');

				if(!$pageParentID)
				{
					// create the first page about-us
					$data_page	= array(
						"pageApprovedStatus"=>1,
						"pageName"=>"",
						"pageSlug"=>"",
						"pageText"=>"Page ini masih baru. Sila kemaskini terlebih dahulu",
						"pageDefaultType"=>1, # about-us and about us
								);

					model::load('page/page')->create($this->siteID, 1, $data_page);
					continue;
				}
			}


			$data_page = array(
				'pageParentID' => $pageParentID,
				'pageApprovedStatus' => 1,
				'pageName' => '',
				'pageSlug' => '',
				'pageText' => 'Page ini masih baru. Sila kemaskini terlebih dahulu.',
				'pageDefaultType' => $default->pageDefaultType
				);

			model::load('page/page')->create($this->siteID, 1, $data_page);
		}
	}

	/**
	 * Get cafe token. generate if ain't exists.
	 * @return string
	 */
	public function getCafeToken()
	{
		return md5($this->siteID.md5($this->siteID));
		if($this->siteCafeToken == '')
		{
			$this->siteCafeToken = md5($this->siteID.md5(microtime(true)));
			$this->save();
		}

		return $this->siteCafeToken;
	}

	## update table site_info only.
	public function updateSiteInfo($id,$data)
	{
		## if the updater one is not root.
		if(session::get("userLevel") != 99)
		{
			## approval part. make site request.
			$data['siteInfoLoaDate'] = date('Y-m-d',strtotime($data['siteInfoLoaDate']));
			$data['siteInfoCommencementDate'] = date('Y-m-d',strtotime($data['siteInfoCommencementDate']));
			$data['siteInfoActualStartDate'] = date('Y-m-d',strtotime($data['siteInfoActualStartDate']));
			$data['siteInfoSatDate'] = date('Y-m-d',strtotime($data['siteInfoSatDate']));
			$data['siteInfoOperationDate'] = date('Y-m-d',strtotime($data['siteInfoOperationDate']));
			$data['siteInfoCompletionDate'] = date('Y-m-d',strtotime($data['siteInfoCompletionDate']));
			

			model::load("site/request")->create('site.update',$id,$id,$data);
		}
		## else, directly update.
		else
		{
		    //$id = $siteID;
		   /* $name = $data['siteName'];
		    $address = $data['siteInfoAddress'];
		    $code = $data['siteRefID'];
		    $state_id = $data['stateID'];

			$url = apps::config('aveo');

		    //1: create, 2: update
		    $process = 2;

		    $myvars = 'process=' . $process;
		    $myvars .= '&id=' . $id;
		    $myvars .= '&name=' . $name;
		    $myvars .= '&address=' . $address;
		    $myvars .= '&code=' . $code;
		    $myvars .= '&state_id=' . $state_id;

		    //echo 'id: ' . $id . ' test';

		    $ch = curl_init( $url );
		    curl_setopt( $ch, CURLOPT_POST, 1);
		    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
		    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt( $ch, CURLOPT_HEADER, 0);
		    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		    $response = curl_exec( $ch );*/

			db::where("siteID",$id);
			db::update("site",Array(
							"siteName"=>$data['siteName'],
							"siteSlug"=>$data['siteSlug'],
							"stateID"=>$data['stateID'],
							"siteUpdatedDate"=>now(),
							"siteUpdatedUser"=>session::get("userID"),
							"siteRefID"=>$data['siteRefID']
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
					"siteInfoEmail"=>$data['siteInfoEmail'],
					'siteInfoLoaDate' => date('Y-m-d',strtotime($data['siteInfoLoaDate'])),
					'siteInfoCommencementDate' => date('Y-m-d',strtotime($data['siteInfoCommencementDate'])),
					'siteInfoActualStartDate' => date('Y-m-d',strtotime($data['siteInfoActualStartDate'])),
					'siteInfoSatDate' => date('Y-m-d',strtotime($data['siteInfoSatDate'])),
					'siteInfoOperationDate' => date('Y-m-d',strtotime($data['siteInfoOperationDate'])),
					'siteInfoCompletionDate' => date('Y-m-d',strtotime($data['siteInfoCompletionDate'])),
					'siteInfoParliament' => $data['siteInfoParliament'],					
					'siteInfoPhase' => $data['siteInfoPhase'],			
					'siteInfoDistrict' => $data['siteInfoDistrict'],			
					'siteInfoPopulation' => $data['siteInfoPopulation'],			
							);

			db::where("siteID",$id);
			db::update("site_info",$data);

			// *** Unity Hook : siteSync ***
			\Iris\Unity::siteSync($id);
		}
	}

	public function checkSiteRefID($siteRefID,$siteID = null)
	{
		## exception
		if($siteID)
			db::where("siteID !=",$siteID);

		db::where("siteRefID",$siteRefID);
		return db::get("site")->row();
	}
	
	public function lists($stateID = null)
	{
		db::from("site");

		if($state)
		{
			db::where("stateID",$stateID);
		}
		db::order_by("siteName","asc");
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
		db::where("siteStatus",1);
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

	public function getSiteByMember($userID,$column = null)
	{
		db::select($column);
		db::from("site_member");
		db::where("userID",$userID);
		db::join("site","site_member.siteID = site.siteID");

		return db::get()->row($column);
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
					"stateID"=>$data['stateID'],
					"siteRefID"=>$data['siteRefID'],
					"siteStatus"=> 1,
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
					"menuName"=>"Aktiviti",
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

		### comp 6 ### blog.
		$data_menu	= Array(
					"menuName"=>"Blog",
					"menuType"=>1,
					"menuNo"=>5
							);
		$menu->create($siteID,6,$data_menu);

		### comp 5 ### contact us.
		$data_menu	= Array(
					"menuName"=>"Hubungi Kami",
					"menuType"=>1,
					"menuNo"=>6
							);
		$menu->create($siteID,5,$data_menu);

		// initiate default pages other than the specified 3 above.
		$defaultPages = orm('page/page_default')->execute();
		orm('site/site')->find($siteID)->initiateDefaultPages($defaultPages);

		// Create new location & site

		/*$url = apps::config('aveo');

	    //1: create, 2: update
	    $process = 1;

	    $id = $siteID;
	    $name = $data['siteName'];
	    $address = $data['siteInfoAddress'];
	    $code = $data['siteRefID'];
	    $state_id = $data['stateID'];

	    $myvars = 'process=' . $process;
	    $myvars .= '&id=' . $id;
	    $myvars .= '&name=' . $name;
	    $myvars .= '&address=' . $address;
	    $myvars .= '&code=' . $code;
	    $myvars .= '&state_id=' . $state_id;

	    $ch = curl_init( $url );
	    curl_setopt( $ch, CURLOPT_POST, 1);
	    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt( $ch, CURLOPT_HEADER, 0);
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	    $response = curl_exec( $ch );

	    // Update manager

	    $url = 'http://localhost/aveo/app/controllers/api/user.php';

	    //1: create, 2: update, 3: change password, 4: delete, 5: update location
	    $process = 5;

	    $id = $userID;
	    $location_id = $siteID;

	    $myvars = 'process=' . $process;
	    $myvars .= '&id=' . $id;
	    $myvars .= '&location_id=' . $location_id;

	    $ch = curl_init( $url );
	    curl_setopt( $ch, CURLOPT_POST, 1);
	    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt( $ch, CURLOPT_HEADER, 0);
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	    $response = curl_exec( $ch );*/

	    // *** Unity Hook : siteSync ***
		\Iris\Unity::siteSync($siteID);

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


	public function getSitesByClusterID($clusterID)
	{
		db::select("site.siteID, siteName");
		db::join("site", "cluster_site.siteID = site.siteID");
		db::where("clusterID", $clusterID);
		return db::get("cluster_site")->result("siteID");
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

	## used by frontend/partial@pim_list
	public function getSiteByState($stateID = null)
	{
		## select everything except manager siteID.
		$managerSiteID	= model::load("config")->get("configManagerSiteID");

		if($managerSiteID != 0)
		{
			db::where("siteID !=",$managerSiteID);
		}

		if($stateID)
		{
			db::where("stateID",$stateID);
		}
		db::where("siteStatus",1);

		return db::get("site")->result("stateID",true);
	}

	## get site slug, by given one id or list of id. and return like $siteID=>$siteSlug style for list, $siteSlug for one.
	public function getSiteSlug($siteID)
	{
		db::from("site");
		db::select("siteSlug");

		db::where("siteID",$siteID);

		if(is_array($siteID))
		{
			$arr	= Array();
			$res	= db::get()->result();

			if($res)
			{
				foreach($res as $row)
				{
					$arr[$row['siteID']]	= $row['siteSlug'];
				}
			}

			return $arr;
		}
		else
		{
			return db::get()->row("siteSlug");
		}

	}

	public function getSiteRefIDBySiteID($siteID)
	{
		return db::where("siteID",$siteID)->get("site")->row("siteRefID");
	}

	public function getSiteRefIDByUserID($userID)
	{
		return db::where("siteID IN (SELECT siteID FROM site_member WHERE userID = ?)",Array($row['userID']))->get("site")->row("siteRefID");
	}

	## generate a new siteID, if there null siteRefID still unappended, will return false.
	public function getNewSiteID()
	{
		## check if there're still an empty siteID.
		db::where("siteRefID",null);

		if(db::get("site")->row())
			return false;

		## get last siteRefID.
		db::limit(1);
		db::order_by("siteRefID","desc");
		$lastID = db::get("site")->row("siteRefID");

		return $lastID+1;
	}

	public function getAllSite()
	{
		return db::get("site")->result();
	}


	public function getFacebookPageId($siteID)
	{

			db::where("siteID",$siteID);
			return db::get("site_info")->row("siteInfoFacebookPageId");
	}


	public function setFacebookPageId($id,$pageId)
	{

			db::where("siteID",$id);
		
			db::update("site_info",Array("siteInfoFacebookPageId"=>$pageId));
		
	}

	public function getNewsletter($siteID)
	{
		$newsletter = db::select('mailChimpListID')->where('siteID', $siteID)->get('site_newsletter')->row();

		if(!$newsletter)
		{
			db::insert('site_newsletter', array(
				'siteID'=>$siteID
				));

			$newsletter = db::getLastID('site_newsletter', 'siteNewsletterID', true);
		}

		return $newsletter;
	}

	/**
	 * ORM : site_cache
	 */
	/*public function getCache()
	{
		$cache = $this->getOne('site/cache', 'siteID');

		if(!$cache)
		{
			$cache = model::orm('site/cache')
			->create()
			->set('siteID', $this->siteID)
			->save();
		}

		return $cache;
	}*/

	public function getCache($key)
	{
		$cache = model::orm('site/cache')->where('siteCacheKey', $this->siteID.'_'.$key)->execute()->getFirst();

		if(!$cache)
		{
			$cache = model::orm('site/cache')
			->create()
			->set('siteID', $this->siteID)
			->set('siteCacheKey', $this->siteID.'_'.$key)
			->set('siteCacheData', serialize(array()))
			->save();
		}

		return $cache;
	}

	public function get_list_site($q){
		//var_dump($q);
	    db::select('siteID, siteName');
	    db::where('siteName LIKE ?', "%".$q."%");
	    db::from('site');
	    
	    //var_dump($query);
	    //var_dump(db::num_rows());
	    
	    //var_dump($query->result());
	    if(db::num_rows() > 0){
	    	$query = db::get();
	      foreach ($query->result() as $row){
	      	//var_dump($query->result());
	        $new_row['label']=htmlentities(stripslashes($row['siteName']));
	        $new_row['value']=htmlentities(stripslashes($row['siteID']));
	        $row_set[] = $new_row; //build an array
	      }
	      echo json_encode($row_set); //format the array into json data
	    }
	}	

	public function deleteSite($siteID)
	{
		db::where("siteID",$siteID);
		db::update("site",Array("siteStatus"=>3));
		
		##
		model::load("user/activity")->create($siteID,session::get("userID"),"site.delete");
	}

	public function unlockSite($siteID, $date)
	{
		db::where("siteID", $siteID);
		db::update("site", Array("siteUnlockDate"=> $date));
	}

	public function listUnlockSite()
	{
		db::from("site");
		//db::where("siteUnlockDate > (DATE_SUB(NOW(), INTERVAL 24 HOUR))");
		db::where("DATE(siteUnlockDate) = CURDATE()");
		$result	= db::get()->result();
		//var_dump($result);
		//die;
		return $result;
	}
}

?>