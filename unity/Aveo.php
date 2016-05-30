<?php
namespace Iris;

class Aveo
{
	protected $unity;

	public function  __construct(\Iris\Unity $unity)
	{
		$this->unity = $unity;
	}

	/**
	 * Get aveo db config
	 * @return array
	 */
	public function getConfig()
	{
		if(!file_exists(__DIR__.'/../.credentials/db.aveo.php'))
			throw new \Exception('IRIS credentials does not exists.');
		
		return require_once __DIR__.'/../.credentials/db.aveo.php';
	}

	/**
	 * Get aveo pdo connection
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

	/**
	 * TODO : Sync iris site with aveo
	 */
	public function siteSyncronize($siteId)
	{
		// iris PDO connection
		$irisPDO = $this->unity->getIris()->getConnection();

		//.. todo : query based on given siteId

		// aveo PDO connection
		$aveoPDO = $this->getConnection();

		//.. todo : update aveo site id
		//.. if site does not exists in aveo, create
		//.. if already exists, update
	}
}