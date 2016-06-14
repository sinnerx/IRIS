<?php
namespace Iris;

class Aveo
{
	protected $unity;

	protected $connection;

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
			throw new \Exception('AVEO credentials does not exists.');
		
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
	 * Syncronize user
	 */
	public function userLoginSyncronize(array $row, $password = '')
	{
		$level = $row['userLevel'];
	
		if(!in_array($level, array(2, 5, 6, 99))){
			return;
		}

		try
		{
			$pdo = isset($GLOBALS['aveoconnection']) ? $GLOBALS['aveoconnection'] : $this->getConnection();
		}
		catch(\Exception $e)
		{
			throw $e;

			// if problem with aveo. skip.
			throw new \Exception("Problem with connection to asset db");
		}

		// create or update new user record.
		$statement = $pdo->prepare('SELECT * FROM users WHERE email = ?');

		$statement->bindValue(1, $row['userEmail']);

		$statement->execute();

		// create aveo user record
		if(!($aveoRow = $statement->fetch(\PDO::FETCH_ASSOC)))
		{
			//var_dump("create aveo user record");
			//die;
			$statement = $pdo->prepare("INSERT INTO users (email, password, permissions, activated, first_name, last_name, created_at, employee_num, location_id, dept_id, username)
	                VALUES (?, ?, ?, 1, ?, ?, NOW(), ?, ?, 1, ?)");
			
			$level = $row['userLevel'];

			switch($level)
			{
				// case 2: // sitemanager
				// 	$permissions 	= "{" . '"' . "user" . '"' . ":1}";
				// 	$group_id 		= 3;
				// break;
				// case 5: // finance
				// 	$permissions 	= "{" . '"' . "finance" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
				// 	$group_id 		= 4;				
				// break;	
				case 2:			
				case 5: // finance & site manager Temporarily
					$permissions 	= "{" . '"' . "finance" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
					$group_id 		= 4;				
				break;
				case 3:
				case 4:
				case 6: // admin
					$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
					$group_id 		= 1;
				break;
				case 99: // superadmin
					$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1, ". '"' . "finance" . '"' . ":1, ". '"' . "superuser" . '"' . ":1}";
					$group_id 		= 5;
				break;			
		
				break;
			}

			$values = array(
				$row['userEmail'],
				password_hash($password, PASSWORD_DEFAULT),
				$permissions,
				$row['userProfileFullName'] ? : '',
				$row['userProfileLastName'] ? $row['userProfileLastName'] = $row['userProfileLastName'] : $row['userProfileLastName'] = '',
				$row['userIC'],
				$row['siteID'] ? : 0,
				$row['userID'] // we dont have username
				);

			foreach(range(0,7) as $no)
				$statement->bindValue($no+1, $values[$no]);

			if(!$statement->execute())
			{
				var_dump($statement->errorInfo());
				die;
			}


			$statement_group = $pdo->prepare("INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)");

			$statement_group->bindValue(1, $row['userID']);

			$statement_group->bindValue(2, $group_id);
			
			if(!$statement_group->execute())
			{
				var_dump($statement_group->errorInfo());
				die;
			}
		}
		else
		{
			$statement = $pdo->prepare("UPDATE users SET email = ?, permissions = ?, first_name = ?,last_name = ?, updated_at = NOW(), employee_num = ?, location_id = ? WHERE username = ?");
			
			// need to figure out the permission based on level
			$level = $row['userLevel'];

			switch($level)
			{
				// case 2: // sitemanager
				// 	$permissions 	= "{" . '"' . "user" . '"' . ":1}";
				// 	$group_id 		= 3;
				// break;
				// case 5: // finance
				// 	$permissions 	= "{" . '"' . "finance" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
				// 	$group_id 		= 4;				
				// break;	
				case 2:			
				case 5: // finance & site manager Temporarily
					$permissions 	= "{" . '"' . "finance" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
					$group_id 		= 4;				
				break;
				case 3:
				case 4:
				case 6: // admin
					$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
					$group_id 		= 1;
				break;
				case 99: // superadmin
					$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1, ". '"' . "finance" . '"' . ":1, ". '"' . "superuser" . '"' . ":1}";
					$group_id 		= 5;
				break;			
		
				break;

			}

			$values = array(
				$row['userEmail'],
				$permissions,
				$row['userProfileFullName'] ? : '',
				$row['userProfileLastName'] ? : '',
				$row['userIC'],
				$row['siteID'] ? : 0,
				$row['userID'],
				);

			foreach(range(0,6) as $no)
				$statement->bindValue($no+1, $values[$no]);

			if(!$statement->execute())
			{
				//var_dump($statement);
				var_dump($statement->errorInfo());
				die;
			}

			$statement_del_group 	= $pdo->prepare("DELETE FROM users_groups WHERE user_id = ?");
			$statement_del_group->bindValue(1, $row['userID']);
			if(!$statement_del_group->execute()){
				var_dump($statement_del_group->errorInfo);
			}

			$statement_group = $pdo->prepare("INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)");

			$statement_group->bindValue(1, $row['userID']);
			$statement_group->bindValue(2, $group_id);
			if(!$statement_group->execute())
			{
				var_dump($statement_group->errorInfo());
				die;
			}
		}
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