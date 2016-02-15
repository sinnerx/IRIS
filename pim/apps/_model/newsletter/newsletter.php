<?php
namespace model\newsletter;
use apps, db, model;

/**
 * This news letter model uses mailchimp.
 */
class Newsletter
{
	// private $apiKey = "befd0314e7e5820f454e52f707bfbcea-us9";
	private $apiKey = "2c0c8f692e8b49dd52f5946e0a14c91b-us10";

	// app info
	// private $clientID = "431097412016";
	private $clientID = "581101117935";
	// private $clientSecret = "f34f516c319ec8fe4d80b0d4d8d5fb4e";
	private $clientSecret = "b413b5e7df485ffee09a93f7e6f73fb7";

	// mailChimpListID
	public $listId = null;

	public function __construct()
	{
		$this->mc = new \Mailchimp($this->apiKey, $opts);
	}

	public function getMailChimpList()
	{
		$response = $this->mc->lists->getList();

		$rebuilt = array();

		if($response['total'] > 0)
		{
			foreach($response['data'] as $row)
			{
				$rebuilt[$row['id']] = $row;
			}
			
		}

		return $rebuilt;
	}

	public function setListID($listId)
	{
		$this->listId = $listId;
	}

	public function hasListID()
	{
		return $this->listId == null || $this->listId == '' ? false : true;
	}

	/**
	 * Basically : 
	 * - create campaign a champaign.
	 */
	public function createCampaign($from_email, $from_name, $subject, $body)
	{
		$listId = $this->listId;

		if(!$listId)
			return;

		$options = array(
			'list_id'=>$listId,
			'subject'=>$subject,
			'from_email'=>$from_email,
			'from_name'=>$from_name,
			);

		$content = array(
			'html'=>$body
			);

		return $this->mc->campaigns->create('regular', $options, $content);
	}

	public function sendTestCampaign($id, array $emails)
	{
		$campaigns = $this->mc->campaigns;
		return $campaigns->sendTest($id, $emails);
	}

	/**
	 * Send the campaign.
	 */
	public function sendCampaign($id)
	{
		$campaigns = $this->mc->campaigns;
		return $campaigns->send($id);
	}

	public function addSubscriber(array $arrays, $listId = null)
	{
		foreach($arrays as $row)
		{
			$batch[] = array(
				'FNAME'=> $row['userProfileFullName'], 
				'LNAME'=> $row['userProfileLastName'],
				'EMAIL'=> $row['userEmail']
							);
		}

		$this->mc->batchSubscribe($listId, $batch, false);
	}
}