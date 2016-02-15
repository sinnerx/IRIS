<?php
namespace model\log;
use db;
class Login
{
	public function createLog($userID)
	{
		db::insert("log_login",Array(
						"userID"=>$userID,
						"logLoginCreatedDate"=>now(),
						"logLoginIP"=>$_SERVER['REMOTE_ADDR']
									));
	}
}

?>