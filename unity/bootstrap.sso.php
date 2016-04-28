<?php

// file ni diguna (shared) oleh mana2 app yang akan guna sso
require_once __DIR__.'/Sso.php';

$sso = new \Iris\Sso;

$sso->onUserLogin(function($row)
{
	$level = $row['userLevel'];

	if(in_array($level, array(2, 5)))
		return;

	// aveo db config.
	// need to change to environmental based later.
	$config = array(
		'host' => 'localhost',
		'name' => 'snipeit',
		'user' => 'root',
		'pass' => 'root'
		);

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
		$statement = $pdo->prepare("INSERT INTO users (email, password, permissions, activated, first_name, created_at, employee_num, /*location_id,*/ dept_id, username)
                VALUES (?, ?, ?, 1, ?, NOW(), ?, 1, ?)");

		// need to figure out the permission based on level
		$level = $row['userLevel'];

		switch($level)
		{
			case 2: // sitemanager
				$permissions = "{" . '"' . "user" . '"' . ":1}";
			break;
			case 5: // finance

			break;
		}

		$values = array(
			$row['userEmail'],
			'',
			$permissions,
			$row['userProfileFullName'],
			$row['userIC'],
			$row['userEmail'] // we dont have username
			);

		foreach(range(0,5) as $no)
			$statement->bindValue($no+1, $values[$no]);

		if(!$statement->execute())
		{
			var_dump($statement->errorInfo());
			die;
		}


	}
	else
	{
		// update.. maybe later.
	}
});

return $sso;


?>