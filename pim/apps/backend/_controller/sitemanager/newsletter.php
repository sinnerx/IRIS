<?php
class Controller_Newsletter
{
	public function Template()
	{
		$siteNL = site()->getSiteNewsletter();

		// echo $siteNL->prepareTemplate();die;

		if(form::submitted())
		{
			$subject = input::get('siteNewsletterSubject');
			$template = input::get('siteNewsletterTemplate');

			// save.
			$siteNL->siteNewsletterSubject = $subject;
			$siteNL->siteNewsletterTemplate = $template;
			$siteNL->siteNewsletterEdited = 1;

			$siteNL->save();

			redirect::to('newsletter/template', 'Successfully updated newsletter template.');
		}

		$data['row'] = $siteNL->toArray();

		view::render('sitemanager/newsletter/template', $data);
	}

	public function subscribers()
	{
		$siteNL = site()->getSiteNewsletter();

		$data['subscribers'] = $siteNL->isConnected() ? $siteNL->getSubscribers() : false;

		view::render("sitemanager/newsletter/subscribers", $data);
	}

	public function syncSubscriber()
	{
		$siteNL = site()->getSiteNewsletter();
		$siteNL->syncSubscribers();

		redirect::to('newsletter/subscribers', 'Site mails synced.');
	}

	public function trySend()
	{
		$ns = model::load("newsletter/newsletter");
		
		$siteNL = site()->getSiteNewsletter();

		if(!$siteNL->isConnected())
			return;

		// will send to rahimie@digitalgaia.com
		$siteNL->mailTest(array('rahimie@digitalgaia.com'));
	}

	public function push()
	{
		$data['siteNL'] = site()->getSiteNewsletter();
		$data['mailpushes'] = $data['siteNL']->getLatestMailpush();
		$data['email'] = authData('user.userEmail');

		view::render('sitemanager/newsletter/push', $data);
	}
}

?>