<?php

class Controller_Ajax_Forum
{
	public function __construct()
	{
		$this->template	= false;	
	}
	
	public function getLatestTopic()
	{
		$data['res_threads']	= model::load("forum/thread")->getLatestThreads(authData("current_site.siteID"));

		if($data['res_threads'])
		{
			$data['res_posts']		= model::load("forum/thread")->getPostsByThreads(array_keys($data['res_threads']));
			
			$userIDR	= Array();
			foreach($data['res_threads'] as $row_thread)
			{
				$userIDR[]	= $row_thread['forumThreadCreatedUser'];
				$catIDR[]	= $row_thread['forumCategoryID'];
			}

			$data['res_users']	= model::load("user/user")->getUsersByID($userIDR);
			
			## categories.
			$data['res_categories']	= model::load("forum/category")->getCategories(authData("current_site.siteID"),Array("forumCategoryID"=>$catIDR));
		}

		view::render("forum/ajax/getLatestThreads",$data);
	}

	public function getCategories()
	{
		$where['forumCategoryApprovalStatus'] = 1;

		if(!authData('current_site.isMember'))
		{
			$where['forumCategoryAccess !=']  = 2;
		}

		$data['res_forum_category']	= model::load("forum/category")->getCategories(Array(authData("current_site.siteID"),0),$where);

		if($data['res_forum_category'])
		{
			$categoryIDs	= array_keys($data['res_forum_category']);
			$data['res_forum_thread']	= model::load("forum/thread")->getThreads(authData("current_site.siteID"),$categoryIDs,"forumCategoryID,forumThreadTitle,forumThreadID,forumThreadCreatedDate");
		}

		view::render("forum/ajax/getCategories",$data);
	}
}


?>