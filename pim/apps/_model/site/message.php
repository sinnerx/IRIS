<?php
namespace model\site;
use db, session, model;
class Message
{
	public function readPublicMessage($siteMessageID)
	{
		db::from("site_message")
		->where("siteMessageType",1) ## public message.
		->where("siteMessageID",$siteMessageID)
		->join("message","message.messageID = site_message.messageID")
		->join("contact","site_message.contactID = contact.contactID");

		return db::get()->row();
	}

	public function createPublicMessage($siteID,$data)
	{
		## get site Email.
		$siteEmail	= db::select("siteInfoEmail")->where("siteID",$siteID)->get("site_info")->row("siteInfoEmail");

		## create message.
		db::insert("message",Array(
						"messageSubject"=>$data['messageSubject'],
						"messageContent"=>$data['messageContent'],
						"messageCreatedDate"=>now(),
						"messageCreatedUser"=>session::has("userID")?session::get("userID"):0
								));

		$messageID	= db::getLastID("message","messageID");

		## create site_message;
		db::insert("site_message",Array(
						"messageID"=>$messageID,
						"siteID"=>$siteID,
						"contactID"=>$this->getContactID($data['contactEmail'],$data),
						"siteMessageType"=>1, # public
						"siteMessageReadStatus"=>0,
						"siteMessageReadUser"=>0
										));

		## send email
		## get mail template
		$templateServices	= model::load("template/services");
		$templateSubject	= $templateServices->getTemplate("mail","public-subject",Array("messageSubject"=>$data['messageSubject']));
		$templateContent	= $templateServices->getTemplate("mail","public-message",Array(
																			"contactName"=>$data['contactName'],
																			"contactEmail"=>$data['contactEmail'],
																			"contactPhoneNo"=>$data['contactPhoneNo'],
																			"messageSubject"=>$data['messageSubject'],
																			"messageContent"=>nl2br($data['messageContent']),
																			"messageDate"=>date("g:i A, d F, Y",strtotime(now())),
																			"messageLink"=>null
																							));

		## ok, mail.
		if($siteEmail)
		{
			model::load("mailing/services")->mail(null,$siteEmail,$templateSubject,$templateContent);
		}
	}

	## required $email, $data['contactPhoneNo'], $data['contactName']
	public function getContactID($email,$data)
	{
		## check already registered or not.
		$contactID	= db::from("contact")->where("contactEmail",$email)->get()->row('contactID');

		## return contactID if already got.
		if($contactID)
			return $contactID;

		## else, create one.
		db::insert("contact",Array(
						"contactEmail"=>$email,
						"contactPhoneNo"=>$data["contactPhoneNo"],
						"contactName"=>$data["contactName"]
									));

		## return contact.
		$contactID	= db::getLastID("contact","contactID");

		return $contactID;
	}
}