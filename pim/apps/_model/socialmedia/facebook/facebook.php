<?php
namespace model\socialmedia\facebook;
use apps, session;

/**
 * A core class for dealing with facebook. uses Facebook PHP SDK 4
 */

class Facebook
{
	private $session = null;
	private $defaultRedirectionUrl = null;
	private $helper = null;
	private $scope = array('public_profile');

	/**
	 * @param string redirectUrl (required redirection url)
	 * @param mixed scope for your request
	 */
	public function __construct($redirectUrl = null, array $scope = null)
	{
		if($scope)
			$this->addScope($scope);

		// if already has an existing scope stored in session. append.
		if(session::has('facebook.scope'))
			$this->addScope(session::get('facebook.scope'));

		$this->setRedirectUrl($redirectUrl);

		// need to create di container later for this static use.
		$this->appID = apps::config("fbAppID");
		$this->appSecret = apps::config("fbAppSecret");

		\Facebook\FacebookSession::setDefaultApplication($this->appID, $this->appSecret);
	}

	public function setRedirectUrl($redirectUrl)
	{
		$this->defaultRedirectionUrl = $redirectUrl;
		return $this;
	}

	public function addScope(Array $scopes)
	{
		foreach($scopes as $scope)
		{
			if(!in_array($scope, $this->scope))
				$this->scope[] = $scope;
		}

		return $this;
	}

	/**
	 * Main initiation method for facebook.
	 * @param array scopes (add additional scopes to the default or existing one stored in session)
	 */
	public function initiate(array $scopes = array())
	{
		if(!$this->defaultRedirectionUrl)
			throw new Exception("Redirection url is required");

		$helper = $this->createRedirectLoginHelper($this->defaultRedirectionUrl);

		if(count($scopes) > 0)
			$this->addScope($scopes);

		if(session::has('facebook.accessToken'))
		{
			$session = new \Facebook\FacebookSession(session::get('facebook.accessToken'));

			// de-valid the current accessToken, and create a new one.
			if(!$this->checkPermission($this->scope, true))
			{
				session::destroy('facebook.accessToken');
				return $this->initiate();
			}

			$this->session = $session;
		}
		// initiate.
		else
		{
			try
			{
				$session = $helper->getSessionFromRedirect();
			}
			catch(\FacebookRequestException $e)
			{
				return false;
			}

			if(!$session)
				return false;

			$this->session = $session;

			if(!$this->checkPermissionFromSessionInfo())
				return false;

			// save token, and permissions
			session::set("facebook.accessToken", $session->getToken());
			session::set("facebook.scope", $this->getPermissions(true));
		}

		return $session;
	}

	private function createRedirectLoginHelper($redirectUrl)
	{
		$helper = new \Facebook\FacebookRedirectLoginHelper($redirectUrl);
		
		// save the helper.
		$this->helper = $helper;

		return $helper;
	}

	/**
	 * check if there's still an ungranted permission
	 */
	private function checkPermissionFromSessionInfo()
	{
		$sessionInfo = $this->session->getSessionInfo();
		$scopes = $sessionInfo->getScopes();

		foreach($this->scope as $scope)
		{
			if(!in_array($scope, $scopes))
				return false;
		}

		return true;
	}

	/**
	 * Validate given scope
	 */
	public function checkPermission(array $scopes, $fromSession = false, $fromCache = false)
	{

		// check permission from existing scope stored in session
		if($fromSession && !$fromCache)
		{
			$permissions = session::get("facebook.scope");
		}
		// from this object property.
		else if($fromCache && !$fromSession)
		{
			$permissions = $this->scope;
		}
		else
		{
			$permissions = $this->getPermissions();

			if(is_string($scopes))
				$scopes = explode(",", $scope);
		}

		foreach($scopes as $scope)
		{
			if(!in_array($scope, $permissions))
				return false;
		}

		return true;
	}

	/**
	 * Get permissions from existing token.
	 * @return array permissions
	 */
	public function getPermissions($fromCache = false)
	{
		// if from the object itself.
		if($fromCache)
		{
			$permissions = $this->scope;
		}
		else
		{
			$permissions = $this->request("GET", "/me/permissions")->asArray();
			$permissions = explode(",", $permissions[0]->permission);
		}

		return $permissions;
	}

	public function getLoginUrl(array $scopes = array())
	{
		// use the given scope if passed. else use default one.
		if(count($scopes) > 0)
			$this->addScope($scopes);

		$helper = $this->createRedirectLoginHelper($this->defaultRedirectionUrl);
		$loginUrl = $helper->getLoginUrl($this->scope);

		return $loginUrl;
	}

	public function getLogoutUrl($nextUrl = null)
	{
		$nextUrl = !$nextUrl? $this->defaultRedirectionUrl : $nextUrl;

		$logoutUrl = $this->helper->getLogoutUrl($this->session, $nextUrl);

		return $logoutUrl;
	}

	/**
	 * return \Facebook\FacebookResponse
	 * https://developers.facebook.com/docs/php/FacebookResponse/4.0.0
	 */
	public function request($method, $path, $params = null)
	{
		$request = new \Facebook\FacebookRequest($this->session, $method, $path, $params);
		return $request->execute();
	}
}

?>