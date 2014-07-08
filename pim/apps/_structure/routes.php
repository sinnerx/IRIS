<?php

### Error page ###
$routes[]	= Array("404",function()
{
	require_once "apps/404.php";
});


$routes[]	= Array(function()
{
	## Require alias helper.
	library::_require("alias");


	# # # # # Hook controller # # # # # # # # # #
	### Hook frontend pre controller with auth controller.
	controller::hook("frontend:pre_controller",function()
	{
		controller::load("auth","index");
	});

	### Hook backend pre controller with backend:auth controller.
	controller::hook("backend:pre_controller",function()
	{
		return controller::load("auth","index");
	});

	### Hook backend pre controller with template controller.
	controller::hook("backend:pre_controller",function()
	{
		controller::load("template","formatting");
	});

	### Hook pre_template with partial index.
	controller::hook("backend:pre_template",function()
	{
		return controller::load("template","index");
	});

	### Hook pre_template (frontend) with index
	controller::hook("frontend:pre_template",function()
	{
		return controller::load("partial","index");
	});
});

#### Backend Route ####
$routes[]	= Array("model/[**:model_param?]","controller=tests:model@tests","{model_param}");

## backed ajax : shared
$routes[]	= Array("dashboard/ajax/shared/[:controller]/[**:method]",function()
{
	template::set(false);
	return controller::init("backend:shared/ajax_{controller}","{method}");
});

## backend ajax : role specific.
$routes[]	= Array("dashboard/ajax/[:controller]/[**:method]",function()
{
	template::set(false);

	## get subcontroller name.
	$levelController	= model::load("access/data")->accessController(session::get("userLevel"));

	return controller::init("backend:$levelController/ajax_{controller}","{method}");
});

## token authentication
$routes[]	= Array('dashboard/authenticateToken/[:token]',"controller=backend:auth@authenticateToken","{token}");

## route to direct model usage.
#$routes[]	= Array("model/[:model]/[**:method]","controller=model:{model}@{method}");

## backend login.
/*$routes[]	= Array("dashboard/login",function()
{
	echo 'tst';
});*/
$routes[]	= Array("dashboard/resetPassword","controller=backend:auth@resetPassword");
$routes[]	= Array("dashboard/login","controller=backend:auth@login");		## login
$routes[]	= Array("dashboard/logout","controller=backend:auth@logout");	## logout
$routes[]	= Array("dashboard","controller=backend:auth@login");			## 

## # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
## the main route for backend. basically it route to sub-controller based on level/role.
## unless, it's shared controller stated in access::sharedController
## all the shared controller files, located under top level of controller, else, under role name (as stated in access::accessController.
$routes[]	= Array("dashboard/[:controller]/[**:method]",function($param)
{
	## route to sub-controller based on level.
	$level				= session::get("userLevel");
	$levelController	= model::load("access/data")->accessController($level); ## controller folder name.

	#controller::init("backend:".$param['controller'],$param['method']); ## commented for a while.
	## if the controller is shared, then load the general controller.
	if(model::load("access/data")->sharedController($param['controller'],$param['method'],$level))
	{
		return controller::init("backend:shared/".$param['controller'],$param['method']);
	}
	## else, load the role based controller.
	else
	{
		return controller::init("backend:$levelController/".$param['controller'],$param['method']); ## redirected to subcontroller under roles.
	}
});

#######################

### Frontend Route ####
## root. will direct to main.
$routes[]	= Array("","controller=main@landing");
$routes[]	= Array("mengenai-kami","controller=main@landing_about");
$routes[]	= Array("hubungi-kami","controller=main@landing_contact");

## site test uri
$routes[]	= Array("[:site-slug]/test/[:controller]/[**:method]","controller={controller}@{@method}");

## site landing page
$routes[]	= Array("[:site-slug]","controller=main@index");

## site registration
$routes[]	= Array("[:site-slug]/registration","controller=main@registration");

## site login and logout
$routes[]	= Array("[:site-slug]/login","controller=main@login");
$routes[]	= Array("[:site-slug]/logout","controller=main@logout");

## site activity
#$routes[]	= Array("[:site-slug]/activity","controller=activity@index");
$routes[]	= Array("[:site-slug]/activity/[:year?]/[:month?]","controller=activity@index");
$routes[]	= Array("[:site-slug]/activity/[:year]/[:month]/[:activity-slug]","controller=activity@view/{activity-slug}");

## site contact-us
$routes[]	= Array("[:site-slug]/hubungi-kami","controller=main@contact");

## site blog [articleList]
$routes[]	= Array("[:site-slug]/blog","controller=blog@article");
$routes[]	= Array("[:site-slug]/blog/user/[i:userID]","controller=blog@articleByUser","{userID}");
$routes[]	= Array("[:site-slug]/blog/[i:year]/[i:month?]","controller=blog@articleByYearOrMonth","{year},{month}");
$routes[]	= Array("[:site-slug]/blog/tag/[:tag]","controller=blog@articleByTagOrCategory","tag,{tag}");
$routes[]	= Array("[:site-slug]/blog/category/[:category]","controller=blog@articleByTagOrCategory","category,{category}");

## site view an article
$routes[]	= Array("[:site-slug]/blog/[:year]/[:month]/[:article-slug]","controller=blog@view");

## site members
$routes[]	= Array("[:site-slug]/members","controller=member@index");

## +++ profile.
$routes[]	= Array("[:site-slug]/profile/edit","controller=member@profile_edit");
$routes[]	= Array("[:site-slug]/profile/uploadAvatar","controller=member@profileUploadAvatar");
$routes[]	= Array("[:site-slug]/profile/[i:userID?]","controller=member@profile","{userID}");

## ajax request.
$routes[]	= Array("[:site-slug]/ajax/[:controller]/[**:method]","controller=ajax/ajax_{controller}@{method}");

## gallery
$routes[]	= Array("[:site-slug]/galeri/[:year?]","controller=gallery@index");
$routes[]	= Array("[:site-slug]/galeri/[:year]/[:month]","controller=gallery@index_month");
$routes[]	= Array("[:site-slug]/galeri/[:year]/[:month]/id/[i:siteAlbumID]","controller=gallery@albumView","{siteAlbumID}");
$routes[]	= Array("[:site-slug]/galeri/[:year]/[:month]/[:sitealbum-slug]","controller=gallery@albumView","{sitealbum-slug},{year},{month}");

## site page.
$routes[]	= Array("[:site-slug]/[**:trail]","controller=page@index");
########################

return $routes;

?>