<?php

$config['default_apps']	= "default";
$config['domain']['localhost'][]	= Array(
					"asset_url"=>"localhost/digitalgaia/iris/pim/assets"
								);

$config['domain']['p1m.gaia.my'][]	= Array(
					"asset_url"=>"p1m.gaia.my/assets"
								);

$config['domain']['celcom1cbc.com'][]	= Array(
					"asset_url"=>"celcom1cbc.com/sta/assets"
								);

$config['domain']['dev.celcom1cbc.com'][]	= Array(
					"asset_url"=>"pi1m.celcom1cbc.com/dev/pim/assets"
								);

return $config;

?>