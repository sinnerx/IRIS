<?php
/**
 * Public api
 */
class Controller_Public
{
	public function __construct()
	{
		header("Access-Control-Allow-Origin: *");
	}
	
	public function sites()
	{
		db::from('site');

		$sites = db::get()->result();

		$response = array('status' => 'success', 'data' => array());

		foreach($sites as $site)
			$response['data'][$site['siteID']] = $site['siteName'];

		return json_encode($response);
	}

	protected function getCafeVersion()
	{
		$path = apps::$root.'../repo/cafe/.git/refs/heads/master';
		$cafeRoot = apps::$root.'../repo/cafe';

		$currentVersion = file_get_contents($path);

		return trim($currentVersion);
	}

	public function ping()
	{
		$version = $this->getCafeVersion();

		return json_encode(array(
			'status' => 'success',
			'cafe_version' => $version
			));
	}
}

