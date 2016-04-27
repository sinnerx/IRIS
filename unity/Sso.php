<?php
namespace Iris;

class Sso
{
	const COOKIE_NAME = 'SSO_ID';

	protected static $instance;

	protected $userLoginEvent = array();

	protected $config = array();

	public function __construct(array $config = array())
	{
		if(!$config)
		{
			// get iris database.php config
		}
		else
		{
			$this->config = $config;
		}

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

	/**
	 * Log user in.
	 * @param string email
	 * @param string password
	 * @param \Pdo|null if \Pdo is passed, the connection must be pointed to database iris!
	 * @return false|array
	 */
	public function logUserIn($email, $password, \Pdo $pdo = null)
	{
		$config = $this->config;

		$pdo = $pdo ? $pdo : new \Pdo('mysql:host='.$config['host'].';dbname='.$config['name'], $config['user'], $config['pass']);

		$statement = $pdo->prepare('SELECT * FROM user WHERE userEmail = ? AND userPassword = ?');

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

	/**
	 * Log user out
	 */
	public function logUserOut()
	{
		unset($_SESSION['sso_user']);
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
}

?>