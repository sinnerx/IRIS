<?php
namespace Iris;

class Iris
{
	protected $unity;

	protected $connection;

	public function __construct(\Iris\Unity $unity)
	{
		$this->unity = $unity;
	}

	/**
	 * Get iris db config
	 * @return array
	 */
	public function getConfig()
	{
		if(!file_exists(__DIR__.'/../.credentials/db.php'))
			throw new \Exception('IRIS credentials does not exists.');
		
		return require_once __DIR__.'/../.credentials/db.php';
	}

	/**
	 * Get iris pdo connection
	 * @return \Pdo
	 */
	public function getConnection()
	{
		if(!$this->connection)
		{
			$config = $this->getConfig();
			
			$this->connection = new \Pdo('mysql:host='.$config['host'].';dbname='.$config['name'], $config['user'], $config['pass']);
		}

		return $this->connection;
	}
}