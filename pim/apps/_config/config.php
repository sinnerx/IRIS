<?php
$config	= Array();
$config['current_env']	= "dev";
$config['default_apps']	= "frontend";
$config['model_namespace']	= true;
$config['frontend_template_default']	= "default_facade";

### domain based configuration :
$config['domain']['localhost'][]	= Array(
						"env"=>"dev",
						"base_url:backend"=>"localhost/digitalgaia/iris/dashboard",
						"base_url:frontend"=>"localhost/digitalgaia/iris",
						"asset_url"=>"localhost/digitalgaia/iris/pim/assets",
						"supportEmail"=>"newrehmi@gmail.com"
										);

### domain based configuration :
$config['domain'][LOCALHOST][]	= Array(
						"env"=>"dev",
						"base_url:backend"=>LOCALHOST."/digitalgaia/iris/dashboard",
						"base_url:frontend"=>LOCALHOST."/digitalgaia/iris",
						"asset_url"=>LOCALHOST."/digitalgaia/iris/pim/assets",
						"supportEmail"=>"newrehmi@gmail.com"
										);

## live dev
$config['domain']['dev.celcom1cbc.com'][]	= Array(
						"env"=>"dev",
						"base_url:backend"=>"dev.celcom1cbc.com/dashboard",
						"base_url:frontend"=>"dev.celcom1cbc.com",
						"asset_url"=>"dev.celcom1cbc.com/pim/assets",
						"supportEmail"=>"newrehmi@gmail.com",
						"fbAppID"=>"744420762279421",
						"fbAppSecret"=>"e0b70e1cc90d13d07f35ccba38d70be6",
										);

## live pro.
$config['domain']['pro.celcom1cbc.com'][]	= Array(
						"env"=>"pro",
						"base_url:backend"=>"pro.celcom1cbc.com/dashboard",
						"base_url:frontend"=>"pro.celcom1cbc.com",
						"asset_url"=>"pro.celcom1cbc.com/pim/assets",
						"supportEmail"=>"support@celcom1cbc.com",
						"fbAppID"=>"744420335612797",
						"fbAppSecret"=>"3b5bfde7d74275484f11ecc974818548",
										);

## live production new url.
$config['domain']['celcom1cbc.com'][]	= Array(
						"env"=>"pro",
						"base_url:backend"=>"celcom1cbc.com/dashboard",
						"base_url:frontend"=>"celcom1cbc.com",
						"asset_url"=>"celcom1cbc.com/pim/assets",
						"supportEmail"=>"support@celcom1cbc.com",
						"fbAppID"=>"744420335612797",
						"fbAppSecret"=>"3b5bfde7d74275484f11ecc974818548",
										);

$config['domain']['www.celcom1cbc.com'][]	= Array(
						"env"=>"pro",
						"base_url:backend"=>"www.celcom1cbc.com/dashboard",
						"base_url:frontend"=>"www.celcom1cbc.com",
						"asset_url"=>"www.celcom1cbc.com/pim/assets",
						"supportEmail"=>"support@celcom1cbc.com",
						"fbAppID"=>"744420335612797",
						"fbAppSecret"=>"3b5bfde7d74275484f11ecc974818548",
										);

## no longer used.
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