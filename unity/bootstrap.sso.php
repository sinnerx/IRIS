<?php

require_once __DIR__.'/Sso.php';

$sso = new \Iris\Sso;

$sso->onUserLogin(function($row)
{
	
});

return $sso;


?>