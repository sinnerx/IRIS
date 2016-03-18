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

	public function uploadLog()
	{
		template::set(false);
		
		$uploads = db::join('site', 'site.siteID = billing_transaction_upload.siteID')->order_by('billingTransactionUploadCreatedDate', 'DESC')->get('billing_transaction_upload')->result();

		return view::render('public/uploadLog', array('uploads' => $uploads));
	}

	protected function getCafeVersion()
	{
		$path = apps::$root.'../repo/cafe/.git/refs/heads/master';
		$cafeRoot = apps::$root.'../repo/cafe';

		$currentVersion = file_get_contents($path);

		return trim($currentVersion);
	}

	public function uploadReschedule()
	{
		template::set(false);

		$sites = db::get('site')->result();

		$data = array();

		foreach($sites as $row)
		{
			$siteID = $row['siteID'];

			$day = floor($siteID / 26) + 1;

			$days = array(
				1 => 'Monday',
				2 => 'Tuesday',
				3 => 'Wednesday',
				4 => 'Thursday',
				5 => 'Friday'
			);

			$data[$days[$day]][] = $row;
		}

		view::render('public/uploadReschedule', array('sites' => $data));
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

