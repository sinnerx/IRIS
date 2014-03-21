<?php
$routes[]	= Array(function()
	{
		## connection.
		#db::connect(apps::config("db_host"),apps::config("db_user"),apps::config("db_pass"),apps::config("db_name"));
	});
$routes[]	= Array("","controller=main@index");
$routes[]	= Array("docs","controller=documentation@index");
$routes[]	= Array("docs/[:subject]/[:topic?]","controller=documentation@subject","{subject},{topic}");
$routes[]	= Array("[:controller]/[**:method]","controller=exedra:{controller}@{method}");
$routes[]	= Array(function()
{
	error::show();
});
return $routes;
?>