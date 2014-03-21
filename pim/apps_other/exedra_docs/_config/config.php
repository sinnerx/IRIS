<?php
$config	= Array();
$config['current_env']	= "dev";
$config['default_apps']	= "exedra";

### domain based configuration :
$config['domain']['localhost'][]	= Array(
						"base_url:default"=>"localhost/rg_frame",
						"base_url:exedra"=>"localhost/rg_frame/exedrasd",
						"asset_url"=>"localhost/rg_frame/assets"
										);

$config['domain']['exedra.rosengate.com'][]	= Array(
						"base_url:default"=>"exedra.rosengate.com",
						"base_url:exedra"=>"exedra.rosengate.com/site",
						"asset_url"=>"exedra.rosengate.com/assets"
										);


return $config;
?>