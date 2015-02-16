<?php
namespace model\site\newsletter;
use db;

class Push extends \Origami
{
	protected $table = 'site_newsletter_push';
	protected $primary = 'siteNewsletterPushID';

	/**
	 * Non ORM method.
	 * @return boolean whether newsletters has been pushed or not on the given date.
	 */
	public function hasPushed($date)
	{
		$res = db::where('date(siteNewsletterPushDate)', $date)->get($this->table)->result();

		return $res ? true : false;
	}
}