<?php

// file ni diguna (shared) oleh mana2 app yang akan guna sso
require_once __DIR__.'/Sso.php';

$sso = new \Iris\Sso;

$sso->onUserLogin(function($row)
{
	// create new pdo connection here.
	// buat update query here.
});

return $sso;


?>