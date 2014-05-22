<?php
namespace model\site;
use db, session;

class Announcement
{
	# add an announcement
	public function addAnnouncement($siteID,$data)
	{
		$status = session::get('userLevel') == 99?1:0;

		$data	= Array(
				"siteID"=>$siteID,
				"announcementStatus"=>$status,
				"announcementText"=>$data['announcementText'],
				"announcementExpiredDate"=>$data['announcementExpiredDate'],
				"announcementCreatedDate"=>now(),
				"announcementCreatedUser"=>session::get("userID")
						);
		
		db::insert("announcement",$data);

		if( session::get('userLevel') == 2)	{
			$req  = new request();
			$req->create(4, $siteID, db::getLastID('announcement', 'announcementID'), Array());
		}
	}

	# return an array of announcementS
	public function getAnnouncement($siteID = 0,$frontend = false)
	{
		db::from("announcement");

		# get by siteID.
		if($siteID === 0)
		{
			db::where("siteID",0);
		}
		else
		{
			if($frontend == false){
				db::where("siteID = '$siteID' OR siteID = '0'");
			}else{
				db::where("announcementStatus = '1' AND siteID = '$siteID' OR siteID = '0'");
				db::where("date(announcementExpiredDate) >",date('Y-m-d', strtotime(now(). ' - 1 days')));
			}
		}

		db::order_by("siteID DESC, announcementID DESC");

		return db::get()->result();
	}

	# return only an announcement
	public function getOneAnnouncement($announceID)
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
			$req->create(5, $data['siteID'], $announceID, $data);
		}
	}
}


?>