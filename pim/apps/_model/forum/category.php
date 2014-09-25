<?php
namespace model\forum;
use db, session, model;
/*
thread_category:
	forumCategoryID [int]
	siteID
	forumCategoryParentID [int]
	forumCategoryTitle [varchar]
	forumCategoryDescription [text]
	forumCategoryCreatedUser [int]
	forumCategoryCreatedDate [datetime]*/
class Category
{
	public function create($siteID,$title,$description = null,$parentID = 0)
	{
		## slug-and-slug.
		$originalSlug	= model::load("helper")->slugify($title);
		$slug			= $this->createSlug($siteID,$originalSlug);

		$data	= Array(
				"siteID"=>$siteID,
				"forumCategoryParentID"=>$parentID,
				"forumCategorySlug"=>$slug,
				"forumCategoryOriginalSlug"=>$originalSlug,
				"forumCategoryTitle"=>$title,
				"forumCategoryDescription"=>$description,
				"forumCategoryApprovalStatus"=>0,
				"forumCategoryCreatedUser"=>session::get("userID"),
				"forumCategoryCreatedDate"=>now()
						);


		db::insert("forum_category",$data);
		$forumCategoryID	= db::getLastID("forum_category","forumCategoryID");
		model::load("site/request")->create("forum_category.add",$siteID,$forumCategoryID,Array());

		return $forumCategoryID;
	}

	public function getCategory($siteID,$where = null)
	{
		if($where)
		{
			foreach($where as $key=>$val)
			{
				db::where($key,$val);
			}
		}
		return db::where('siteID',$siteID)->get("forum_category")->result("forumCategoryID");
	}

	public function getCategoryBySlug($siteID,$slug)
	{
		db::where("siteID",$siteID);
		db::where("forumCategorySlug",$slug);

		return db::get("forum_category")->row();;
	}

	public function checkTitle($siteID,$title)
	{
		db::where("siteID",Array($siteID,0));
		db::where("forumCategoryTitle",$title);

		return db::get("forum_category")->result();
	}

	public function createSlug($siteID,$originalSlug)
	{
		## find slug based on originalSlug of that site.
		db::where("siteID",Array($siteID,0));
		db::where("forumCategoryOriginalSlug",$originalSlug)->get("forum_category");

		$result	= db::result();

		return $result?$originalSlug."-".(count($result)+1):$originalSlug;
	}
}


?>