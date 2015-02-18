<?php

### Error page ###
$routes[]	= Array("404",function()
{
	require_once apps::$root."apps/404.php";
});


$routes[]	= Array(function()
{
	## Require alias helper.
	library::_require("alias");


	# # # # # Hook controller # # # # # # # # # #
	### Hook frontend pre controller with auth controller.
	controller::hook("frontend:pre_controller",function()
	{
		## set default template to default_facade.
		template::set(session::has("template_frontend")?session::get("template_frontend"):"default_skmm");
		controller::load("auth","index");
	});

	### Hook API pre controller with same auth controller of frontend one.
	controller::hook("api:pre_controller",function()
	{
		controller::load("frontend:auth","index");
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



### API Routes ####
// $routes['api-image-avatar']	= Array("api/photo/avatar/[**:photo-name]","controller=api:image@get","{photo-name},avatar");
$routes[]	= Array("api/photo/[:size]/[:year]/[:month]/[:day]/[:photo-name]","controller=api:image@get","{year},{month},{day},{photo-name},{size}");
$routes[]	= Array("api/photo/[:year]/[:month]/[:day]/[:photo-name]/[:size]","controller=api:image@get","{year},{month},{day},{photo-name},{size}");
$routes[]	= Array("api/photo/[:photo-name]/[:size]","controller=api:image@get","{photo-name},{size}"); ## page photo api.
$routes['api-redirect-fb-proxified']	= Array('api/redirect-fb', 'controller=api:redirect@fbProxified');
$routes['api-redirect-useractivity']	= Array("api/redirect/user-activity/[:type]/[:userActivityID]","controller=api:redirect@userActivity","{type},{userActivityID}");
$routes['api-redirect-link']			= Array("api/redirect/link","controller=api:redirect@link");
$routes['api-redirect-general']			= Array("api/redirect/[:type]","controller=api:redirect@general","{type}");
$routes['api-download-ga'] = Array('api/downloadga', 'controller=api:cronjob@downloadga');
$routes['api-notification'] = Array('api/blastnewsletter', 'controller=api:cronjob@blastNewsletter');

### Frontend Route ####

## root. will direct to main.
$routes[]	= Array("","controller=main@landing");
$routes[]	= Array("mengenai-kami","controller=main@landing_about");
$routes[]	= Array("hubungi-kami","controller=main@landing_contact");

### SKMM Temporary route ###
### Hook frontend pre controller with auth controller.
if(!session::has("template_frontend")):
	controller::hook("skmm:pre_controller",function()
	{
		## set default template to default_facade.
		controller::load("frontend:auth","index");
	});

	### Hook pre_template (frontend) with index
	controller::hook("skmm:pre_template",function()
	{
		return controller::load("frontend:partial","index");
	});

	## site landing page
	$routes[]	= Array("[:site-slug]/blog","controller=skmm:main@blog_latest");
	$routes['rss-blog']	= Array("[:site-slug]/blog/rss/[:category?]","controller=blog@rss","{category}");
	$routes[]	= Array("[:site-slug]/blog/[:id]","controller=skmm:main@blog","{id}");
	$routes['skmm-article-view']	= Array("[:site-slug]/blog/[:year]/[:month]/[:article-slug]","controller=skmm:main@blogSlug","{year},{month},{article-slug}");
	$routes[]	= Array("[:site-slug]","controller=skmm:main@index");
	$routes[] 	= Array("[:site-slug]/mengenai-kami","controller=skmm:main@about");
	$routes[]	= Array("[:site-slug]/aktiviti","controller=skmm:main@activity");
	$routes[]	= Array("[:site-slug]/galeri","controller=skmm:main@gallery");
	$routes[]	= Array("[:site-slug]/faq","controller=skmm:main@faq");
	$routes[]	= Array("[:site-slug]/hubungi-kami","controller=skmm:main@contact");
	### SKMM Temporary route ends
endif;

## Rss
$routes['rss-blog']	= Array("[:site-slug]/blog/rss/[:category?]","controller=blog@rss","{category}");

## error page
$routes['frontend-error']	= Array("[:site-slug]/404","controller=error@index");

## site test uri
$routes[]	= Array("[:site-slug]/test/[:controller]/[**:method]","controller={controller}@{@method}");

## site landing page
$routes['main-index']	= Array("[:site-slug]","controller=main@index");

## site registration
$routes[]	= Array("[:site-slug]/registration","controller=main@registration");

## site login and logout
$routes[]			= Array("[:site-slug]/login","controller=main@login");
$routes['logout']	= Array("[:site-slug]/logout","controller=main@logout");

## site activity
#$routes[]	= Array("[:site-slug]/activity","controller=activity@index");
$routes[]	= Array("[:site-slug]/aktiviti/[:year?]/[:month?]","controller=activity@index");
$routes[]	= Array("[:site-slug]/aktiviti/[:year]/[:month]/[:activity-slug]","controller=activity@view/{activity-slug}");

## old routes for activity.
$routes[]	= Array("[:site-slug]/activity/[:year?]/[:month?]","controller=activity@index");
$routes['activity-view']	= Array("[:site-slug]/activity/[:year]/[:month]/[:activity-slug]","controller=activity@view/{activity-slug}");
## /old routes

## site contact-us
$routes['main-contact']	= Array("[:site-slug]/hubungi-kami","controller=main@contact");

## Files
$routes['file-index']	= Array("[:site-slug]/fail","controller=file@index");
$routes['file-view']	= Array("[:site-slug]/fail","controller=file@view");

## site blog [articleList]
$routes[]	= Array("[:site-slug]/blog","controller=blog@article");
$routes[]	= Array("[:site-slug]/blog/user/[i:userID]","controller=blog@articleByUser","{userID}");
$routes[]	= Array("[:site-slug]/blog/[i:year]/[i:month?]","controller=blog@articleByYearOrMonth","{year},{month}");
$routes[]	= Array("[:site-slug]/blog/tag/[:tag]","controller=blog@articleByTagOrCategory","tag,{tag}");
$routes[]	= Array("[:site-slug]/blog/kategori/[:category]","controller=blog@articleByTagOrCategory","category,{category}");

## site comments
$routes[]	= Array("[:site-slug]/comment/addComment","controller=comment@addComment");
$routes[]	= Array("[:site-slug]/comment/disableComment/[:commentID]","controller=comment@disableComment","{commentID}");
$routes[]	= Array("[:site-slug]/comment/getComment/[:commentID]","controller=comment@getComment","{commentID}");
$routes[]	= Array("[:site-slug]/comment/getComments/[:refID]/[:type]","controller=comment@getComments","{refID},{type}");

## site view an article
$routes['article-view']	= Array("[:site-slug]/blog/[:year]/[:month]/[:article-slug]","controller=blog@view");

## site members
$routes[]	= Array("[:site-slug]/members","controller=member@index");

## +++ profile.
$routes[]	= Array("[:site-slug]/ahli","controller=member@profile_directory");
$routes[]	= Array("[:site-slug]/profile/getUserList/[:type]","controller=member@getUserList","{type}");
$routes[]	= Array("[:site-slug]/profile/edit","controller=member@profile_edit");
$routes[]	= Array("[:site-slug]/profile/uploadAvatar","controller=member@profileUploadAvatar");
$routes['profile']	= Array("[:site-slug]/profile/[i:userID?]","controller=member@profile","{userID}");

## ajax request.
$routes[]	= Array("[:site-slug]/ajax/[:controller]/[**:method]","controller=ajax/ajax_{controller}@{method}");

## gallery
$routes['gallery-year']		= Array("[:site-slug]/galeri/[:year?]","controller=gallery@index");
$routes['gallery-month']	= Array("[:site-slug]/galeri/[:year]/[:month]","controller=gallery@index_month");
$routes['gallery-view']		= Array("[:site-slug]/galeri/[:year]/[:month]/id/[i:siteAlbumID]","controller=gallery@albumView","{siteAlbumID}");
$routes['gallery-view2']	= Array("[:site-slug]/galeri/[:year]/[:month]/[:sitealbum-slug]","controller=gallery@albumView","{sitealbum-slug},{year},{month}");

## forum
$routes['forum-index']			= Array("[:site-slug]/forum","controller=forum@index");
$routes['forum-new-thread1']	= Array("[:site-slug]/forum/topik-baru","controller=forum@newThread");
$routes['forum-threads']		= Array("[:site-slug]/forum/[:category-slug]","controller=forum@threadList","{category-slug}");
$routes['forum-new-thread2']	= Array("[:site-slug]/forum/[:category-slug]/topik-baru","controller=forum@newThread","{category-slug}");
$routes['forum-thread']			= Array("[:site-slug]/forum/[:category-slug]/[:thread-id]","controller=forum@viewThread","{category-slug},{thread-id}");


## faq
$routes['main-faq']			= Array("[:site-slug]/soalan-lazim","controller=main@faq");

## search
$routes['search']			= Array("[:site-slug]/carian/[:keyword?]","controller=main@search","{keyword}");

## video gallery
$routes[] = Array("[:site-slug]/video","controller=video@album"); 
$routes['video-album-view'] = Array("[:site-slug]/video/[:video-slug]","controller=video@video_gallery","{video-slug}"); 

## site page.
$routes[]	= Array("[:site-slug]/[**:trail]","controller=page@index");
########################

return $routes;

?>