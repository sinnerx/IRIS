<?php

class Controller_Ajax_Newsletter
{
	public function getPreviewContent()
	{
		$siteNL = site()->getSiteNewsletter();

		$template = input::get('template');

		echo $siteNL->prepareTemplate($template);die;
	}

	/**
	 * This function already disabled. Because everytime we want to test, we'll need to set up a new campaign, which isn't good practice.
	 */
	public function testSend()
	{
		$siteNL = site()->getSiteNewsletter();

		if(!$siteNL->isConnected())
		{
			echo 'Site not yet connected!';die;
		}

		$template = input::get('template');

		// $siteNL->siteNewsletterTemplate = $siteNL->prepareTemplate($template);

		$siteNL->mailTest(array('newrehmi@gmail.com'));

		echo 'Newsletter successfully send this test to your email!';die;
	}

	/**
	 * This function already disabled. Because everytime we want to test, we'll need to set up a new campaign, which isn't good practice.
	 */
	public function mailPush()
	{
		$siteNL = site()->getSiteNewsletter();

		if(!$siteNL->isConnected())
		{
			echo 'Site not yet connected!';die;
		}

		$template = input::get('template');

		// $siteNL->siteNewsletterTemplate = $siteNL->prepareTemplate($template);

		// if has already pushed for today.
		if($siteNL->hasAlreadyPushed())
		{

			echo 'Newsletter has already pushed for today';die;
		}

		// do a single send.
		$siteNL->mail(true);

		echo 'Newsletter successfully blasted.';die;
	}

	public function preview()
	{
		$siteNL = site()->getSiteNewsletter();

		$data['siteNL'] = $siteNL;
		view::render('sitemanager/newsletter/ajax/preview', $data);
	}
}