<?php

class Controller_Ajax_Page
{
	var $template	= false;
	## return json of row
	public function pageDetail($pageID)
	{
		## return row of page with default.
		$row_page	= model::load("page/page")->getPageByID($pageID,true);

		## return a prepared view.
		$type		= $row_page['pageType'];

		$writer		= model::load("user/user")->get($row_page['pageCreatedUser'],"userProfileFullName");
		$writer		= $row_page['userFullName']?$row_page['userFullName']:"Null";

		## prepare result.
		$row['pageID']			= $row_page['pageID'];
		$row['pageTitle']		= $type == 1?$row_page['pageDefaultName']:$row_page['pageName'];
		$date					= !$row_page['pageUpdatedDate']?$row_page['pageCreatedDate']:$row_page['pageUpdatedDate'];
		$updated				= !$row_page['pageUpdatedDate']?"Written":"Last updated";
		$row['pageLabel']		= "$updated at , ".date("d-F-Y g:i A",strtotime($date));
		$row['pageContent']		= $row_page['pageText'];
		$row['pageTextExcerpt']	= $row_page['pageTextExcerpt'];

		## get page photo.
		$row['pageImageUrl']	= model::load("page/page")->getPagePhotoUrl($pageID);
		$row['pageImageUrl']	= !$row['pageImageUrl']?null:url::asset("frontend/images/photo/".$row['pageImageUrl']);
		
		## get unapproved flag content 
		$unapprovedData	= model::load("site/request")->getUnapprovedRequestData('page.update',$pageID);

		$row['pageStatus']		= "";
		if($unapprovedData)
		{
			$row['pageContent']		= $unapprovedData['pageText'];
			$row['pageStatus']		= "<span style='color:#de7c7c;'>Content waiting for approval</span>";
			$row['pageTextExcerpt'] = $unapprovedData['pageTextExcerpt'];
		}

		$row['pageContent']	= stripslashes($row['pageContent']);

		return response::json($row);
	}

	public function pageEdit($pageID)
	{
		## return row of page with default.
		$row_page	= model::load("page/page")->getPageByID($pageID,true);

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
			$excerpt	= input::get("pageTextExcerpt");

			$data['pageName']	= $pageName;
			$data['pageText']	= $text;
			$data['pageTextExcerpt']	= $excerpt;

			model::load("page/page")->updatePage($pageID,$data);
		}
	}
}


?>