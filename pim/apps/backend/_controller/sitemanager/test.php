<?php
class Controller_Test
{
	public function test()
	{
		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('test/test'));

		if(!$facebook->initiate(array('manage_pages', 'publish_actions')))
			redirect::to($facebook->getLoginUrl());

		$page = $facebook->getPage('662477777159457');

		$response = $page->request('GET',null, array('id'))->getGraphObject()->asArray();

		echo "<pre>";
		print_r($response);
	}

	public function test2()
	{
		$siteSlug = authData('site.siteSlug');
		$articleSlug = "contoh-artikel";
		$date = date('Y-m-d');
		
		$base_url = 'http://'.apps::config('base_url:frontend')."/".$siteSlug;
		$url = model::load("helper")->buildDateBasedUrl($articleSlug, $date, $base_url);

		echo $url;
	}


	public function mailchimp()
	{
		$newsletter = model::load("newsletter/newsletter");

		if(!$newsletter->hasListID())
		{
		}
	}
}


?>