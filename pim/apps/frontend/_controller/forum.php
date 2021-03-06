<?php
class Controller_Forum
{
	public function __construct()
	{
		if(!authData("user"))
			redirect::to("{site-slug}");

		if(request::named("category-slug"))
		{
			$this->row_category	= model::load("forum/category")->getCategoryBySlug(Array(authData("current_site.siteID"),0),request::named("category-slug"));
			if($this->row_category['forumCategoryAccess'] == 2 && !authData("current_site.isMember"))
			{
				redirect::to("{site-slug}/forum");
			}
		}
	}

	public function index()
	{
		$where['forumCategoryApprovalStatus'] = 1;
		$where['forumCategoryAccess !='] = 2;
		$data['res_forum_category']	= model::load("forum/category")->getCategories(Array(authData("current_site.siteID"),0),$where);

		if($data['res_forum_category'])
		{
			$categoryIDs	= array_keys($data['res_forum_category']);
			$data['res_forum_thread']	= model::load("forum/thread")->getThreads(authData("current_site.siteID"),$categoryIDs,"forumCategoryID,forumThreadTitle,forumThreadID,forumThreadCreatedDate");
		}

		view::render("forum/index",$data);
	}

	public function threadList($categorySlug)
	{
		$data['row_category']	= $this->row_category;
		$data['res_threads']	= model::load("forum/thread")->getThreads(authData("current_site.siteID"),$data['row_category']['forumCategoryID'],"*");

		/**
		 * By orm checks.
		 */

		if (authData('user.userLevel') == 1){
			if(!member()->hasAccessTo('forum', array('forumCategoryAccess'=> $this->row_category['forumCategoryAccess'])))
			{
				flash::set('message', 'Anda tiada akses untuk forum ini. Sila buat bayaran keahlian terlebih dahulu.');
				redirect::toRoute('frontend-error');
			}
		}

		if($data['res_threads'])
		{
			$data['res_posts']		= model::load("forum/thread")->getPostsByThreads(array_keys($data['res_threads']));
			
			$userIDR	= Array();
			foreach($data['res_threads'] as $row_thread)
			{
				$userIDR[]	= $row_thread['forumThreadCreatedUser'];
			}

			$data['res_users']	= model::load("user/user")->getUsersByID($userIDR);
			
		}
		view::render("forum/threadList",$data);
	}

	public function newThread($categorySlug = null)
	{
		$data['row_category']	= $categorySlug?$this->row_category:db::where("forumCategoryID",input::get("forumCategoryID"))->get("forum_category")->row();
		$where['forumCategoryApprovalStatus']	= 1;
		if(!authData('current_site.isMember'))
		{
			$where['forumCategoryAccess !='] = 2;
		}
		$res_category	= model::load("forum/category")->getCategories(Array(authData("current_site.siteID"),0),$where);

		## cat.
		$data['withoutCategory']	= !$categorySlug?true:false;

		foreach($res_category as $row)
			$data['categories'][$row['forumCategoryID']] = $row['forumCategoryTitle'];

		if(form::submitted())
		{
			$rules	= Array(
					"forumThreadTitle,forumThreadPostBody"=>"required:Ruangan ini diperlukan."
							);

			if(!$categorySlug)
			{
				$rules['forumCategoryID']	= "required:Ruangan ini diperlukan.";
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("span-error",$error));
				redirect::to("","Sila lengkapkan ruangan yang diperlukan.","error");
			}

			$threadID	= model::load("forum/thread")->createNew(authData("current_site.siteID"),$data['row_category']['forumCategoryID'],input::get("forumThreadTitle"),input::get("forumThreadPostBody"));
			redirect::to("{site-slug}/forum/".$data['row_category']['forumCategorySlug']."/$threadID");
		}

		view::render("forum/newThread",$data);
	}

	public function viewThread($categorySlug,$threadID)
	{
		$data['row_category']	= $this->row_category;

		## get thread.
		$data['row_thread']	= model::load("forum/thread")->getThread(authData("current_site.siteID"),$data['row_category']['forumCategoryID'],$threadID);

		$pgConf['currentPage']	= request::get("page",1);
		$pgConf['urlFormat']	= url::base(url::createByRoute("forum-thread")."?page={page}");
		$pgConf['limit']		= 10;

		$data['res_posts']	= model::load("forum/thread")->getPosts($threadID,$pgConf);

		if(form::submitted())
		{
			$body	= input::get("forumThreadPostBody");

			model::load("forum/thread")->addPost($threadID,$body);

			redirect::to("");
		}

		$data['firstPost']	= model::load("forum/thread")->getFirstPost($threadID);
		
		## get users.
		$userIDR	= Array();
		$userIDR[]	= $data['firstPost']['forumThreadPostCreatedUser'];
		foreach($data['res_posts'] as $row)
		{
			$userIDR[]	= $row['forumThreadPostCreatedUser'];
		}


		$data['res_users']	= model::load("user/user")->getUsersByID($userIDR);

		view::render("forum/viewThread",$data);
	}
}
?>