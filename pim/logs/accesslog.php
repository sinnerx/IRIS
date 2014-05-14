<?php
## just to get every application access.
$endtime	= microtime(true)-$GLOBALS['access_time'];
file_put_contents("logs/access.txt",$_SERVER['REMOTE_ADDR']."@$endtime@".date("Y-m-d H:i:s")."@".$_SERVER['REQUEST_URI']."\n",FILE_APPEND);
?>