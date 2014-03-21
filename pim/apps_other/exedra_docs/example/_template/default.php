<style type="text/css">
*
{
	font-family: "tahoma";
	letter-spacing: 1px;
}
.info
{
	padding:5px;
}
#top_menu
{
	background: #f8cbcb;
	padding:5px;
	margin-bottom: 5px;
}

#left_menu
{
	background: #d1f1da;
	padding:5px;
	margin-bottom: 5px;
}

#footer
{
	background: #dfe3e2;
	padding:5px;
}

#content
{
	padding:5px;
}

#main_content
{
	border:1px solid #dcdcdc;
	padding:5px;
	margin-bottom: 5px;
}

#content
{
	background: #e0fad8;
}

</style>
<div id='wrapper'>
	<div class='info'>
	This is a Templating example about how this nice things is working.
	</div>
	<div id='top_menu'><?php controller::load("menu","top");?></div>
	<div id='left_menu'><?php controller::load("menu","left");?></div>
	<div id='main_content'>
	Main Content :
	<div id='content'><?php template::showContent();?></div>
	</div>
	<div id='footer'><?php controller::load("menu","footerMessage");?></div>
	<div class='info'>
	As you can see, many controller is loaded and fit in one template. Which explains the concept HMVC, or Nested MVC. Which give you a modular control of your own template.
	</div>
</div>