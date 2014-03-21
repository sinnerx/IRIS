<?php
$config = Array();
## Database
$config['domain']['localhost'][]	= Array(
				"db_host"=>"localhost",
				"db_name"=>"digitalgaia_iris",
				"db_user"=>"root",
				"db_pass"=>""
						);

$config['domain']['p1m.gaia.my'][]	= Array(
				"db_host"=>"localhost",
				"db_name"=>"p1mgaia_iris",
				"db_user"=>"p1mgaia_iris",
				"db_pass"=>"gaiacelcom12345%"
						);

return $config;
?>