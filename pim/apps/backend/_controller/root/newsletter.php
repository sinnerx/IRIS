<?php
class Controller_Newsletter
{
	public function index($page = 1)
	{
		db::from('site');

		if($siteID = request::get('disconnect'))
		{
			$site = model::orm('site/site')->find($siteID);

			$site->getSiteNewsletter()->unlink();

			redirect::to('newsletter/index');
		}

		// pagination::initiate(Array(
						// "totalRow"=>db::num_rows(),
						// "currentPage"=>$page,
						// "urlFormat"=>url::base("site/index/{page}",true)
								// ));

		db::order_by("siteName","ASC");
		// db::limit(pagination::get("limit"),pagination::recordNo()-1);

		$newsletter = model::load("newsletter/newsletter");

		// get list of mailChimpList, the id as the key.
		$data['mailChimpList'] = $newsletter->getMailChimpList();

		## get result.
		$data['res_site']		= db::get()->result('siteID');

		$data['res_newsletter'] = db::get('site_newsletter')->result('siteID');

		view::render('root/newsletter/index', $data);
	}
}


?>