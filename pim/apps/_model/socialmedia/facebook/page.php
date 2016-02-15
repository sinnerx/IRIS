<?php
namespace model\socialmedia\facebook;

/*
## page entitled to each site.
facebook_page
- facebookPageID
- siteID
- facebookPID
- facebookPageCreatedDate
- facebookPageCreatedUser

*/

class Page
{
	public function __construct(\model\socialmedia\facebook\facebook $facebook, $pageId)
	{
		$this->facebook = $facebook;
		$this->pageId = $pageId;
		$this->token = $this->getPage($pageId, 'access_token');
	}

	/**
	 * POST to /me/feed
	 */
	public function postToWall(array $data)
	{
		return $this->request('POST', 'feed', $data);
	}

	/**
	 * Make a request for page based on the page token gained from /user/accounts
	 * already concenated with /me/.
	 * @param string method
	 * @param string path
	 * @param array params
	 */
	public function request($method, $path = null, $params = array())
	{
		$path = $path? '/me/'.trim($path, '/') : "/me";
		return $this->facebook->request($method, $path, $params, $this->token);
	}

	/**
	 * Get user page 
	 * @return array page
	 */
	private function getPage($pageId, $column = null)
	{
		$pages = $this->facebook->getPages();

		// find page with the id.
		foreach($pages as $page)
		{
			$page = (array) $page;

			if($page['id'] == $pageId)
				break;
		}

		return $column ? $page[$column] : $page;
	}
}