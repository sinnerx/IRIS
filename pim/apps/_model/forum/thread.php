<?php
namespace model\forum;
use db, session;

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
				"forumThreadCreatedUser"=>session::get("userID")
						);

		db::insert("forum_thread",$data);
		$threadID	= db::getLastID("forum_thread","forumThreadID");

		## add post.
		$this->addPost($threadID,$post);

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

		db::insert("forum_thread_post",$data);
	}

	public function getThreads($siteID,$categoryID,$selects,$paginationConf = null)
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
		db::where("siteID",$siteID);
		db::order_by("forumThreadID","DESC");
		db::get("forum_thread");

		## return result grouped by categoryID if the passed categoryID is array.
		return is_array($categoryID)?db::result("forumCategoryID",true):db::result("forumThreadID");
	}

	public function getLatestThreads($siteID)
	{
		db::where("siteID",$siteID);
		db::order_by("forumThreadID","DESC");

		db::limit(10);
		return db::get("forum_thread")->result();
	}

	public function getThread($siteID,$categoryID,$threadID)
	{
		return db::where("siteID",$siteID)->where("forumCategoryID",$categoryID)->where("forumThreadID",$threadID)->get("forum_thread")->row();
	}

	public function getPosts($threadID)
	{
		db::where("forumThreadID",$threadID);
		return db::get("forum_thread_post")->result();
	}

	## grouped by forumThreadID
	public function getPostsByThreads($threadIDs)
	{
		db::where("forumThreadID",$threadIDs);
		return db::get("forum_thread_post")->result("forumThreadID",true);
	}
}


?>