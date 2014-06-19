<?php
class Controller_Ajax_Request
{
	var $template	= false;
	public function __construct()
	{
		$this->siteID = model::load("site/site")->getSiteByManager(session::get("userID"),"siteID");
	}

	public function lists($page = 1)
	{
		## get siteID by current manager.
		$siteID				= $this->siteID;

		## format pagination with css bootstrap styles..
		$bootstrapCss	= model::load("template/cssbootstrap")->paginationLink();
		pagination::setFormat($bootstrapCss);

		## get paginated request.
		$siteRequest	= model::load("site/request");
		$data['res_request']	= $siteRequest->getPaginatedUnreadRequest($siteID,url::base("ajax/request/lists/{page}"),$page);
		$data['requestTypeNameR']	= $siteRequest->type();
		$data['requestStatusNameR']	= $siteRequest->statusName();

		view::render("sitemanager/request/ajax/lists",$data);
	}

	public function clear($id = null)
	{
		## get siteID by current manager.
		$siteID				= $this->siteID;

		model::load("site/request")->clearRequest($siteID,$id);

		## forward to lists.
		$this->lists();
	}

	public function correctionDetail($siteRequestID)
	{
		$row = model::load("site/request")->getCorrection($siteRequestID);

		$row_request			= model::load("site/request")->getRequest($siteRequestID);
		$data['row']	= $row;
		$data['typeName']	= model::load("site/request")->type($row_request['siteRequestType']);

		## prepare urlToSubject.
		list($typeObject)	= explode(".",$row_request['siteRequestType']);

		switch($typeObject)
		{
			case "activity":
			$data['urlToSubject']	= url::base("activity/edit/".$row_request['siteRequestRefID']);
			break;
			case "site":
			$data['urlToSubject']	= url::base("site/edit");
			break;
			case "article":
			$data['urlToSubject']	= url::base("site/editArticle/".$row_request['siteRequestRefID']);
			break;
		}
	
		$data['row_request']	= $row_request;		
		view::render("sitemanager/request/ajax/correctionDetail",$data);
	}
}