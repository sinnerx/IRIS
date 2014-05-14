<?php
## ajax controller 
class Controller_Ajax_Message
{
	var $template	= false;
	public function __construct()
	{
		$this->siteID	= model::load("site/site")->getSiteByManager(session::get("userID"),"siteID");
	}

	public function lists($page = 1)
	{
		$urlFormat		= url::base("ajax/message/lists/{page}");
	
		## format the pagination with cssbootstrap model;
		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
		pagination::setFormat("limit",5); ## overwrite with 5 only.

		$data['siteMessage']	= model::load("site/message");
		$data['res_message']	= model::load("site/message")->getPaginatedMessage($this->siteID,$page,$urlFormat);

		view::render("sitemanager/message/ajax/lists",$data);
	}
}


?>