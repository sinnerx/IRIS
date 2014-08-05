<?php
namespace model\site;
use db, session, model, pagination, apps;
class Message
{
	public function getCategoryName($no = null)
	{
		$catNameR	= Array(
					1=>"Aduan",
					2=>"Pertanyaan",
					3=>"Cadangan"
							);

		return $no?$catNameR[$no]:$catNameR;
	}

	public function getSupportEmail()
	{
		$supportEmail	= apps::config("supportEmail");
		return $supportEmail;
	}

	public function getPaginatedMessage($siteID = null,$currentPage,$urlFormat = false)
	{
		db::from("site_message");
		db::where("siteID",$siteID);

		pagination::initiate(Array(
						"totalRow"=>db::num_rows(),
						"currentPage"=>$currentPage,
						"urlFormat"=>$urlFormat
								));

		db::join("message","message.messageID = site_message.messageID");
		db::join("contact","contact.contactID = site_message.siteMessageCreatedUser");

		db::order_by("siteMessageID","desc")
		->limit(pagination::get("limit"),pagination::recordNo()-1);

		return db::get()->result();
	}

	public function readPublicMessage($siteMessageID)
	{
		db::from("site_message")
		->where("siteMessageType",1) ## public message.
		->where("siteMessageID",$siteMessageID)
		->join("message","message.messageID = site_message.messageID")
		->join("contact","site_message.siteMessageCreatedUser = contact.contactID");

		return db::get()->row();
	}

	public function createPublicMessage($siteID = null,$data)
	{
		## get site Email by site.
		if($siteID)
		{
			$siteEmail	= db::select("siteInfoEmail")->where("siteID",$siteID)->get("site_info")->row("siteInfoEmail");	
		}
		## no siteID was given, use support email address. 
		else
		{
			$siteEmail	= $this->getSupportEmail();
		}

		## create message.
		db::insert("message",Array(
						"messageSubject"=>$data['messageSubject'],
						"messageContent"=>$data['messageContent'],
						"messageCreatedDate"=>now()
								));

		$messageID	= db::getLastID("message","messageID");

		## create site_message;
		db::insert("site_message",Array(
						"messageID"=>$messageID,
						"siteID"=>$siteID,
						"siteMessageCreatedUser"=>$this->getContactID($data['contactEmail'],$data),
						"siteMessageType"=>1, # public
						"siteMessageCategory"=>$data['siteMessageCategory'],
						"siteMessageReadStatus"=>0,
						"siteMessageReadUser"=>0
										));

		$siteMessageID	= db::getLastID("site_message","siteMessageID");

		## send email
		## get mail template
		$templateServices	= model::load("template/services");
		$templateSubject	= $templateServices->getTemplate("mail","public-subject",Array("messageSubject"=>$data['messageSubject']));
		$templateContent	= $templateServices->getTemplate("mail","public-message",Array(
																			"siteMessageCategory"=>$this->getCategoryName($data['siteMessageCategory']),
																			"contactName"=>$data['contactName'],
																			"contactEmail"=>$data['contactEmail'],
																			"contactPhoneNo"=>$data['contactPhoneNo'],
																			"messageSubject"=>$data['messageSubject'],
																			"messageContent"=>nl2br($data['messageContent']),
																			"messageDate"=>date("g:i A, d F, Y",strtotime(now())),
																			"messageLink"=>null
																							));

		## prepare email
		$emailList	= Array();

		## check site email.
		if($siteEmail)
		{
			$emailList[]	= $siteEmail;
		}

		if($siteID)
		{
			## get both manager email.
			$res_manager	= model::load("site/manager")->getManagersBySite($siteID);

			## if got record.
			if($res_manager)
			{
				foreach($res_manager as $row)
				{
					$emailList[]	= $row['userEmail'];
				}
			}

			## sender.
			$emailList[]	= $data['contactEmail'];
		}

		## mail, if there're an address.
		if(count($emailList) > 0)
		{
			model::load("mailing/services")->mail(null,$emailList,$templateSubject,$templateContent);
		}

		return $this->encryptID($siteMessageID);
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

	## required helper function : encryptNo();
	public function encryptID($id,$state = "encrypt")
	{
		$additionalNo	= 0;
		$maxNoPerAlphabet	= 9999;

		return encryptNo($id,$state,$additionalNo,$maxNoPerAlphabet);
	}
}