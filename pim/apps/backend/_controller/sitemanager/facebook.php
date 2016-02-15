<?php

class Controller_Facebook
{
	public function checkPageId()
	{
	
		$pageID = authData('site.siteInfoFacebookPageId');
		
		if($pageID == "")  {
			$alert = "This site not link with facebook, please click connect button";
		}
		else 
		{
			$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('facebook/checkPageId'));

			if(!$facebook->initiate(array('manage_pages', 'publish_actions')))
				redirect::to($facebook->getLoginUrl());
		
				$page = $facebook->getPage($pageID);		
				$response = $page->request('GET')->getGraphObject()->asArray();

		}	
		

		$data['alert'] = $alert;
		$data['pageID'] = $pageID;
		$data['pageInfo'] = $response;
	
		view::render("sitemanager/facebook/facebookid",$data);
	}

	public function getPageId()
	{
		$pageId = request::get("pageID");



		if($pageId != "")
		{

			$siteID	= authData('site.siteID');
			
			$siteUpdated	= model::load("site/site")->setFacebookPageId($siteID,$pageId);		
			$pageID = authData('site.siteInfoFacebookPageId');

			redirect::to("facebook/checkPageId");
		}
			
		$pageID = authData('site.siteInfoFacebookPageId');

		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('facebook/getPageId'));

		if(!$facebook->initiate(array('manage_pages', 'publish_actions')))
			redirect::to($facebook->getLoginUrl());

		$data1 = $facebook->getPages();

		$data['pages'] = $data1;
		$data['pageID'] = $pageID;


		view::render("sitemanager/facebook/pageslist",$data);
	}



	public function getArticleInfo()
	{
		$articleID = request::get("articleID");
		$blog = model::load("blog/article");
		$article = $blog->getArticle($articleID);
		$date = $article['articlePublishedDate'];
		$slugName = $article['articleSlug'];
      	$content = $article['articleText'];
      	$type = "blog";

		$this->postToWall($articleID,$date,$slugName,$content,$type);	

		
		redirect::to("site/article","New article post");
		
	}


	public function getActivityInfo()
	{
		$activityID  = request::get("activityID");
		$activityType = request::get("activityType");
		$info = model::load("activity/activity");


		$activity = $info->getActivity($activityID);


		$date = $activity['activityStartDate'];
		$slugName = $activity['activitySlug'];
      	$activityName = $activity['activityName'];
      	$type = "aktiviti";

      	$activityStartDate = $activity['activityStartDate'];
      	$activityEndDate   = $activity['activityEndDate'];

 				
		$helper	= model::load("helper");
		if($activityStartDate == $activityEndDate) {
			
			$startenddate = $helper->frontendDate($activityStartDate);
		} else {
			$startenddate = $helper->frontendDate($activityStartDate)." hingga ".$helper->frontendDate($activityEndDate);	
		}
				 
		$content = $activityName."\n".$startenddate;

		$this->postToWall($activityID,$date,$slugName,$content,$type);	
		
		redirect::to("activity/".$activityType,"New article post");
		
	}

	public function postToWall($postID,$date,$slugName,$content,$type)
	{
		$pageID = authData('site.siteInfoFacebookPageId');

		$domain = apps::config('base_url:frontend');
		if ($domain == "localhost/digitalgaia/iris"){	
			$domain = "dev.celcom1cbc.com";	}

		$url1 = "http://".$domain."/".authData('site.siteSlug').'/'.$type.'/';
		$url =	model::load('helper')->buildDateBasedUrl($slugName,$date,$url1);




		$content =	model::load('helper')->purifyHTML($content);		
		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('facebook/postToWall'));

		if(!$facebook->initiate(array('manage_pages')))
			redirect::to($facebook->getLoginUrl());


		$data = $facebook->getPage($pageID);		

		$data->postToWall(array ('message' => $content,
									'link' => $url));

		
	}



}