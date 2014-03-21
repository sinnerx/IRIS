<?php

### Error page ###
$routes[]	= Array("404",function()
{
	require_once "apps/404.php";
});


$routes[]	= Array(function()
{
	### Hook frontend pre controller with auth controller.
	controller::hook("frontend:pre_controller",function()
	{
		controller::load("auth","index");
	});

	### Hook backend pre controller with backend:auth controller.
	controller::hook("backend:pre_controller",function()
	{
		controller::load("auth","index");
	});
});

#### Backend Route ####
## backend login.
$routes[]	= Array("dashboard/login","controller=backend:auth@login");
$routes[]	= Array("dashboard","controller=backend:home@index");
$routes[]	= Array("dashboard/[:controller]/[**:method]","controller=backend:{controller}@{method}");
#######################

### Frontend Route ####
## root. will direct to main.
$routes[]	= Array("","controller=main@landing");

## site test uri
$routes[]	= Array("[:site-slug]/test/[:controller]/[**:method]","controller={controller}@{@method}");

## site landing page
$routes[]	= Array("[:site-slug]","controller=main@index");

## site registration
$routes[]	= Array("[:site-slug]/registration","controller=main@registration");

## site login
$routes[]	= Array("[:site-slug]/login","controller=main@login");

## site activity
$routes[]	= Array("[:site-slug]/activity","controller=activity@index");

## site contact-us
$routes[]	= Array("[:site-slug]/contact-us","controller=main@contact");

## site members
$routes[]	= Array("[:site-slug]/members","controller=member/index");

## site page.
$routes[]	= Array("[:site-slug]/[**:trail]","controller=page@index");
########################

return $routes;

?>