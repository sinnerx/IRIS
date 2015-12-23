<?php
if(file_exists(IRIS_ROOT_DIR.'/localenvdb.php'))
{
	$config['domain']['localhost'][] = require_once IRIS_ROOT_DIR.'/localenvdb.php';
}
else
{
	$config['domain']['localhost'][]	= Array(
	"db_host"=>"localhost",
	"db_user"=>"root",
	"db_pass"=>"",
	"db_name"=>"digitalgaia_iris"
	);
}

$config['domain']['p1m.gaia.my'][]	= Array(
	"db_host"=>"localhost",
	"db_name"=>"p1mgaia_iris",
	"db_user"=>"p1mgaia_iris",
	"db_pass"=>"gaiacelcom12345%"
	);

## no longer used.
/*$config['domain']['celcom1cbc.com'][]	= Array(
	"db_host"=>"localhost",
	"db_name"=>"celcomcb_web2",
	"db_user"=>"celcomcb_fulkrum",
	"db_pass"=>"fulkrum@123"
						);*/

$config['domain']['dev.celcom1cbc.com'][]	= Array(
	"db_host"=>"localhost",
	"db_name"=>"pi1m_dev_01",
	"db_user"=>"fulkrum",
	"db_pass"=>"fulkrum@123"
					);

## production auth.
$config['domain']['celcom1cbc.com'][]	= Array(
				"db_host"=>"ca-dbi-01.cegjaqlxeaxp.ap-southeast-1.rds.amazonaws.com",
				"db_name"=>"pi1m_pro_01",
				"db_user"=>"fulkrum",
				"db_pass"=>"fulkrum@123"
									);

## production pro. (same)
$config['domain']['pro.celcom1cbc.com'][]	= Array(
				"db_host"=>"ca-dbi-01.cegjaqlxeaxp.ap-southeast-1.rds.amazonaws.com",
				"db_name"=>"pi1m_pro_01",
				"db_user"=>"fulkrum",
				"db_pass"=>"fulkrum@123"
									);

$config['domain']['www.celcom1cbc.com'][]	= Array(
				"db_host"=>"ca-dbi-01.cegjaqlxeaxp.ap-southeast-1.rds.amazonaws.com",
				"db_name"=>"pi1m_pro_01",
				"db_user"=>"fulkrum",
				"db_pass"=>"fulkrum@123"
									);

return $config;

?>