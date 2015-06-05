<?php
namespace model\site;
use db, session, pagination, model;
class Request extends \Origami
{
	protected $table = 'site_request';
	protected $primary = 'siteRequestID';

	/**
	 * ORM : Delete this site_request.
	 */
	public function delete()
	{
		$this->siteRequestStatus = 99;
		$this->save();
	}

	public function checkRequest($type,$siteID,$refID)
	{
		$row	= db::from("site_request")->where(Array("siteRequestRefID"=>$refID,"siteID"=>$siteID,"siteRequestType"=>$type,"siteRequestStatus"=>0))->get()->row();

		if($row)
		{
			## if correctionFlag = true, 
			if($row['siteRequestCorrectionFlag'] == 1)
			{
				$this->deactivateCorrection($row['siteRequestID']);
			}
			return $row;
		}

		return false;
	}

	//used by model site:updateSiteInfo, page:updatePage
	public function create($type,$siteID,$refID,$siteRequestData)
	{
		## sanitize first.
		foreach($siteRequestData as $key=>$val)
		{
			# $newSiteRequestData[$key]	= addslashes($val);
		}

		## then serialize.
		$newSiteRequestData	= serialize($siteRequestData);

		## Temporary function. in-case got numeric type haven't changed this will do the conversion.
		$type	= $this->numericTypeConverter($type);

		$data	= Array(
				"siteID"=>$siteID,
				"siteRequestType"=>$type,
				"siteRequestRefID"=>$refID,
				"siteRequestData"=>$newSiteRequestData,
				"siteRequestStatus"=>0,
				"siteRequestCreatedDate"=>now(),
				"siteRequestCreatedUser"=>session::get("userID"),
				"siteRequestApprovalRead"=>0
						);

		## check if still got same unapproved request, for the selected refID and type.;
		$check	= $this->checkRequest($type,$siteID,$refID);
		if($check)
		{
			$requestID	= $check['siteRequestID'];
			## just update createdDate, and Data.
			db::where("siteRequestID",$requestID);
			db::update("site_request",Array(
								"siteRequestData"=>$newSiteRequestData,
								"siteRequestCreatedDate"=>now(),
								"siteRequestCreatedUser"=>session::get("userID"),
								"siteRequestApprovalRead"=>0,
								"siteRequestCorrectionFlag"=>0 ## set correction to 0, everytime request updated.
											));

			## check if got correction.
			if($this->getCorrection($requestID))
			{
				$this->deactivateCorrection($requestID);
			}

			$id	= $check['siteRequestID'];
		}
		else
		{
			## create a new request;
			db::insert("site_request",$data);

			$id	= db::getLastID("site_request","siteRequestID");
		}

		## if this request is being made for pengurus site, terus approve request.
		if($siteID == model::load("config")->get("configManagerSiteID"))
		{
			## approve.
			$this->approve($id);
		}
	}


	## use in manager/edit, and page/edit.
	public function getUnapprovedRequestData($param1,$param2 = null)
	{
		db::from("site_request");

		## if got both param.
		if($param2)
		{
			$param1	= $this->numericTypeConverter($param1);

			db::where(Array(
					"siteRequestStatus"=>0,
					"siteRequestType"=>$param1
							));

			db::where("siteRequestRefID",$param2);
		}
		## else, param1 is a siteRequestID
		else
		{
			db::where(Array("siteRequestID"=>$param1));
		}

		db::get();

		## if the refID is a list of refID.
		if(is_array($param2))
		{
			$res	= db::result();
			$data	= Array();
			
			if($res)
			{
				foreach($res as $row)
				{
					$data[$row['siteRequestRefID']]	= unserialize($row['siteRequestData']);
				}

				return $data;
			}
			else
			{
				return $data;
			}
		}
		## else only one refID.
		else
		{
			$data	= db::row();

			if(!$data)
			{
				return false;
			}

			$data	= unserialize($data['siteRequestData']);

			## and strip slashes.
			foreach($data as $key=>$val)
			{
				# $strippedData[$key]	= stripslashes($val); No more strip.
				$finalData[$key]	= $val;
			}

			return $finalData;
			# return $strippedData; No more strip.
		}
	}

	public function replaceWithRequestData($type,$refID,&$originalData = null)
	{
		$requestData	= $this->getUnapprovedRequestData($type,$refID);

		if($requestData)
		{
			if(is_array($refID))
			{
				return $requestData;
			}
			else
			{
				$originalData	= $this->replaceData($originalData,$requestData);
				return true;
			}
		}

		return false;
	}

	## just replace whatever column got in new data.
	public function replaceData($originalData,$newData)
	{
		$replacedData	= Array();
		foreach($originalData as $key=>$value)
		{
			if(isset($newData[$key]))
			{
				$originalData[$key]	= $newData[$key];
			}
		}

		return $originalData;
	}

	## used by cluster/overview
	public function getRequestByCluster($clusterID)
	{
		db::from("site_request")
		->where("siteRequestStatus",0)
		->where("siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = '$clusterID')");

		return db::get();
	}

	## return converted type
	private function numericTypeConverter($num)
	{
		$map	= Array(
				1=>"page.add",
				2=>"page.update",
				3=>"site.update",
				4=>"announcement.add",
				5=>"announcement.update"
						);

		return is_numeric($num)?$map[$num]:$num;
	}

	## list of type.
	public function type($id = null)
	{
		$typeR	= Array(
				1=>"New Page",
				2=>"Page Updates",
				3=>"Site Information Updates",
				4=>"New site announcement",
				5=>"Edit announcement",

						);

		$typeR	= Array(
				"page.add"=>"New page",
				"page.update"=>"Page updates",
				"site.update"=>"Site information update",
				"announcement.add"=>"New site announcement",
				"announcement.update"=>"Announcement Update",
				"article.add"=>"New Article",
				"article.update"=>"Article Update",
				"activity.add"=>"New Activity",
				"activity.update"=>"Activity Update",
				"video.add"=>"New Video",
				"video.update"=>"Update Video",
				"forum_category.add"=>"New Forum Category",
				"forum_category.update"=>"Forum Category Update"
						);

		return !$id?$typeR:$typeR[$id];
	}

	public function statusName($status = null)
	{
		$statusR = Array(
				0=>"Pending",
				1=>"Approved",
				2=>"Rejected",
				3=>"Require Correction"
						);

		return !$status?$statusR:$statusR[$status];
	}

	## used by clusterlead/ajax_request@lists
	public function getRequestBySite($siteID)
	{
		db::from("site_request");
		db::select("site_request.*,userProfileFullName");
		db::where(Array(
				"siteID"=>$siteID,
				"siteRequestStatus"=>0
						));
		db::join("user_profile","user_profile.userID = site_request.siteRequestCreatedUser");
		db::order_by("siteRequestUpdatedDate","desc");
		db::order_by("siteRequestID","desc");

		return db::get()->result("siteRequestID");
	}

	public function getRequest($requestID,$col = "*")
	{
		## get type first.
		db::from("site_request");
		db::select($col);
		db::where("siteRequestID",$requestID);
		list($type,$method)	= explode(".",db::get()->row("siteRequestType"));

		## select and join based on type. i think this way is clearer.
		db::from("site_request");
		db::select("*");
		db::where("siteRequestID",$requestID);
		switch($type)
		{
			case "site":
				db::join("site_info","site_info.siteID = site_request.siteID"); 			## site_info edit. (3)
			break;
			case "page":
				db::join("page","pageID = siteRequestRefID"); 							# page edit. (2)
			break;
			case "announcement":
				db::join("announcement","siteRequestRefID = announcement.announcementID");## annoucement (4)
			break;
			case "article":
				db::join("article","siteRequestRefID = article.articleID");
			break;
			case "activity":
				db::select("activity.*");
				db::join("activity","activity.activityID = siteRequestRefID");

				## join event or training.
				db::join("event","activityType = 1 AND activity.activityID = event.activityID");
				db::join("training","activityType = 2 AND activity.activityID = training.activityID");
			break;
			case "video":
				db::join("video","video.videoID = siteRequestRefID");
				db::join("video_album","video.videoAlbumID = video_album.videoAlbumID");
			break;
			case "forum_category":
				db::join("forum_category","forum_category.forumCategoryID = siteRequestRefID");
			break;
		}

		return db::get()->row();
	}

	public function approve($id)
	{
		## approve request, and set approvalRead to 0
		db::where("siteRequestID",$id)->update("site_request",Array("siteRequestStatus"=>1,"siteRequestApprovalRead"=>0));

		## and append content.
		$row	= $this->getRequest($id);

		## update requestData with the column.
		$type	= $row['siteRequestType'];
		$data	= unserialize($row['siteRequestData']);

		switch($type)
		{
			case "page.add":

			break;
			case "page.update":
			$pageID	= $row['pageID'];

			db::where("pageID",$pageID)->update("page",$data);
			break;
			case "site.update": # site info
			$siteID	= $row['siteID'];

			## update with data.
			db::where("siteID",$siteID)->update("site_info",$data);
			break;
			case "announcement.add": # new site announcement. 
			db::where("announcementID",$row['announcementID'])->update("announcement",Array("announcementStatus"=>1)); # approved.
			break;
			case "announcement.update": 
			db::where("announcementID",$row['siteRequestRefID'])->update("announcement",$data);
			break;

			# new site article.
			case "article.add":  
			db::where("articleID",$row['articleID'])->update("article",Array("articleStatus"=>1)); # approved.
			break;
			case "article.update":
			db::where("articleID",$row['siteRequestRefID'])->update("article",$data);
			break;
			case "activity.add":
			db::where("activityID",$row['siteRequestRefID'])->update("activity",Array('activityApprovalStatus'=>1));
			break;
			case "activity.update":
			model::load("activity/activity")->_updateActivity($row['activityID'],$row['activityType'],$data);
			break;

			## video
			case "video.add":
			db::where("videoID",$row['siteRequestRefID'])->update("video",Array("videoApprovalStatus"=>1));
			break;
			case "video.update":
			db::where("videoID",$row['siteRequestRefID'])->update("video",$data);
			break;

			## forum
			case "forum_category.add":
			db::where("forumCategoryID",$row['siteRequestRefID'])->update("forum_category",Array("forumCategoryApprovalStatus"=>1));
			break;
			case "forum_category.update":
			db::where("forumCategoryID",$row['siteRequestRefID'])->update("forum_category",$data);
			break;
		}
	}

	public function disapprove($id)
	{
		## disprove request, and set approvalRead to 0
		db::where("siteRequestID",$id)->update("site_request",Array("siteRequestStatus"=>2,"siteRequestApprovalRead"=>0));
		
		$row	= $this->getRequest($id);

		## set any specific action based on type of request.
		switch($row['siteRequestType'])
		{
			case "announcement.add": ## set announcementStatus to 2.
			db::where("announcementID",$row['announcementID'])->update("announcement",Array("announcementStatus"=>2));
			break;
			case "article.add":
			db::where("articleID",$row['articleID'])->update("article",Array("articleStatus"=>2));
			break;
			case "activity.add":
			db::where("activityID",$row['activityID'])->update("activity",Array("activityApprovalStatus"=>2));
			break;
			case "video.add":
			db::where("videoID",$row['videoID'])->update("video",Array("videoApprovalStatus"=>2));
			break;
			case "forum_category.add":
			db::where('forumCategoryID',$row['forumCategoryID'])->update("forum_category",Array("forumCategoryApprovalStatus"=>2));
			break;
		}
	}

	## used by clusterlead/ajax/request
	## return [type,[{key1:[ori,updated]}]];
	public function getComparedChange($requestID)
	{
		$row_req	= $this->getRequest($requestID);

		$type			= $row_req['siteRequestType'];
		$refID			= $row_req['siteRequestRefID'];
		$updatedData	= unserialize($row_req['siteRequestData']);

		$changesR		= Array();
		$row_ori		= Array();
		switch($type)
		{
			case 'page.add': ## new pages.
				$row_page	= db::from("page")->where("pageID",$refID)->get()->row();

				foreach($row_page as $key=>$val)
				{
					$changesR[$key]	= Array("",$val);
				}

				## return earlier for new page.
				return Array($type,$changesR);
			break; 
			case 'page.update': ## edit page
				$row_ori	= db::from("page")->where("pageID",$refID)->get()->row();
				unset($row_ori['pageUpdatedDate']);
				unset($row_ori['pageUpdatedUser']);
			break;
			case 'site.update': ## site_info edit.
				$row_ori	= db::from("site_info")->where("siteID",$refID)->get()->row();
			break;
			case 'announcement.update':
				$row_ori	= db::from("announcement")->where("announcementID",$refID)->get()->row();
				$row_ori['announcementExpiredDate']	= date("Y-m-d",strtotime($row_ori['announcementExpiredDate']));
				unset($row_ori['announcementUpdatedDate']);
				unset($row_ori['announcementUpdatedUser']);
			break;
			case "activity.update":
				$row_ori	= model::load("activity/activity")->getActivity($refID);
				unset($row_ori['activityUpdatedDate'],$row_ori['activityUpdatedUser'],$row_ori['activityDateTimeType']);
			break;
			case "article.update":
				$row_ori	= model::load("blog/article")->getArticle($refID);
				$row_ori['articlePublishedDate']	= date("Y-m-d",strtotime($row_ori['articlePublishedDate']));
				unset($row_ori['articleUpdatedDate'],$row_ori['articleUpdatedUser']);
			break;
			case "video.update":
				$row_ori	= db::where("videoID",$refID)->get("video")->row();
			break;
			case "forum_category.update":
				$row_ori	= db::where("forumCategoryID",$refID)->get("forum_category")->row();
			break;
		}

		foreach($row_ori as $key=>$ori_value)
		{
			if(isset($updatedData[$key]))
			{
				## compare between 2 value.
				if($ori_value != $updatedData[$key])
				{
					$changesR[$key]	= Array($ori_value,$updatedData[$key]);
				}
			}
		}

		return Array($type,$changesR,$requestID,$row_req);
	}

	public function getPaginatedUnreadRequest($siteID,$urlFormat,$currentPage = 1)
	{
		db::from("site_request")
		->where("siteID",$siteID)
		->where('siteRequestApprovalRead',0);

		pagination::initiate(Array(
						"totalRow"=>db::num_rows(),
						"limit"=>5,
						"currentPage"=>$currentPage,
						"urlFormat"=>$urlFormat
									));

		db::order_by(Array("siteRequestID desc","siteRequestUpdatedDate desc"))->limit(5,pagination::recordNo()-1);

		return db::get()->result();
	}

	## used by sitemanager/ajax/request. basically it read request. in the other name, clear.
	public function clearRequest($siteID,$id = null)
	{
		## if only id is selected. else, just clear all.
		if($id)
		{
			db::where("siteRequestID",$id);
		}

		db::where(Array("siteRequestApprovalRead"=>0,"siteID"=>$siteID,"siteRequestStatus !="=>0));
		db::update("site_request",Array("siteRequestApprovalRead"=>1));
	}




	// Related site correction.
	public function deactivateCorrection($requestID)
	{
		## get last data.
		$lastData	= db::select("siteRequestData")->where("siteRequestID",$requestID)->get("site_request")->row("siteRequestData");

		## site_request_correction
		db::where("siteRequestID",$requestID);
		db::where("siteRequestCorrectionStatus",1);
		db::update("site_request_correction",Array(
								"siteRequestCorrectionStatus"=>2,
								"siteRequestCorrectionLastData"=>$lastData,
								"siteRequestCorrectionUpdatedDate"=>now(),
								"siteRequestCorrectionUpdatedUser"=>session::get("userID")
								));// updated.

		## set flag.
		db::where("siteRequestID",$requestID)->update("site_request",Array("siteRequestCorrectionFlag"=>0));
	}

	public function getTotalCorrection($siteRequestID)
	{
		db::select("siteRequestCorrectionID,siteRequestID");
		db::where("siteRequestID",$siteRequestID);
		db::where("siteRequestCorrectionStatus",2); ## corrected.

		if(is_array($siteRequestID))
		{
			## group by siteRequestID
			$res	= db::get("site_request_correction")->result("siteRequestID",true);

			return $res;
		}
		else
		{
			return db::get("site_request_correction")->num_rows();
		}
	}

	public function getCorrection($siteRequestID)
	{
		db::where("siteRequestID",$siteRequestID);
		db::where("siteRequestCorrectionStatus",1);
		db::get("site_request_correction");

		return is_array($siteRequestID)?db::result():db::row();
	}

	public function createCorrection($siteRequestID,$txt)
	{
		## no correction mode yet.
		if($this->getCorrection($siteRequestID))
		{
			return;
		}

		## set correction flag to 1.
		db::where("siteRequestID",$siteRequestID)->update("site_request",Array(
												"siteRequestCorrectionFlag"=>1,
												"siteRequestUpdatedDate"=>now(),
												"siteRequestUpdatedUser"=>session::get("userID")
												));

		## create correction message.
		db::insert("site_request_correction",Array(
										"siteRequestCorrectionMessage"=>$txt,
										"siteRequestID"=>$siteRequestID,
										"siteRequestCorrectionStatus"=>1,
										"siteRequestCorrectionCreatedDate"=>now(),
										"siteRequestCorrectionCreatedUser"=>session::get("userID")
													));
	}
}
?>