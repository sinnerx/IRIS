<?php
/**
 * Public api
 */
class Controller_Public
{
	public function sites()
	{
		db::from('site');

		$sites = db::get()->result();

		$response = array('status' => 'success', 'data' => array());

		foreach($sites as $site)
			$response['data'][$site['siteID']] = $site['siteName'];

		return json_encode($response);
	}
}

