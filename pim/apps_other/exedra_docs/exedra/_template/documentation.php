<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src='<?php echo url::asset("scripts/jquery-min.js");?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("exedra_docs/fonts/opensans.css");?>">
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("exedra_docs/highlight-js/styles/zenburn.css");?>">
<script type="text/javascript" src='<?php echo url::asset("exedra_docs/highlight-js/highlight.pack.js");?>'></script>
<script>hljs.initHighlightingOnLoad();</script>
<script type="text/javascript">
	
$(document).ready(function()
{
	$("code").each(function(i,e)
	{
		this.innerHTML	= this.innerHTML.trim();
	});
});

</script>

<title>Exedra Documentation</title>
	<style type="text/css">
	*
	{
		letter-spacing: 1px;
		font-family: "Open Sans";
	}

	body
	{
		padding:0px;
		margin:0px;
		background: #f5f5f5;
		font-size:0.8em;
	}
	#wrapper-header
	{
		background: #484848;
		color: white;
		font-size:30px;
		padding:5px;
	}

		#wrapper-header a
		{
			text-decoration: none;
			color:white;
			font-weight: bold;
		}

	#wrapper-body, #wrapper-header > div
	{
		width:90%;
		margin: auto;
	}

	#wrapper-body
	{
	}

	#wrapper-leftmenu
	{
		width:20%;
		/*background: blue;*/
		float:left;
		background: white;
	}

	#wrapper-leftmenu > div
	{
		padding:5px;
		padding-bottom: 20px;
		border-right:1px solid #e4e4e4;
		border-bottom:1px solid #e4e4e4;
		border-left:1px solid #e4e4e4;
	}

	

	.wrapper-leftmenu-title
	{
		border-bottom:1px solid #cecece;
		padding:5px;
		margin-top:5px;
	}

		.wrapper-leftmenu-title a
		{
			display:block;
			text-decoration: none;
			color: inherit;
		}

	.wrapper-leftmenu-content ul
	{
		margin: 5px 0 0 0;
		padding:0px 0 0 25px;
	}

	.wrapper-leftmenu-content a
	{
		display: block;
		border-bottom: 1px solid #e2e2e2;
		text-decoration: none;
		padding:1px 0 0 5px;
		color: #3333ff;
	}

	#wrapper-content
	{
		width:75%;
		/*background: green;*/
		margin-left:20%;
	}

	#wrapper-content > div
	{
		padding:10px;
		min-height: 500px;
	}

	.wrapper-content-subject
	{
		font-size:40px;
		font-weight: bold;
		border-bottom: 1px solid #dfdfdf;
		padding-left:5px;
	}

	.wrapper-content-topic
	{
		padding-left:5px;
		font-size:20px;
	}
	.wrapper-content-main
	{
		margin-top:10px;
		padding-left:5px;
	}

	.wrapper-content-main .text
	{
		margin-bottom:40px;
		background: white;
		padding:5px;
		border:1px solid #dadada;
		position: relative;
	}

	.wrapper-content-main .text-title
	{
		font-size:20px;
		background: white;
		position: absolute;
		top:-30px;
		left:5px;
		font-weight: bold;
		border:1px solid #dadada;
		border-bottom:0px;
		display: inline;
		padding:3px 10px 3px 10px;
	}

	.wrapper-content-main .text-subtitle
	{
		font-size:1.2em;
		text-decoration: underline;
	}

	.wrapper-content-main .text pre
	{
		padding:0px;
		margin: 5px 0 5px 0;
	}

	.wrapper-content-main code
	{
		margin:0px;
	}

	</style>
</head>
<body>
<div id='container'>
	<div id='wrapper'>
	<div id='wrapper-header'>
		<div>
			<a href='<?php echo url::base("");?>'>EXEDRA.Documentation</a>
		</div>
	</div>
	<div id='wrapper-body'>
		<div id='wrapper-leftmenu'>
			<?php
			$finishedListR	= 	Array("router","model","view","session","flash","form","url","query");

			$comps	= model::load("components")->lists();
			?>
			<div>
				<?php
				foreach($comps as $name=>$listR):
					list($name,$title)		= explode("|",$name);
					$url	= url::base("docs/$name");
					$subjectR[$name]	= $title;


					echo "<div class='wrapper-leftmenu-title'><a href='$url'>$title</a></div>";
					echo "<div class='wrapper-leftmenu-content'>";
						echo "<ul>";
						foreach($listR as $compName=>$api)
						{
							list($comp,$apis)	= explode(":",$api);
							$url	= url::base("docs/$name/$comp");
							$topicR[$comp]	= $compName;
							$style			= !in_array($comp,$finishedListR)?"style='opacity:0.3;'":"";
							echo "<li><a href='$url' $style>$compName</a></li>";
						}
						echo "</ul>";
					echo "</div>";
				endforeach;
				?>
			</div>
		</div>
		<div id='wrapper-content'>
		<div>
			<div class='wrapper-content-subject'>
				<?php echo $subjectR[request::named("subject")];?>
			</div>
			<div class='wrapper-content-topic'>
				<?php echo $topicR[request::named("topic")];?>
			</div>
			<div class='wrapper-content-main'>
			<?php template::showContent();?>
			</div>
		</div>
		</div>
	</div>
	<div id='wrapper-footer'>

	</div>
	</div>
</div>
</body>
</html>