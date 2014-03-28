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

	### Hook pre_template with partial index.
	controller::hook("backend:pre_template",function()
	{
		return controller::load("template","index");
	});
});

#### Backend Route ####
## backend ajax
$routes[]	= Array("dashboard/ajax/[:controller]/[**:method]","controller=backend:ajax/{controller}@{method}");

## route to direct model usage.
$routes[]	= Array("model/[:model]/[**:method]","controller=model:{model}@{method}");

## backend login.
$routes[]	= Array("dashboard/login","controller=backend:auth@login");
$routes[]	= Array("dashboard","controller=backend:home@index");
$routes[]	= Array("dashboard/[:controller]/[**:method]","controller=backend:{controller}@{method}");

#######################

### Frontend Route ####
## root. will direct to main.
$routes[]	= Array("","controller=main@landing");
$routes[]	= Array("about","controller=main@landing_about");
$routes[]	= Array("contact","controller=main@landing_contact");

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

## ajax request.
$routes[]	= Array("[:site-slug]/ajax/[:controller]/[**:method]","controller=ajax/{controller}@{method}");

## site page.
$routes[]	= Array("[:site-slug]/[**:trail]","controller=page@index");
########################

return $routes;

?>