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
}