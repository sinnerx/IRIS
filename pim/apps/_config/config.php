<?php
$config	= Array();
$config['current_env']	= "dev";
$config['default_apps']	= "frontend";
$config['model_namespace']	= true;

### domain based configuration :
$config['domain']['localhost'][]	= Array(
						"base_url:backend"=>"localhost/digitalgaia/iris/dashboard",
						"base_url:frontend"=>"localhost/digitalgaia/iris",
						"asset_url"=>"localhost/digitalgaia/iris/pim/assets"
										);

## live dev
$config['domain']['dev.celcom1cbc.com'][]	= Array(
						"base_url:backend"=>"dev.celcom1cbc.com/dashboard",
						"base_url:frontend"=>"dev.celcom1cbc.com",
						"asset_url"=>"dev.celcom1cbc.com/pim/assets"
										);

## live pro.
$config['domain']['pro.celcom1cbc.com'][]	= Array(
						"base_url:backend"=>"pro.celcom1cbc.com/dashboard",
						"base_url:frontend"=>"pro.celcom1cbc.com",
						"asset_url"=>"pro.celcom1cbc.com/pim/assets"
										);

## live production new url.
$config['domain']['celcom1cbc.com'][]	= Array(
						"base_url:backend"=>"celcom1cbc.com/dashboard",
						"base_url:frontend"=>"celcom1cbc.com",
						"asset_url"=>"celcom1cbc.com/pim/assets"
										);

$config['domain']['p1m.gaia.my'][]	= Array(
						"base_url:backend"=>"p1m.gaia.my/dashboard",
						"base_url:frontend"=>"p1m.gaia.my",
						"asset_url"=>"p1m.gaia.my/assets"
										);

/*$config['domain']['celcom1cbc.com'][]	= Array(
						"base_url:backend"=>"celcom1cbc.com/sta/dashboard",
						"base_url:frontend"=>"celcom1cbc.com/sta",
						"asset_url"=>"celcom1cbc.com/sta/assets"
												);*/

return $config;
?>