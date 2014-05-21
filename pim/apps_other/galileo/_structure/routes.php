<?php
$routes[]	= Array("schema","controller=monitor@schema");
$routes[]	= Array("update","controller=monitor@update");
$routes[]	= Array(function()
{
	error::show();
});
return $routes;

?>