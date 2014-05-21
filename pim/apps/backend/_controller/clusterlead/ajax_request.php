<?php
class Controller_Ajax_Request
{
	var $template	= false;
	public function lists($siteID)
	{
		$data['row']			= model::load("site/site")->getSite($siteID);
		$data['res_requests']	= model::load("site/request")->getRequestBySite($siteID);
		$data['typeR']			= model::load("site/request")->type();

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

		view::render("clusterlead/request/ajax/detail",$data);
	}
}
?>