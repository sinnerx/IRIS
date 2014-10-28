<?php
namespace model\forum;
use db, session, model, url, pagination;

/*
forum_thread:
	forumThreadID [int]
	siteID [int]
	forumCategoryID [int]
	forumThreadTitle [varchar]
	forumThreadDescription [text]
	forumThreadCreatedDate [datetime]
	forumThreadCreatedUser [int]

forum_thread_post:
	forumThreadPostID [int]
	forumThreadID [int]
	forumThreadPostTitle [varchar]
	forumThreadPostBody [text]
	forumThreadPostCreatedUser [int]
	forumThreadPostCreatedDate [datetime]

forum_thread_tag:
	forumThreadTagID [int]
	forumThreadID [int]
	forumThreadTagName [varchar]
	forumThreadTagCreatedDate [datetime]
	forumThreadTagCreatedUser [int]

*/
class Thread
{
	public function createNew($siteID,$categoryID,$title,$post)
	{
		$data	= Array(
				"siteID"=>$siteID,
				"forumCategoryID"=>$categoryID,
				"forumThreadTitle"=>$title,
				"forumThreadCreatedDate"=>now(),
				"forumThreadCreatedUser"=>session::get("userID"),
				"forumThreadStatus"=>1
						);

		db::insert("forum_thread",$data);
		$threadID	= db::getLastID("forum_thread","forumThreadID");

		## add post.
		$this->addPost($threadID,$post);

		## create user activity.
		model::load("user/activity")->create($siteID,session::get("userID"),"forum.newthread",Array("forumThreadID"=>$threadID));

		return $threadID;
	}

	public function addPost($threadID,$body,$title = null)
	{
		$data = Array(
				"forumThreadID"=>$threadID,
				"forumThreadPostTitle"=>$title,
				"forumThreadPostBody"=>$body,
				"forumThreadPostCreatedUser"=>session::get("userID"),
				"forumThreadPostCreatedDate"=>now()
					);

		## create user activity (only if there already exist anothers post to signify it's not a first post.)
		if(db::where("forumThreadID",$threadID)->get("forum_thread_post")->row())
		{
			model::load("user/activity")->create($siteID,session::get("userID"),"forum.newpost",Array("forumThreadPostID"=>db::getLastID("forum_thread_post","forumThreadPostID")));
		}

		db::insert("forum_thread_post",$data);
	}

	public function getThreads($siteID = null,$categoryID,$selects,$paginationConf = null)
	{
		db::select($selects);
		if(is_array($categoryID) || is_numeric($categoryID))
		{
			db::where("forumCategoryID",$categoryID);
		}
		## is slug.
		else
		{
			db::where("forumCategoryID IN (SELECT forumCategoryID FROM forum_category WHERE forumCategorySlug = ?)",Array($categoryID));
		}

		if($siteID)
			db::where("siteID",$siteID);

		db::where("forumThreadStatus",1);

		db::order_by("forumThreadID","DESC");
		db::get("forum_thread");

		## return result grouped by categoryID if the passed categoryID is array.
		return is_array($categoryID)?db::result("forumCategoryID",true):db::result("forumThreadID");
	}

	public function getThreadsByCategory($categoryID,$paginationConf)
	{
		db::where("forumCategoryID",$categoryID);
		db::from("forum_thread");

		if($paginationConf)
		{
			pagination::initiate(Array(
				"currentPage"=>$paginationConf['currentPage'],
				"urlFormat"=>$paginationConf['urlFormat'],
				"totalRow"=>db::num_rows(),
				"limit"=>$paginationConf['limit'],
				));

			db::limit($paginationConf['limit'],pagination::recordNo()-1);
		}

		db::get();
		return is_array($categoryID)?db::result("forumCategoryID",true):db::result("forumThreadID");
	}

	public function getLatestThreads($siteID,$status = 1)
	{
		db::where("siteID",$siteID);
		if($status)
			db::where("forumThreadStatus",$status);

		db::order_by("forumThreadID","DESC");

		db::limit(10);
		return db::get("forum_thread")->result("forumThreadID");
	}

	public function getThread($siteID = null,$categoryID = null,$threadID)
	{
		if($siteID) db::where("forum_thread.siteID",$siteID);
		if($categoryID) db::where("forum_thread.forumCategoryID",$categoryID);

		db::join("forum_category","forum_category.forumCategoryID = forum_thread.forumCategoryID");

		return db::where("forumThreadID",$threadID)->get("forum_thread")->row();
	}

	public function getPosts($threadID,$paginationConf = null)
	{
		db::where("forumThreadID",$threadID);
		db::from("forum_thread_post");

		if($paginationConf)
		{
			if(db::num_rows() == 1)
			{
				$res = db::get()->result();
				return Array();
			}

			$totalRow	= db::num_rows()-1;

			pagination::setFormat(model::load("template/frontend")->pagination());
			pagination::initiate(Array(
				"currentPage"=>$paginationConf['currentPage'],
				"urlFormat"=>$paginationConf['urlFormat'],
				"totalRow"=>$totalRow,
				"limit"=>$paginationConf['limit'],
				));

			$offset	= pagination::recordNo();

			db::limit($paginationConf['limit'],$offset);
		}

		return db::get()->result();
	}

	## grouped by forumThreadID
	public function getPostsByThreads($threadIDs)
	{
		db::where("forumThreadID",$threadIDs);
		return db::get("forum_thread_post")->result("forumThreadID",true);
	}

	public function createThreadLink($threadID,$forumCategorySlug = null,$siteSlug = null)
	{
		if(is_numeric($threadID))
		{
			db::where("forumThreadID",$threadID);
			db::join("forum_category","forum_category.forumCategoryID = forum_thread.forumCategoryID");
			db::join("site","site.siteID = forum_thread.siteID");
			$row	= db::get("forum_thread")->row();

			$forumCategorySlug	= $row['forumCategorySlug'];
			$siteSlug			= $row['siteSlug'];
		}

		return url::createByRoute("forum-thread",Array(
			"site-slug"=>$siteSlug,
			"category-slug"=>$forumCategorySlug,
			"thread-id"=>$threadID
			),true);
	}

	public function getFirstPost($threadID)
	{
		return db::where("forumThreadID",$threadID)->get("forum_thread_post")->row();
	}

	public function changeCategory($threadID,$categoryID)
	{
		db::where("forumThreadID",$threadID);
		db::update("forum_thread",Array("forumCategoryID"=>$categoryID));
	}
}


?>