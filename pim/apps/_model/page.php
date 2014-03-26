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

	## get page ID.
	public function getPageByID($pageID,$withDefault = false)
	{
		db::from("page");
		db::where("pageID",$pageID);

		if($withDefault)
		{
			db::join("page_default","page.pageDefaultType = page_default.pageDefaultType");
		}

		return db::get()->row();
	}

	public function getPageBySlug($slug,$item)
	{
		db::from("page");
		db::where("pageSlug",$slug);

		if($item)
		{
			return db::get()->row($item);
		}

		return db::get()->row();
	}

	public function getPageByParent($id,$where = null,$col = null)
	{
		db::from("page");
		db::where("pageID IN (SELECT pageID FROM page_reference WHERE pageParentID = '$id')");
		if($where)
		{
			db::where($where);
			return db::get()->row($col);
		}

		return db::get()->result();
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


	## update page.
	public function updatePage($pageID,$data)
	{
		## check type.
		$type	= db::where("pageID",$pageID)->get("page")->row('pageType');

		if($type == 1)
		{
			## unset pageName, if type of page to be updated is 1.
			if(isset($data['pageName']))
			{
				unset($data['pageName']);
			}
		}

		$data['pageUpdatedDate']	= now();
		$data['pageUpdatedUser']	= session::get("userID");

		db::where("pageID",$pageID);
		db::update("page",$data);
	}
}



?>