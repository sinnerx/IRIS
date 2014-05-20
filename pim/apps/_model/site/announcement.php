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

	public function getAnnouncement($siteID,$all = true)
	{
		db::from("announcement");

		## if only status == 1
		if(!$all)
		{
			db::where("announcementStatus",1);
		}

		# get by siteID.
		if($siteID === 0)
		{
			db::where("siteID",0);
		}
		else
		{
			db::where("siteID = '$siteID' OR siteID = '0'");
		}

		db::order_by("siteID DESC, announcementID DESC");

		return db::get()->result();
	}
}


?>