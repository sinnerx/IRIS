<?php
namespace model\log;
use db, session;

class Error
{
	public function createLog($message)
	{
		db::insert("log_error",Array(
						"logErrorMessage"=>$message,
						"logErrorCreatedDate"=>now(),
						"logErrorCreatedUser"=>session::get("userID"),
						"logErrorStatus"=>0
									));
	}
}