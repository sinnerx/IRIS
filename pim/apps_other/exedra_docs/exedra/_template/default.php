<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo url::asset("exedra_docs/fonts/opensans.css");?>">
	<style type="text/css">
	*
	{
		font-family: "Open Sans";
		font-weight: normal;
	}

	@font-face {
	  font-family: 'Open Sans';
	  font-style: normal;
	  font-weight: 300;
	  src: local('Open Sans'), local('OpenSans-Light'), url(opensans.woff) format('woff');
	}

	.wrapper
	{
		font-size: 0.9em;
		width:900px;
		margin: auto;
		text-align: justify;
		letter-spacing: 1px;
	}

	.footer
	{
		opacity: 0.8;
	}

	.content_status
	{
		position: relative;
		top:45px;
		left:56px;
		letter-spacing: 5px;
		color: #bcbcbc;
	}

	.content_title
	{
		font-size:100px;
		letter-spacing: 5px;
	}

	.components-list
	{
		margin-top: 0px;
		margin-bottom: 0px;
	}

	.components-list label
	{
		display: none;
	}

	.components-list li
	{
		border-bottom: 1px solid white;
		border-top:1px solid white;
		padding:3px 0 3px 0;
		cursor: pointer;
	}
	.components-list li:hover label
	{
		display: inline;
		cursor: pointer;
	}

	.components-list li:hover
	{
		border-top:1px solid #eaeaea;
		border-bottom: 1px solid #eaeaea;
		padding:3px;
	}

	.components-list li a
	{
		text-decoration: none;
		color: #353535;
	}

	.components-list li:last-child:hover
	{
		border-bottom:1px solid white;
	}

	.components-list li:first-child:hover
	{
		border-top:1px solid white;
	}

	.components
	{
		margin-top:5px;
		margin-bottom: 5px;
		border-top:1px solid #e5e5e5;
		border-bottom: 1px solid #e5e5e5;
	}

	.components-tabs
	{
		height:20px;
		padding:4px;
	}
	.components-tabs div
	{
		float:left;
		border-right: 1px solid #e5e5e5;
		padding:0 10px 0 10px;
		cursor: pointer;
	}

	.components-tabs .active
	{
		font-weight: bold;
	}

	.components-list
	{
		display: none;
	}

	.components-list.active
	{
		display: block;
	}

	</style>
	<script type="text/javascript" src='<?php echo url::asset('scripts/jquery-min.js');?>'></script>
	<script type="text/javascript">

	var tabs	= function(tabs)
	{
		this.show	= function(name)
		{
			this.reset();

			//highlight tabs.
			$("#components-tabs-"+name).addClass("active");

			//show components
			$("#components-"+name).addClass("active");
		}

		this.reset	= function()
		{
			$(".components-tabs div, .components-list").removeClass("active");
		}
	}
	var tabs	= new tabs();

	$(document).ready(function()
	{
		tabs.show('mvc');
	})
	
	</script>
</head>
<body>
	<div class='wrapper'>
	<div class='content_status'>Coming&nbsp;&nbsp;&nbsp;soon</div>
	<div class='content_title'><a style='color:inherit;text-decoration:none;' href='<?php echo url::base();?>'>Ex&#233;dra</a></div>
		<div class='content_info'>
			<?php template::showContent();?>
		<div class='footer'>
		Copyright by eimihar.rosengate; with MIT License.
		</div>
	</div>
	</div>
</body>
</html>