<?php

// file ni diguna (shared) oleh mana2 app yang akan guna sso
require_once __DIR__.'/Unity.php';

$unity = new \Iris\Unity;

$unity->onUserLogin(function($row, $password = '') use($unity)
{
	$unity->getAveo()->userLoginSyncronize($row, $password);
});

return $unity;

