<?php
## access log micro time.
#$GLOBALS['access_time']	= microtime(true);
define("ROOT_FOLDER", "pim");
require_once __DIR__ . '/pim/vendor/autoload.php';
require_once "pim/core/routing.php";

## log access for speed analysing purpose.
#require_once "logs/accesslog.php";

?>