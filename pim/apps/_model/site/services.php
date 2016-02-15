<?php
namespace model\site;
use model, db, session, request as reqs; ## need to alias, because site also got request. might collide one day.
class Services extends Site
{
	## used in hooked controller auth, and site/add
	public function checkSiteSlug($slug	= null)
	{

		## get slug from param or named request (if null param)
		$slug	= !$slug?reqs::named("site-slug"):$slug;
		$slug	= trim($slug);

		## query.
		$result	= db::from("site")->where("siteSlug",$slug)->get()->result();	
		return !$result?false:true;
	}

	## used in site/add
	public function checkManager($emailR)
	{
		## if is string, convert to array.
		$emailR	= is_string($emailR)?explode(",",$emailR):$emailR;

		## trim and remove spaces.
		#array_walk($emailR, create_function('&$emailR', '$emailR = trim($emailR);')); 

		## query.
		db::select("user.userID as userID,userEmail,siteManagerID");
		db::from("user");
		db::where("userLevel = '2' AND userEmail IN ('".implode("','",$emailR)."')");
		db::join("site_manager","user.userID = site_manager.userID");
		$result	= db::get()->result('userEmail');

		## 1. if couldn't find one of the email.
		if(count($result) != count($emailR))
		{
			$noEmail	= Array();
			foreach($emailR as $email)
			{
				if(!isset($result[$email]))
				{
					$noEmail[]	= $email;
				}
			}

			return Array(false,"Couldn't find manager email record : ".implode(", ",$noEmail));
		}

		## 2. check if already registered in other site.
		$registeredEmail	= Array();
		foreach($result as $email=>$row)
		{
			if($row['siteManagerID'])
			{
				$registeredEmail[]	= $email;
			}
		}

		## if got registered oledi
		if(count($registeredEmail) > 0)
		{
			return Array(false,"This manager already registered to another site : ".implode(", ",$registeredEmail));
		}

		foreach($result as $row)
		{
			$userIDR[]	= $row['userID'];
		}

		## 3. all success, return with user id.
		return Array(true,$userIDR);
	}
}


?>