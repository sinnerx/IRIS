<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("exedra_docs/fonts/opensans.css");?>">
<script type="text/javascript" src='<?php echo url::asset("scripts/jquery-min.js");?>'></script>
<head>
	<title></title>
	<style type="text/css">
	*
	{
		font-family: "Open Sans";
		letter-spacing: 1px;
	}
	html,body,.container
	{
		height:100%;
	}

	body
	{
		padding:0px;
		margin:0px;
		background: #f2f4f8;
		overflow: hidden;
	}

	.container
	{
		display: table;
		float:none;
		height:100%;
		width:100%;
		margin:auto;
		padding:0px;
	}

	/* Header */
	header
	{
		background: white;
		height:60px;
		position: relative;
		box-shadow: 0px 2px 5px #d3d3d3;
	}

		.p1m-title
		{
			font-size:19px;
		}

	/* Body */
	.body-wrapper
	{
		position: absolute;
		top:60px;
		bottom:0px;
		width:100%;
	}

	.body
	{
		display: table;
		height:100%;
		width:100%;
		font-size:0.8em;
	}

	.body-left, .body-main
		{
			display: table-cell;
			float:none;
		}

		/* menu part */
		.body-left
		{
			background: #222222;
			height:100%;
			width:240px;
			position: relative;
		}

		/* main (content) part */
		.body-main
		{
			padding:18px 0 0 15px;
		}

		.left-menu
		{
			position: absolute;
			bottom:0px;
			top:0px;
			width:100%;
		}

		.left-menu ul
		{
			padding:0px;
			margin: 0px;
			list-style: none;
		}

		.left-menu > ul
		{
			margin-top:10px;
		}

		.left-menu li
		{
			cursor: default;
			font-weight: bold;
			margin-bottom:2px;
			cursor: pointer;
		}

		.left-menu a
		{
			text-decoration: none;
			color: inherit;
			display: block;
			padding:5px;
		}

			.left-menu-li:hover
			{
				background: #444444;
			}

		.left-menu-li
		{
			color:#6aa9ae;
			border-bottom: 1px solid #0c0c0c;
			position: relative;
		}

		.left-menu-li.active
		{
			/*background: #009bff;*/
			color: #baf3ec;
			text-shadow:0px 0px 1em #baf3ec,0px 0px 0.5em #baf3ec;
		}

		.left-menu-sub
		{
			background: #000000;
			color:#999999;
			display: none;
		}

			.left-menu-sub li:hover
			{
				color:#e5e5e5;
			}

			.left-menu-sub li.active
			{
				color:#e5e5e5;
				text-shadow:0px 0px 1em #e5e5e5;
			}

		.welcome-box
		{
			position: absolute;
			right:0px;
			top:0px;
			font-size:13px;
			padding:5px;
			opacity: 0.5;
		}

		.main-title
		{
			font-size:23px;
		}

		.body-main
		{
			color:#343536;
		}

		.body-main-wrapper
		{
			position: relative;
		}
	</style>
</head>
<body>
<script type="text/javascript">
	
$(document).ready(function()
{
	$(".left-menu-li").click(function()
	{
		var nextClass	= $(this).next();

		if(nextClass.attr("class") == "left-menu-sub")
		{
			nextClass.slideToggle();
		}
	});
});

</script>
	<div class='container'>
		<header>
			<div class='welcome-box'>
				<div><a href='#'>Ke laman Web</a> | Selamat bertugas, Ahmad Rahimie | <a href='<?php echo url::base("auth/logout");?>'>Keluar!</a></div>
				
			</div>
		</header>
		<div class='body-wrapper'>
			<div class='body'>
				<div class='body-left'>
					<div class='left-profile'>
						
					</div>
					<div class='left-menu'>
						<ul>
							<?php
							$menu	= model::load("access")->menu();
							$controller	= controller::getCurrentController();
							$method		= controller::getCurrentMethod();

							foreach($menu as $menu_name=>$module)
							{
								if(!is_array($module))
								{
									

									list($menu_con,$menu_meth)	= explode("/",$module);

									$active		= $controller == $menu_con && $method == $menu_meth?"active":"";
									$url		= url::base($module);

									echo "<li class='left-menu-li $active'><a href='$url'>$menu_name</a></li>";
								}
								else
								{
									$display	= in_array($controller."/".$method,$module)?"style='display:block;'":"";

									echo "<li class='left-menu-li'><a href='javascript:void(0);'>$menu_name <span style='position:absolute;right:5px;top:3px;'>+</span></a></li>";
									echo "<li class='left-menu-sub' $display><ul>";
									foreach($module as $sub_menuname=>$submod)
									{
										$url	= url::base($submod);
										list($menu_con,$menu_meth)	= explode("/",$submod);

										$active		= $controller == $menu_con && $method == $menu_meth?"active":"";

										echo "<li class='$active'><a href='$url'>$sub_menuname</a></li>";
									}
									echo "</ul></li>";
								}
							}
							?>
							<!-- 
							Example Format :
							<li class='left-menu-li active'>Overview</li>
							<li class='left-menu-li'>Site Information</li>
							<li class='left-menu-sub' style='display:none;'>
								<ul>
									<li>Basic Info</li>
									<li>Contact</li>
								</ul>
							</li>
							<li class='left-menu-li'>Pages</li> -->
						</ul>
					</div>
				</div>
				<!-- Body main -->
				<div class='body-main'>
					<div class='body-main-wrapper'>
					<?php template::showContent();?>
					</div>
				</div>
				<!-- Body main, end -->
			</div>
		</div>
		<div class='footer'>

		</div>
	</div>
</body>
</html>