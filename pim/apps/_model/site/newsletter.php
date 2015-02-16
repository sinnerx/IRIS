<?php
namespace model\site;
use model, session, db;

/**
 * Fully used as ORM.
 */
class Newsletter extends \Origami
{
	protected $table = "site_newsletter";
	protected $primary = "siteNewsletterID";

	// exception attribute.
	protected $exception = array('newsletter');

	public function initiate()
	{
		$this->newsletter = model::load('newsletter/newsletter');
		$this->newsletter->setListID($this->mailChimpListID);
	}

	/**
	 * Unset mailChimpListID
	 */
	public function unlink()
	{
		$this->mailChimpListID = '';
		$this->save();
	}

	/**
	 * Get site.
	 */
	public function getSite()
	{
		return $this->getOne('site/site', 'siteID');
	}

	/**
	 * Get latest \site\newsletter\send
	 */
	public function getLatestMailpush()
	{
		return model::orm('site/newsletter/mailpush')->where('siteID', $this->siteID)->order_by('siteNewsletterMailpushID', 'desc')->execute();
	}

	/**
	 * Check if newsletter has been sent for the date.
	 */
	public function hasSend($date)
	{
		$res = db::where('siteID', $this->siteID)->where("date(siteNewsletterSendDate)", $date)->result()->execute();

		return $res ? true : false;
	}

	protected function prepareNewsletter()
	{
		if($this->siteNewsletterEdited == 1 || $this->mailChimpCampaignID == '')
		{
			if($this->mailChimpCampaignID == '')
			{
				$detail = $this->newsletter->createCampaign('newrehmi@gmail.com', 'remi', $this->siteNewsletterSubject, $this->prepareTemplate());
				$cID = $detail['id'];
			}
			// just update the campaign.
			else
			{
				try
				{
					$campaigns = $this->newsletter->mc->campaigns;

					$detail = $campaigns->update($this->mailChimpCampaignID, 'options', array('subject'=> $this->siteNewsletterSubject, 'title'=> $this->siteNewsletterSubject));
					$detail = $campaigns->update($this->mailChimpCampaignID, 'content', array('html'=> $this->prepareTemplate()));
					
					$cID = $detail['data']['id'];
				}
				catch(\Exception $e)
				{
					$this->mailChimpCampaignID = '';
					$this->save();

					// recursive, and recreate campaign.
					return $this->prepareNewsletter();
				}
			}

			$this->siteNewsletterEdited = 0;

			// update campaignID.
			$this->mailChimpCampaignID = $cID;
			$this->save();
		}
	}

	// has been pushed for the day.
	public function hasAlreadyPushed()
	{
		$res = db::where('siteID', $this->siteID)
		->where('date(siteNewsletterMailpushDate)', date("Y-m-d"))
		->get('site_newsletter_mailpush')->result();

		return $res ? true : false;
	}

	public function mailTest(array $emails)
	{
		if($this->siteNewsletterSubject == '' || $this->siteNewsletterTemplate == '')
			return false;

		$this->prepareNewsletter();

		// send campaign using test parameter.
		$response = $this->newsletter->sendTestCampaign($this->mailChimpCampaignID, $emails);
	}

	public function mail($singleSend = false)
	{
		$newsletter = $this->newsletter;

		// don't do anything if subject or newsletter is empty.
		if($this->siteNewsletterSubject == '' || $this->siteNewsletterTemplate == '')
			return false;

		$this->prepareNewsletter();
		
		if($singleSend === true)
		{
			// create a new site_newsletter_mailpush.
			$mailpush = model::orm('site/newsletter/mailpush')->create();
			$mailpush->siteID = $this->siteID;

			// create the history.
			$mailpush->siteNewsletterMailpushSubject = $this->siteNewsletterSubject;
			$mailpush->siteNewsletterMailpushTemplate = $this->siteNewsletterTemplate;
			$mailpush->siteNewsletterMailpushDate = date('Y-m-d H:i:s');
			$mailpush->siteNewsletterMailpushUser = session::get('userID');
			$mailpush->save();
		}

		// send campaign using test parameter.
		$response = $newsletter->sendCampaign($this->mailChimpCampaignID);
	}

	public function addSubscriber(array $emails)
	{
		foreach($emails as $email)
			$ems[] = array('email'=> array('email'=>$email));

		$response = $this->newsletter->mc->lists->batchSubscribe($this->mailChimpListID, $ems, false, true);

		$addCount = $response['add_count'];
		$added = $response['adds'];
		$updateCount = $response['update_count'];

		return $this;
	}

	/**
	 * Sync this site members into mailchimps
	 */
	public function syncSubscribers()
	{
		$members = $this->getSite()->getMailedMembers();

		$mails = array();
		foreach($members as $member)
		{
			$mails[] = $member->userEmail;
		}

		if(count($mails) > 0)
		{
			$this->addSubscriber($mails);
		}
	}

	public function getSubscribers()
	{
		return $this->newsletter->mc->lists->members($this->mailChimpListID);
	}

	public function isConnected()
	{
		return $this->mailChimpListID == ""? false : true;
	}

	public function prepareTemplate($template = null)
	{
		$template = $template ? $template : $this->siteNewsletterTemplate;
		$site = $this->getSite();

		// search for {site.activities}
		if(strpos($template, '{site.activities}') !== false)
		{
			// get latest activities.
			$latestActivities = $site->getLatestActivities();

			$activities = '<h3><u>Latest Activities</u></h3>';
			if(count($activities) > 0)
			{
				$no = 1;
				foreach($latestActivities as $activity)
				{
					$title = $activity->activityName;
					$desc = $activity->activityDescription;
					$start = date('d F Y', strtotime($activity->activityStartDate));
					$end = date('d F Y', strtotime($activity->activityEndDate));

					$activities .= '<h4>'.$no++.'. '.$title.'</h4>';
					$activities .= '<div>'.$desc.'</div>';
					$activities .= '<div>From '.$start.' to '.$end. '</div>';
				}
			}
			else
			{
				$activities .= '<div>- No latest activities available. -</div>';
			}

			$template = str_replace('{site.activities}', $activities, $template);
		}

		// search for {site.articles}
		if(strpos($template, '{site.articles}'))
		{
			$latestArticles = $site->getLatestArticles();

			$articles = '<h3><u>Latest Articles</u></h3>';
			if(count($latestArticles) > 0)
			{
				$no = 1;
				foreach($latestArticles as $article)
				{
					$title = $article->articleName;
					$text = $article->getSimplifiedText()."...";
					$link = $article->getFrontendLink();

					$articles .= "<h4 style='margin-bottom:5px;'>$no. <a href='$link'>$title</a></h4>";
					$articles .= "<div>$text</div>";

					$no++;
				}
			}
			else
			{
				$articles .= "- We're sorry but we don't have any latest article. -";
			}

			$template = str_replace('{site.articles}', $articles, $template);
		}

		return $template;
	}
}



?>