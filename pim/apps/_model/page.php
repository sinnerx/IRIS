<?php

class Model_Page
{
	public function listBySite($siteID)
	{
		db::from("page");
		db::where(Array(
					"siteID"=>$siteID,
					"pageType IN"=>"(1,2)",
						));
		$result	= db::get()->result();

		return $result;
	}

	## required : pageName, pageSlug, pageDefaultType
	## used by model site/createSite
	public function create($siteID,$type,$data_page)
	{
		$data	= Array(
				"siteID"=>$siteID,
				"pageType"=>$type,
				"pageApprovedStatus"=>$data_page['pageApprovedStatus']?$data_page['pageApprovedStatus']:0,
				"pageName"=>$data_page['pageName'],
				"pageSlug"=>$data_page['pageSlug'],
				"pageText"=>$data_page['pageText'],
				"pageType"=>$type,
				"pageDefaultType"=>$data_page['pageDefaultType'],
				"pageCreatedUser"=>session::get("userID"),
				"pageCreatedDate"=>now()
						);

		db::insert("page",$data);
		$pageID	= db::getLastID("page","pageID");

		if($data_page['pageParentID'])
		{
			//create page reference.
			db::insert("page_reference",Array(
									"pageID"=>$pageID,
									"pageParentID"=>$data_page['pageParentID']
												));
		}

		return db::getLastID("page","pageID");
	}

	public function getDefault($type = null)
	{
		db::select("pageDefaultType,pageDefaultName,pageDefaultSlug");
		db::from("page_default");
		return db::get()->result("pageDefaultType"); ## like Array(1=>$row,2=>$row);
	}

	## used by header.
	public function getPage($pageID)
	{
		$where	= Array(
				"pageID"=>$pageID,
				"pageApprovedStatus"=>1
						);

		db::from("page");
		db::where($where);
		return db::get()->row();
	}

	public function getPageBySlug($slug)
	{
		db::from("page");
		db::where("pageSlug",$slug);

		return db::get()->row();
	}

	## used by header.
	## return list of page under the pageID.
	public function getChildrenPage($pageParentID)
	{
		db::from("page_reference");
		db::where("pageParentID",$pageParentID);
		db::join("page","page.pageID = page_reference.pageID");

		return db::get()->result();
	}
}



?>