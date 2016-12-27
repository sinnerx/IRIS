<?php
$config	= Array();
$config['current_env']	= "dev";
$config['default_apps']	= "frontend";
$config['model_namespace']	= true;
$config['frontend_template_default']	= "default_facade";

### domain based configuration :
$config['domain']['localhost'][]	= Array(
						"env"=>"dev",
						"base_url:backend"=>"localhost/irix/dashboard",
						"base_url:frontend"=>"localhost/irix",
						"asset_url"=>"localhost/irix/pim/assets",
						"supportEmail"=>"newrehmi@gmail.com",
						"fbAppID"=>"772269416161222",
						"fbAppSecret"=>"d404c0343d16f5b70c22d120d1ab94c8",
						"aveo"=>"localhost/snipeit/app/controllers/api/user.php",
										);

### domain based configuration :
$config['domain'][LOCALHOST][]	= Array(
						"env"=>"dev",
						"base_url:backend"=>LOCALHOST."/irix/dashboard",
						"base_url:frontend"=>LOCALHOST."/irix",
						"asset_url"=>LOCALHOST."/irix/pim/assets",
						"supportEmail"=>"newrehmi@gmail.com",
						"fbAppID"=>"772269416161222",
						"fbAppSecret"=>"d404c0343d16f5b70c22d120d1ab94c8",
						"aveo"=> LOCALHOST . "/snipeit/app/controllers/api/user.php",
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
						"aveo"=> "dev.celcom1cbc.com/aveo/app/controllers/api/user.php",
						'secure' => true
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
						"aveo"=> "pro.celcom1cbc.com/app/controllers/api/user.php",
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
						"aveo"=> "celcom1cbc.com/aveo/app/controllers/api/user.php",
										);

$config['domain']['www.celcom1cbc.com'][]	= Array(
						"env"=>"pro",
						"base_url:backend"=>"www.celcom1cbc.com/dashboard",
						"base_url:frontend"=>"www.celcom1cbc.com",
						"asset_url"=>"www.celcom1cbc.com/pim/assets",
						"supportEmail"=>"support@celcom1cbc.com",
						"fbAppID"=>"744420335612797",
						"fbAppSecret"=>"3b5bfde7d74275484f11ecc974818548",
						"aveo"=> "www.celcom1cbc.com/aveo/app/controllers/api/user.php",
						'secure' => true
										);

## no longer used.
<<<<<<< HEAD
$config['domain']['p1m.gaia.my'][]	= Array(
						"base_url:backend"=>"calent.gaia.my/dashboard",
						"base_url:frontend"=>"calent.gaia.my",
						"asset_url"=>"p1m.gaia.my/assets"
=======
$config['domain']['calent.gaia.my'][]	= Array(
						"base_url:backend"=>"calent.gaia.my/dashboard",
						"base_url:frontend"=>"calent.gaia.my",
						"asset_url"=>"calent.gaia.my/assets"
>>>>>>> d0dc45820c6e15278b0e0a6e146f869a71265117
										);

/*$config['domain']['celcom1cbc.com'][]	= Array(
						"base_url:backend"=>"celcom1cbc.com/sta/dashboard",
						"base_url:frontend"=>"celcom1cbc.com/sta",
						"asset_url"=>"celcom1cbc.com/sta/assets"
												);*/

return $config;
?>