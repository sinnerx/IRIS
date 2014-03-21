<?php
$routes[]	= Array("schema","controller=monitor@schema");
$routes[]	= Array(function()
{
	error::show();
});
return $routes;

?>