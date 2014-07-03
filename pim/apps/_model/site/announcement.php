<?php
namespace model\site;
use db, session, pagination, url, model;

class Announcement
{
	# add an announcement
	public function addAnnouncement($siteID,$data)
	{
		$status = session::get('userLevel') == 99?1:0;

		if(strpos($data['announcementLink'], 'http') === false || strpos($data['announcementLink'], '//') === false){
			$link = "http://".$data['announcementLink'];
		}else{
			$link = $data['announcementLink'];
		}

		$data	= Array(
				"siteID"=>$siteID,
				"announcementStatus"=>$status,
				"announcementText"=>$data['announcementText'],
				"announcementLink"=>$link,
				"announcementExpiredDate"=>$data['announcementExpiredDate'],
				"announcementCreatedDate"=>now(),
				"announcementCreatedUser"=>session::get("userID")
						);
		
		db::insert("announcement",$data);

		if( session::get('userLevel') == 2)	{
			$req  = new request();
			$req->create('announcement.add', $siteID, db::getLastID('announcement', 'announcementID'), Array());
		}
	}

	# return an array of announcement list
	public function getAnnouncementList($siteID = 0,$frontend = false,$page = 1)
	{
		db::from("announcement");

		# get by siteID.
		if($siteID === 0)
		{
			db::where("siteID",0);

			if($frontend == false){
				## paginate based on current query built.
				pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
				pagination::initiate(Array(
								"totalRow"=>db::num_rows(), 
								"limit"=>6,				
								"urlFormat"=>url::base("site/announcement/{page}"),
								"currentPage"=>$page
										));

				## limit, and offset.
				db::limit(pagination::get("limit"),pagination::recordNo()-1); 
			}else{
				db::where("announcementStatus = '1'");
				db::where("date(announcementExpiredDate) >",date('Y-m-d', strtotime(now(). ' - 1 days')));
			}
		}
		else
		{
			if($frontend == false){
				db::where("siteID = '$siteID'");
				## paginate based on current query built.
				pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
				pagination::initiate(Array(
								"totalRow"=>db::num_rows(), 
								"limit"=>6,				
								"urlFormat"=>url::base("site/announcement/{page}"),
								"currentPage"=>$page
										));

				## limit, and offset.
				db::limit(pagination::get("limit"),pagination::recordNo()-1); 
			}else{
				db::where("announcementStatus = '1' AND siteID = '$siteID' OR siteID = '0'");
				db::where("date(announcementExpiredDate) >",date('Y-m-d', strtotime(now(). ' - 1 days')));
			}
		}

		db::order_by("siteID DESC, announcementID DESC");

		return db::get()->result("announcementID");
	}

	# return only an announcement
	public function getAnnouncement($announceID)
	{
		db::from("announcement");
		db::where("announcementID",$announceID);

		return db::get()->row();
	}

	#updating announcement
	public function updateAnnouncement($announceID,$data)
	{
		if(session::get('userLevel') == 99){
			db::where("announcementID",$announceID);
			db::update("announcement",$data);
		}else{
			$data['announcementUpdatedDate'] = now();
			$data['announcementUpdatedUser'] = session::get('userID');
			$req  = new request();

			## only if no add request pending.
			if(!$req->checkRequest("announcement.add",$data['siteID'],$announceID))
			{
				$req->create('announcement.update', $data['siteID'], $announceID, $data);
			}
			## if exists, just update announcement tabel.
			else
			{
				db::where("announcementID",$announceID)->update("announcement",$data);
			}
		}
	}
}


?>