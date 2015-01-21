<?php
namespace model\newsletter;
use apps, db, model;

/**
 * This news letter model uses mailchimp.
 */
class Newsletter
{
	private $apiKey = "befd0314e7e5820f454e52f707bfbcea-us9";

	// app info
	private $clientID = "431097412016";
	private $clientSecret = "f34f516c319ec8fe4d80b0d4d8d5fb4e";

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

		if($response['total'] == 1)
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

	/**
	 * Send the campaign.
	 */
	public function sendCampaign($id, $prod = false)
	{
		$campaigns = $this->mc->campaigns;

		if(!$prod)
			return $campaigns->sendTest($id, array('rahimie@digitalgaia.com'));
		else
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