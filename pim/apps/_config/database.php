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

$config['domain']['celcom1cbc.com'][]	= Array(
				"db_host"=>"localhost",
				"db_name"=>"celcomcb_web2",
				"db_user"=>"celcomcb_fulkrum",
				"db_pass"=>"fulkrum@123"
									);

$config['domain']['dev.celcom1cbc.com'][]	= Array(
				"db_host"=>"localhost",
				"db_name"=>"pi1m_dev_01",
				"db_user"=>"fulkrum",
				"db_pass"=>"fulkrum@123"
									);

return $config;
?>