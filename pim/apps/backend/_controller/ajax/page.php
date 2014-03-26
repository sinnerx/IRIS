<?php

class Controller_Page
{
	## return json of row
	public function pageDetail($pageID)
	{
		## return row of page with default.
		$row_page	= model::load("page")->getPageByID($pageID,true);

		## return a prepared view.
		$type		= $row_page['pageType'];

		$writer		= model::load("user")->get($row_page['pageCreatedUser'],"userProfileFullName");
		$writer		= $row_page['userFullName']?$row_page['userFullName']:"Null";

		## prepare result.
		$row['pageID']			= $row_page['pageID'];
		$row['pageTitle']		= $type == 1?$row_page['pageDefaultName']:$row_page['pageName'];
		$row['pageLabel']		= "Written at , ".date("d-F-Y g:i A",strtotime($row_page['pageCreatedDate']));
		$row['pageContent']		= $row_page['pageText'];
		

		return response::json($row);
	}

	public function pageEdit($pageID)
	{
		## return row of page with default.
		$row_page	= model::load("page")->getPageByID($pageID,true);

		## return a prepared view.
		$type		= $row_page['pageType'];

		$row['pageID']			= $pageID;
		$row['pageTitle']		= form::text("pageName","placeholder='Page title'");
		$row['pageLabel']		= "Last updated at , ".date("d-F-Y g:i A",strtotime($row_page['pageCreatedDate']));
		#$row['pageContent']		= 

		return response::json($row);
	}

	public function pageUpdate($pageID)
	{
		if(form::submitted())
		{
			$pageName	= input::get("pageName");
			$text		= input::get("pageText");

			$data['pageName']	= $pageName;
			$data['pageText']	= $text;

			model::load("page")->updatePage($pageID,$data);
		}
	}
}


?>