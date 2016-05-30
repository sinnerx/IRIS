<?php

// file ni diguna (shared) oleh mana2 app yang akan guna sso
require_once __DIR__.'/Sso.php';

$sso = new \Iris\Sso;

// set config for iris db
if(file_exists(__DIR__.'/.credentials/db.php'))
	$sso->setConfig(require_once __DIR__.'/.credentials/db.php');

$sso->onUserLogin(function($row)
{
	$level = $row['userLevel'];
	//var_dump($level);
	if(!in_array($level, array(2, 5, 6, 99))){
		//var_dump("return array of level");
		//die;
		return;
	}
	//die;
	// aveo db config.
	// need to change to environmental based later.
	if(file_exists(__DIR__.'/.credentials/db.aveo.php'))
		$config = require_once __DIR__.'/.credentials/db.aveo.php';

	// create new pdo connection here.
	// buat update query here.
	$pdo = isset($GLOBALS['aveoconnection']) ? $GLOBALS['aveoconnection'] : new \Pdo('mysql:host='.$config['host'].';dbname='.$config['name'], $config['user'], $config['pass']);

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

		
		//$statement_group = $pdo->prepare("UPDATE users_group SET user_id = ? , group_id = ?");

		// need to figure out the permission based on level
		$level = $row['userLevel'];

		switch($level)
		{
			case 2: // sitemanager
				$permissions 	= "{" . '"' . "user" . '"' . ":1}";
				$group_id 		= 3;
			break;
			case 5: // finance
				$permissions 	= "{" . '"' . "finance" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
				$group_id 		= 4;				
			break;
			case 6: // admin
				$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
				$group_id 		= 1;
			break;
			case 99: // superadmin
				$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1, ". '"' . "finance" . '"' . ":1}";
				$group_id 		= 5;
			break;			
	
			break;
		}

		$values = array(
			$row['userEmail'],
			'12345',
			$permissions,
			$row['userProfileFullName'],
			$row['userProfileLastName'] ? $row['userProfileLastName'] = $row['userProfileLastName'] : $row['userProfileLastName'] = '',
			$row['userIC'],
			$row['siteID'],
			$row['userID'] // we dont have username
			);

		foreach(range(0,7) as $no)
			$statement->bindValue($no+1, $values[$no]);

		if(!$statement->execute())
		{
			var_dump($values);
			var_dump($statement);
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
		//var_dump("update here");
		//die;
		// update.. maybe later.
		$statement = $pdo->prepare("UPDATE users SET email = ?, permissions = ?, first_name = ?,last_name = ?, updated_at = NOW(), employee_num = ?, location_id = ? WHERE username = ?");

		
		//$statement_group = $pdo->prepare("UPDATE users_group SET user_id = ? , group_id = ?");

		// need to figure out the permission based on level
		$level = $row['userLevel'];

		switch($level)
		{
			case 2: // sitemanager
				$permissions 	= "{" . '"' . "user" . '"' . ":1}";
				$group_id 		= 3;
			break;
			case 5: // finance
				$permissions 	= "{" . '"' . "finance" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
				$group_id 		= 4;				
			break;
			case 6: // admin
				$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1}";
				$group_id 		= 1;
			break;
			case 99: // superadmin
				$permissions 	= "{" . '"' . "admin" . '"' . ":1, ". '"' . "reports" . '"' . ":1, ". '"' . "finance" . '"' . ":1}";
				$group_id 		= 5;
			break;			
	
			break;
		}

		$values = array(
			$row['userEmail'],
			$permissions,
			$row['userProfileFullName'],
			$row['userProfileLastName'],
			$row['userIC'],
			$row['siteID'],
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
	}//else
});

return $sso;


?>