<?php

class Controller_Blog
{
	public function FbExampleUse()
	{
		$facebook = model::load("socialmedia/facebook/facebook")->setRedirectUrl(url::base("blog/FbExampleUse"));

		if(!$facebook->initiate(array('user_birthday')))
			redirect::to($facebook->getLoginUrl());

		$me = $facebook->request('GET', '/me');
	}
}