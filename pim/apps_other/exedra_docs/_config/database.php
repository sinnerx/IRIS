<?php
$config = Array();
## Database
$config['domain']['localhost'][]	= Array(
				"db_host"=>"localhost",
				"db_name"=>"test",
				"db_user"=>"root",
				"db_pass"=>""
						);

$config['domain']['exedra.rosengate.com'][]	= Array(
				"db_host"=>"localhost",
				"db_name"=>"test",
				"db_user"=>"root",
				"db_pass"=>""
						);

return $config;
?>