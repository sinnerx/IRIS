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
	public function create($siteID,$title,$access,$description = null,$parentID = 0)
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
				"forumCategoryApprovalStatus"=>$siteID == 0?1:0,
				"forumCategoryAccess"=>$access,
				"forumCategoryCreatedUser"=>session::get("userID"),
				"forumCategoryCreatedDate"=>now()
						);


		db::insert("forum_category",$data);
		$forumCategoryID	= db::getLastID("forum_category","forumCategoryID");

		## if 0 was passed as siteID, it was root creating the category then.
		if($siteID != 0)
		{
			model::load("site/request")->create("forum_category.add",$siteID,$forumCategoryID,Array());
		}

		return $forumCategoryID;
	}

	public function getCategories($siteID,$where = null)
	{
		if($where)
		{
			foreach($where as $key=>$val)
			{
				db::where($key,$val);
			}
		}
		db::where("siteID",0);
		return db::or_where('siteID',$siteID)->get("forum_category")->result("forumCategoryID");
	}

	public function getCategory($categoryID)
	{
		db::where("forumCategoryID",$categoryID);
		return db::get("forum_category")->row();
	}

	public function getCategoryBySlug($siteID,$slug)
	{
		db::where("siteID",$siteID);
		db::where("forumCategorySlug",$slug);
		db::where("forumCategoryApprovalStatus",1);

		return db::get("forum_category")->row();;
	}

	public function checkTitle($siteID,$title,$categoryID = null)
	{
		if($categoryID)
			db::where("forumCategoryID !=",$categoryID);

		if($siteID != 0)
		{
			db::where("siteID",Array($siteID,0));
		}

		db::where("forumCategoryTitle",$title);

		return db::get("forum_category")->result();
	}

	public function updateCategory($categoryID,$categoryTitle,$access,$categoryDescription)
	{
		$data	= Array(
				"forumCategoryTitle"=>$categoryTitle,
				"forumCategoryDescription"=>$categoryDescription,
				"forumCategoryAccess"=>$access
						);

		## if currently have pending add request for this category OR is root : do direct update.
		if(model::load("site/request")->checkRequest("forum_category.add",authData("site.siteID"),$categoryID) || session::get("userLevel") == 99)
		{
			## slug-and-slug.
			$originalSlug	= model::load("helper")->slugify($categoryTitle);
			$slug			= $this->createSlug($siteID,$originalSlug,$categoryID);

			$data['forumCategorySlug']			= $slug;
			$data['forumCategoryOriginalSlug']	= $originalSlug;

			db::where("forumCategoryID",$categoryID)->update("forum_category",$data);
		}
		else
		{
			## create forum_category.update request.
			model::load("site/request")->create("forum_category.update",authData("site.siteID"),$categoryID,$data);
		}

	}

	public function createSlug($siteID,$originalSlug,$categoryID = null)
	{
		## find slug based on originalSlug of that site.
		db::where("siteID",Array($siteID,0));
		db::where("forumCategoryOriginalSlug",$originalSlug);

		if($categoryID)
		{
			db::where("forumCategoryID !=",$categoryID);
		}

		$result	= db::get("forum_category")->result();

		return $result?$originalSlug."-".(count($result)+1):$originalSlug;
	}

	public function accessLevel($no = null)
	{
		$accessR	= Array(
								1=>"Member of any site",
								2=>"Only for this site"
										);

		return $no?$accessR[$no]:$accessR;
	}
}


?>