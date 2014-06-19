<?php
class Controller_Ajax_Request
{
	var $template	= false;
	public function lists($siteID)
	{
		$data['row']			= model::load("site/site")->getSite($siteID);
		$data['res_requests']	= model::load("site/request")->getRequestBySite($siteID);
		$data['typeR']			= model::load("site/request")->type();

		if($data['res_requests'])
		{
			$data['totalCorrection']= model::load("site/request")->getTotalCorrection(array_keys($data['res_requests']));
		}

		view::render("clusterlead/request/ajax/lists",$data);
	}

	## used in ajax/request/lists
	public function approve($requestID)
	{
		model::load("site/request")->approve($requestID);
	}

	## used in ajax/request/lists
	public function disapprove($requestID)
	{
		model::load("site/request")->disapprove($requestID);
	}

	public function detail($requestID)
	{
		## return only changed item. ## [type,[changesR]];
		$data['comparedR']	= model::load("site/request")->getComparedChange($requestID);
		$data['typeName']	= model::load("site/request")->type($data['comparedR'][0]);
		$data['row_request']= $data['comparedR'][3];

		## specific detail joining. 
		switch($data['comparedR'][0])
		{
			case "activity.add":
			## load activitiy date.
			$data['activityDate']	= model::load("activity/activity")->getDate($data['row_request']['activityID']);
			
			break;
			case "activity.update":

			break;
		}

		## total correction
		$data['totalCorrection']	= model::load("site/request")->getTotalCorrection($requestID);

		## sanitize column name for page.
		$data['colNameR']['page']	= Array("pageTitle"=>"Page Name","pageText"=>"Page Content");

		$data['colNameR']['site']	= Array("siteInfoPhone"=>"Phone No.",
											"siteInfoAddress"=>"Address",
											"siteInfoFax"=>"Fax No.",
											"siteInfoTwitterUrl"=>"Twitter Url",
											"siteInfoFacebookUrl"=>"Facebook Url",
											"siteInfoEmail"=>"Email Address",
											"siteInfoImage"=>"Image",
											"siteInfoDescription"=>"Site Description",
											"siteInfoLatitude"=>"Latitude",
											"siteInfoLongitude"=>"Longitude"
											);

		$data['colNameR']['announcement']	= Array("announcementText"=>"Announcement Text",
													"announcementExpiredDate"=>"Validate until",
													"announcementLink"=>"Link"
													);
		$data['colNameR']['activity']	= Array(
											"activityType"=>"Activity Type",
											"activityName"=>"Name",
											"activityAddress"=>"Address",
											"activityDescription"=>"Description",
											"activityParticipation"=>"Participation",
											"activityStartDate"=>"Start Date",
											"activityEndDate"=>"End Date",
											"trainingType"=>"Type of Training",
											"trainingMaxPax"=>"Max pax",
											"eventType"=>"Type of Event",
											"activityAddressFlag"=>"Activity Address : On using site address"
												);

		$data['colNameR']['article']	= Array(
											"articleName"=>"Article Name",
											"articleText"=>"Article Content",
											"articlePublishedDate"=>"Published after"
												);

		## date list column.
		$data['dateTimeListColumn']	= Array(
							"activityStartDate",
							"activityEndDate"
										);

		## param required column.
		$data['paramRequiredColumn']	= Array(
						"activityParticipation"=>function($no)
						{
							return model::load("activity/activity")->participationName($no);
						},
						"trainingType"=>function($no)
						{
							return model::load("activity/training")->type($no);
						},
						"trainingMaxPax"=>function($no)
						{
							return $no == 0?"No-limit":$no;
						},
						"activityAddressFlag"=>function($no) use($data)
						{
							return $no == 1?"Use site address":$data['row_request']['activityAddress'];
						},
								);

		view::render("clusterlead/request/ajax/detail",$data);
	}

	public function rejectionForm($siteRequestID)
	{
		$data['siteRequestID']	= $siteRequestID;
		view::render("clusterlead/request/ajax/rejectionForm",$data);
	}

	public function requestCorrection($requestID)
	{
		model::load("site/request")->createCorrection($requestID,input::get("text"));
	}

	public function correctionDetail($siteRequestID)
	{
		$data				= model::load("site/request")->getCorrection($siteRequestID);
		$data['row_request']	= model::load("site/request")->getRequest($siteRequestID);
		$data['typeName']		= model::load("site/request")->type($data['row_request']['siteRequestType']);

		view::render("clusterlead/request/ajax/correctionDetail",$data);
	}
}
?>