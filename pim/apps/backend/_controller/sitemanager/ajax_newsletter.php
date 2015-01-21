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

		$siteNL->siteNewsletterTemplate = $siteNL->prepareTemplate($template);

		$siteNL->mail(false);

		echo 'Test Mail sent!';die;
	}
}