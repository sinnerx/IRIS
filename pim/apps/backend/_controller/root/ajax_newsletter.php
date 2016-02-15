<?php
class Controller_Ajax_Newsletter
{
	public function setListID($siteID, $mailChimpListID)
	{
		$newsletter = orm('site/site')->find($siteID)->getSiteNewsletter();
		$newsletter->mailChimpListID = $mailChimpListID;
		$newsletter->save();
	}
}