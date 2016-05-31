<?php
namespace Iris;

class Unity
{
	const COOKIE_NAME = 'SSO_ID';

	protected static $instance;

	protected $userLoginEvent = array();

	protected $config = array();

	/**
	 * Aveo instance
	 * @var \Iris\Aveo
	 */
	protected $aveo;

	/**
	 * Iris instance
	 * @var \Iris\Iris
	 */
	protected $iris;

	public function __construct(array $config = array())
	{
		static::$instance = $this;
	}

	public function setConfig(array $config)
	{
		foreach($config as $key => $value)
			$this->config[$key] = $value;
	}

	public function sessionStart()
	{
		if(session_status() != PHP_SESSION_NONE)
			return;

		session_start();
	}

	public static function getInstance()
	{
		return static::$instance;
	}

	public function onUserLogin(\Closure $callback)
	{
		$this->userLoginEvent[] = $callback;
	}

	public function getAveo()
	{
		if(!$this->aveo)
		{
			require_once __DIR__.'/Aveo.php';

			$this->aveo = new \Iris\Aveo($this);
		}

		return $this->aveo;
	}

	public function getIris()
	{
		if(!$this->iris)
		{
			require_once __DIR__.'/Iris.php';

			$this->iris = new \Iris\Iris($this);
		}

		return $this->iris;
	}

	/**
	 * Log user in.
	 * @param string email
	 * @param string password
	 * @param \Pdo|null if \Pdo is passed, the connection must be pointed to database iris!
	 * @return false|array
	 */
	public function logUserIn($email, $password, \Pdo $pdo = null)
	{
		$pdo = $pdo ? : $this->getIris()->getConnection();

		$statement = $pdo->prepare('SELECT U.userID, U.userIC, U.userEmail, U.userLevel, U.userStatus, U.userPassword, UP.userProfileFullName, UP.userProfileLastName ,SM.siteID 
			FROM user U 
			INNER JOIN user_profile UP ON U.userID = UP.userID 
			LEFT OUTER JOIN site_manager SM ON U.userID = SM.userID  
			WHERE U.userEmail = ? AND U.userPassword = ?');

		$statement->bindValue(1, $email);

		$password = md5(sha1($password.substr($password, 0,4)).$password);

		$statement->bindValue(2, $password);

		$statement->execute();

		if(!($row = $statement->fetch(\PDO::FETCH_ASSOC)))
			return false;

		foreach($this->userLoginEvent as $callback)
			$callback($row, $pdo);
		
		$_SESSION['sso_user']['user_id'] = $row['userID'];

		$_SESSION['sso_user']['user_level'] = $row['userLevel'];

		return $row;
	}

	public function getConnection()
	{
		return $this->getIris()->getConnection();
	}

	/**
	* @return array iris user record
	*/
	public function userRefresh()
	{
		$pdo = $this->getIris()->getConnection();

		$statement = $pdo->prepare('SELECT U.userID, U.userIC, U.userEmail, U.userLevel, U.userStatus, U.userPassword, UP.userProfileFullName, UP.userProfileLastName ,SM.siteID 
			FROM user U 
			INNER JOIN user_profile UP ON U.userID = UP.userID 
			LEFT OUTER JOIN site_manager SM ON U.userID = SM.userID 
			WHERE U.userID = ?');

		$statement->bindValue(1, $_SESSION['sso_user']['user_id']);

		if(!$statement->execute())
		{
			print_r($statement->errorInfo());
			die;
		}

		$row = $statement->fetch(\Pdo::FETCH_ASSOC);

		foreach($this->userLoginEvent as $callback)
			$callback($row, $pdo);

		return $row;
	}

	public static function refresh()
	{
		return static::getInstance()->userRefresh();
	}

	/**
	 * Log user out
	 */
	public function logUserOut()
	{
		unset($_SESSION['sso_user']);
	}

	public function siteAddSyncronize($siteID)
	{
		// sync site information to aveo
		$this->getAveo()->siteAddSyncronize($siteID);
	}	

	public function siteUpdateSyncronize($siteID)
	{
		// sync site information to aveo
		$this->getAveo()->siteUpdateSyncronize($siteID);
	}

	/**
	 * Check whether user is logged in
	 * @return bool
	 */
	public function userIsLoggedIn()
	{
		return isset($_SESSION['sso_user']);
	}

	public static function logOut()
	{
		return static::getInstance()->logUserOut();
	}

	public static function logIn($user, $password, \Pdo $pdo = null)
	{
		return static::getInstance()->logUserIn($user, $password, $pdo);
	}

	public static function isLoggedIn()
	{
		return static::getInstance()->userIsLoggedIn();
	}

	public static function iris()
	{
		return static::getInstance()->getIris();
	}

	public static function aveo()
	{
		return static::getInstance()->getAveo();
	}

	public static function siteAddSync($siteID)
	{
		static::getInstance()->siteAddSyncronize($siteID);
	}	

	public static function siteUpdateSync($siteID)
	{
		static::getInstance()->siteUpdateSyncronize($siteID);
	}
}

class AveoBridge
{
	public function updateSite()
	{
		
	}
}

?>