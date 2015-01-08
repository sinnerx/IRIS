<?php

class Controller_Blog
{
	public function test()
	{
		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('blog/test'));

		if(!$facebook->initiate(array('manage_pages')))
			redirect::to($facebook->getLoginUrl());

		$me = $facebook->request('GET', '/me');
	}

	public function getPageId()
	{
		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('blog/getPageId'));

		if(!$facebook->initiate(array('manage_pages', 'publish_actions')))
			redirect::to($facebook->getLoginUrl());

		$pages = $facebook->getPages();
	}

	public function tryPost()
	{
		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base('blog/tryPost'));

		if(!$facebook->initiate(array('manage_pages')))
			redirect::to($facebook->getLoginUrl());

		echo "<pre>";
		print_r($facebook->request("GET", "/me/friendlists"));

	}
}