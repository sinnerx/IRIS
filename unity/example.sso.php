<?php
/* jangan directly guna code di dalam ni */
/* jangan directly guna code di dalam ni */
/* jangan directly guna code di dalam ni */
// 1. require sso bootstrap file at the very first of aveo
$sso = require_once __DIR__.'/bootstrap.sso.php';

// 2. set database config untuk IRIS (bukan aveo)
// kalau tak nak set config ni pun takpe.
// dekat logUserIn nnti just pass \Pdo instance on 3rd param.
// maybe later aku akan set config ni dkat bootstrap.sso.php. tapi tgk lah, sbb sso ni bukan iris/aveo dependant..
$sso->setConfig(array(
	'host' => 'localhost',
	'user' => 'root',
	'pass' => '',
	'name' => 'database_iris'
	));

// 3. start session
// just call this one at the most top
$sso->startSession();

// 4. $sso->userIsLoggedIn atau \Iris\Sso::isLoggedIn()
// function ni untuk check whether user sudah logged in or not
// guna function ni dkat aveo punya logged in check tu.
if(\Iris\Sso::isLoggedIn())
{

}

// 5. log user in $sso->logUserIn($email, $pass) atau \Iris\Sso::logIn($email, $pass);
// return false or array (false if failed login, array if success. array ialah record user)
// *on every successful login, akan invoke callback di bootstrap.sso.php
// guna function ini masa nak login aveo
\Iris\Sso::logIn($email, $pass); 

// 6. log out $sso->logUserOut() atau \Iris\Sso::logOut();
// logout user
// guna function ni time user dkat aveo nak logout.
\Iris\Sso::logOut();

