<?php
## access log micro time.
#$GLOBALS['access_time']	= microtime(true);
setlocale(LC_ALL, "ms");
define("ROOT_FOLDER", "");
define("LOCALHOST",$_SERVER['SERVER_ADDR']);
require_once __DIR__ . '/vendor/autoload.php';
require_once "core/routing.php";

## log access for speed analysing purpose.
#require_once "logs/accesslog.php";

?>