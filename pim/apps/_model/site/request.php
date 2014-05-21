<?php
namespace model\site;
use db, session, pagination;
class Request
{
	//used by model site:updateSiteInfo, page:updatePage
	public function create($type,$siteID,$refID,$siteRequestData)
	{
		## sanitize first.
		foreach($siteRequestData as $key=>$val)
		{
			$newSiteRequestData[$key]	= addslashes($val);
		}

		## then serialize.
		$newSiteRequestData	= serialize($siteRequestData);

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
		$check	= db::from("site_request")->where(Array("siteRequestRefID"=>$refID,"siteID"=>$siteID,"siteRequestType"=>$type,"siteRequestStatus"=>0))->get()->row();
		if($check)
		{
			$requestID	= $check['siteRequestID'];
			## just update createdDate, and Data.
			db::where("siteRequestID",$requestID);
			db::update("site_request",Array(
								"siteRequestData"=>$newSiteRequestData,
								"siteRequestCreatedDate"=>now(),
								"siteRequestCreatedUser"=>session::get("userID"),
								"siteRequestApprovalRead"=>0
											));
		}
		else
		{
			## create a new request;
			db::insert("site_request",$data);
		}
	}

	## use in manager/edit, and page/edit.
	public function getUnapprovedRequestData($param1,$param2 = null)
	{
		db::from("site_request");

		## if got both param.
		if($param2)
		{
			db::where(Array(
					"siteRequestStatus"=>0,
					"siteRequestType"=>$param1,
					"siteRequestRefID"=>$param2
							));
		}
		## else, param1 is a siteRequestID
		else
		{
			db::where(Array("siteRequestID"=>$param1));
		}

		$data	= db::get()->row("siteRequestData");
		if(!$data)
		{
			return false;
		}

		$data	= unserialize($data);

		## and strip slashes.
		foreach($data as $key=>$val)
		{
			$strippedData[$key]	= stripslashes($val);
		}

		return $strippedData;
	}

	## used by cluster/overview
	public function getRequestByCluster($clusterID)
	{
		db::from("site_request")
		->where("siteRequestStatus",0)
		->where("siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = '$clusterID')");

		return db::get();
	}

	public function type($id = null)
	{
		$typeR	= Array(
				1=>"New Page",
				2=>"Page Updates",
				3=>"Site Information Updates",
				4=>"New site announcement",
				5=>"Edit announcement"
						);

		return !$id?$typeR:$typeR[$id];
	}

	public function statusName($status = null)
	{
		$statusR = Array(
				0=>"Pending",
				1=>"Approved",
				2=>"Rejected"
						);

		return !$status?$statusR:$statusR[$status];
	}

	## used by clusterlead/ajax_request@lists
	public function getRequestBySite($siteID)
	{
		db::from("site_request");
		db::where(Array(
				"siteID"=>$siteID,
				"siteRequestStatus"=>0
						));

		return db::get()->result();
	}

	public function getRequest($requestID)
	{
		db::from("site_request");
		db::where("siteRequestID",$requestID);
		db::join("page","siteRequestType IN (1,2) AND pageID = siteRequestRefID"); 							# page edit. (2)
		db::join("site_info","siteRequestType = '3' AND site_info.siteID = site_request.siteID"); 			## site_info edit. (3)
		db::join("announcement","siteRequestType = '4' AND siteRequestRefID = announcement.announcementID");## annoucement (4)

		return db::get()->row();
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
			case 1: # new page

			break;
			case 2: # edit page
			$pageID	= $row['pageID'];

			db::where("pageID",$pageID)->update("page",$data);
			break;
			case 3: # site info
			$siteID	= $row['siteID'];

			## update with data.
			db::where("siteID",$siteID)->update("site_info",$data);
			break;
			case 4: # new site announcement. 
			db::where("announcementID",$row['announcementID'])->update("announcement",Array("announcementStatus"=>1)); # approved.
			break;
			case 5: # edit announcement
			db::where("announcementID",$row['isteRequestRefID'])->update("announcement",$data);
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
			case 4: ## set announcementStatus to 2.
			db::where("announcementID",$row['announcementID'])->update("announcement",Array("announcementStatus"=>2));
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
			case 1: ## new pages.
				$row_page	= db::from("page")->where("pageID",$refID)->get()->row();

				foreach($row_page as $key=>$val)
				{
					$changesR[$key]	= Array("",$val);
				}

				## return earlier for new page.
				return Array($type,$changesR);
			break; 
			case 2: ## edit page
				$row_ori	= db::from("page")->where("pageID",$refID)->get()->row();
				unset($row_ori['pageUpdatedDate']);
				unset($row_ori['pageUpdatedUser']);
			break;
			case 3: ## site_info edit.
				$row_ori	= db::from("site_info")->where("siteID",$refID)->get()->row();
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

		db::order_by("siteRequestID","desc")->limit(5,pagination::recordNo()-1);

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
}
?>