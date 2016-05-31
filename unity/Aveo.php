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
		//select site
		$statementSite = $irisPDO->prepare('SELECT S.*, CS.clusterSiteID, CS.siteID, CS.clusterID , SI.siteInfoAddress, S.siteID as siteID, S.siteCreatedUser as siteUser  
			FROM site S 
			LEFT OUTER JOIN cluster_site CS ON CS.siteID = S.siteID
			LEFT OUTER JOIN site_info SI ON SI.siteID = S.siteID
			WHERE S.siteID = ?');

		$statementSite->bindValue(1, $siteId);

		if(!$statementSite->execute())
		{
			print_r($statementSite->errorInfo());
			die;
		}		

		$recordSite = $statementSite->fetch(\PDO::FETCH_ASSOC);



		// aveo PDO connection
		$aveoPDO = $this->getConnection();				

		$statementCheckLocation = $aveoPDO->prepare("SELECT id FROM locations WHERE id = ". $siteId);

		if(!$statementCheckLocation->execute())
		{
			print_r($statementSite->errorInfo());
			die;
		}


		//.. if site does not exists in aveo, create
		if(!($statementCheckLocation->fetch(\PDO::FETCH_ASSOC))){
			//var_dump("create");
			
				//var_dump($recordSite);
				//die;

				//.. todo : update aveo site id
				$statementLocation = $aveoPDO->prepare("INSERT INTO locations (id, name, country, created_at, user_id, address, code, is_ktw, is_pi1m, cluster_id, state_id)
		                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");

				$values = array(
					$siteId,
					$recordSite['siteName'],
					'my',
					$recordSite['siteUser'],
					$recordSite['siteInfoAddress']."",
					$recordSite['siteRefID'],
					'1',
					'1',
					$recordSite['clusterID'],
					$recordSite['stateID'],
					);

				foreach(range(0,9) as $no)
					$statementLocation->bindValue($no+1, $values[$no]);

				if(!$statementLocation->execute())
				{
					//var_dump($statement);
					var_dump($statementLocation->errorInfo());
					die;
				}

				$statementSiteAveo = $aveoPDO->prepare("INSERT INTO sites (name, code, userid, created_at, site_type, location_id, state_id) VALUES (?, ?, ?, NOW(),'PIM', ?,?)");

				$values = array(
					$recordSite['siteName'],
					$recordSite['siteRefID']. '_00',
					$recordSite['siteUser'],
					$siteId,
					$recordSite['stateID'],
					);

				foreach(range(0,4) as $no)
					$statementSiteAveo->bindValue($no+1, $values[$no]);

				if(!$statementSiteAveo->execute())
				{
					//var_dump($statement);
					var_dump($statementSiteAveo->errorInfo());
					die;
				}	
		}//end if statementCheckLocation

		else{
			//var_dump("update");

			//for update 1st record of sites in aveo
			$statementSelectLocation = $aveoPDO->prepare("SELECT * FROM locations WHERE id = ". $siteId);
			
			if(!$statementSelectLocation->execute())
			{
				//var_dump($statement);
				var_dump($statementSelectLocation->errorInfo());
				die;
			}	
			$recordSelectLocation = $statementSelectLocation->fetch(\PDO::FETCH_ASSOC);

			$statementUpdateSiteAveo = $aveoPDO->prepare("UPDATE sites SET name = ?, code = ?, updated_at = NOW(), state_id = ? WHERE location_id = ? AND name = ?");

			//var_dump($recordSelectLocation['name']);
			//die;
			$values = array(
				$recordSite['siteName'],
				$recordSite['siteRefID']. '_00',
				$recordSite['stateID'],
				$siteId,
				$recordSelectLocation['name'],
				);

			foreach(range(0,4) as $no)
				$statementUpdateSiteAveo->bindValue($no+1, $values[$no]);			

			if(!$statementUpdateSiteAveo->execute())
			{
				//var_dump($statement);
				var_dump($statementUpdateSiteAveo->errorInfo());
				die;
			}

			//end update 1st record of sites in aveo

			//.. todo : update aveo site id
			$statementLocation = $aveoPDO->prepare("UPDATE locations SET name = ?, address = ?, code = ?, updated_at = NOW(), cluster_id = ?, state_id = ? WHERE id = ?");

			$values = array(
				$recordSite['siteName'],
				$recordSite['siteInfoAddress']."",
				$recordSite['siteRefID'],
				$recordSite['clusterID'],
				$recordSite['stateID'],
				$siteId,
				);

			foreach(range(0,5) as $no)
				$statementLocation->bindValue($no+1, $values[$no]);

			if(!$statementLocation->execute())
			{
				//var_dump($statement);
				var_dump($statementLocation->errorInfo());
				die;
			}	

			$statementSiteAveo = $aveoPDO->prepare("SELECT * FROM sites WHERE location_id = " . $siteId);
			if(!$statementSiteAveo->execute()){
				print_r($statementSiteAveo->errorInfo());
				die;
			}	

			$resultSiteAveo = $statementSiteAveo->fetchAll(\PDO::FETCH_ASSOC);
			//var_dump($resultSiteAveo);
			foreach ($resultSiteAveo as $keySite) {
				# code...
				//var_dump($keySite);
				$temp_code = $keySite['code'];
				$temp_code = $recordSite['siteRefID'] . substr($temp_code, -3);

				$rowSite = $aveoPDO->prepare("UPDATE sites SET code = '$temp_code', updated_at = NOW(), state_id = '".$recordSite['state']."' WHERE id = ". $keySite['id']);

				if(!$rowSite->execute())
				{
					//var_dump($statement);
					var_dump($rowSite->errorInfo());
					die;
				}

			}//end foreach

		}//end else statementCheckLocation
	}
}