<?php
## access log micro time.
$GLOBALS['access_time']	= microtime(true);
require_once __DIR__ . '/vendor/autoload.php';
require_once "core/routing.php";

## log access for speed analysing purpose.
require_once "logs/accesslog.php";

?>