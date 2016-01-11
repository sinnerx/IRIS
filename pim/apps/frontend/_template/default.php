<?php #view::render("partial/all_top");?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<!-- Responsive Code -->
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

	<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed:700);
	</style>
	<link href="<?php echo url::asset("frontend/css/styles.css");?>" rel="stylesheet" type="text/css">
	<!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> -->
	<link rel="stylesheet" type="text/css" href="<?php echo url::asset("_scale/css/font-awesome.min.css");?>">
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
	<!-- <script type="text/javascript" src='<?php echo url::asset("_scale/js/jquery.min.js");?>'></script> -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
	<script src="<?php echo url::asset("frontend/js/jquery.ticker.js");?>" type="text/javascript"></script>
	<link href="<?php echo url::asset("_landing/css/jquery.mCustomScrollbar.css");?>" rel="stylesheet" type="text/css" /> <!-- used by partial/top -->
	<script src="<?php echo url::asset("_landing/js/jquery.mCustomScrollbar.concat.min.js");?>"></script> <!-- used by partial/top -->
	<script src="<?php echo url::asset("frontend/js/site.js");?>" type="text/javascript"></script>
	<script type="text/javascript">
		
	jQuery(function() {  
	        jQuery("#accordion").accordion({
				active: false,
			collapsible: true	
			});  
	    });

	</script>

	<!-- Responsive Code -->
	<style type="text/css">

	.area-mobile, .mobile-top, .logo-mobile, .navigation-tablet
	{
		display: none;
	}

	</style>
	<?php if(controller::getCurrentController() == "blog"):?>
	<link rel="stylesheet" href="<?php echo url::asset("frontend/css/blog.css"); ?>" type="text/css" />
	<?php endif;?>
	<link href="<?php echo url::asset("frontend/responsive/css/responsive.css");?>" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo url::asset("frontend/responsive/css/slidebars.css");?>">
	<link rel="stylesheet" href="<?php echo url::asset("frontend/responsive/css/example-styles.css");?>">
	<!------>
</head>
<body>
<div class="mobile-top">
	<div class="mobile-icon-left"></div>
	<div class="mobile-site-name"><?php echo $siteName;?></div>
	<div class="mobile-navigation-button">
	<div class="sb-toggle-right"><i class="fa fa-bars"></i></div>
	</div>
</div>
<div class="area-mobile">
	<?php
	## logged in
	if(!$username):?>
	<div class="mobile-cp">
		<ul>
		<li><a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>">Log Masuk</a></li>
		<li><a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>">Daftar</a></li>
		</ul>
	</div>
	<?php endif;?>
	<div class="area-mobile-wrap">
		<select onchange="window.location.href = '<?php echo url::base();?>/'+this.value;">
			<option selected="selected">Ke Pi1M Lain</option>
			<?php
			foreach($stateR as $stateID => $stateName)
			{
				if($res_site[$stateID]):?>
				<option disabled><?php echo $stateName;?></option>
				<?php foreach($res_site[$stateID] as $row):?>
				<option value='<?php echo $row['siteSlug'];?>'><?php echo $row['siteName'];?></option>
				<?php endforeach;?>
				<?php endif;?>
			<?php
			}
			?>
		</select>
	</div>
</div>
<?php #controller::load("partial","pim_list");?>
<?php
#========================================
# SECTION : Pi1M LIST START
#========================================
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("div.panel_button").click(function(){
		jQuery("div#panel").animate({
			height: "650px"
		})
		.animate({
			height: "570px"
		}, "fast");
		jQuery("div.panel_button").toggle();
	
	});	
	
   jQuery("div#hide_button").click(function(){
		jQuery("div#panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
   
   jQuery("div#hide_button_top").click(function(){
		jQuery("div#panel").animate({
			height: "0px"
		}, "fast");
   });	
});

</script>
<style type="text/css">
	
.panel_button {
	margin-left: auto;
	margin-right: auto;
	position: absolute;

	width: 210px;
	height: 50px;
	
	z-index: 20;
	
	cursor: pointer;
	background:url(<?php echo url::asset("frontend/images/pim_top_open.jpg");?>) no-repeat top center;
}
.panel_button img {
	position: relative;
	top: 10px;
	border: none;
}
#show_button a,
#hide_button a {
	text-indent:-9999px;
	display:block;
}
.panel_button a:hover {
	color: #999999;
}

#toppanel {
	position:relative;
	width: 100%;
	left: 0px;
	z-index: 99999;
	text-align: center;

}
#panel {
	width: 100%;
	position: relative;

	height: 0px;
	margin-left: auto;
	margin-right: auto;
	z-index: 10;
	overflow: hidden;
	text-align: left;
	background:#000;
}
#panel_contents {

	height: 100%;
	width: 1200px;
	margin:auto;
	z-index: -1;
	color:#FFF;
	margin-top:50px;
	
	font-size:12px;
}

#hide_button{
	background:url(<?php echo url::asset("frontend/images/pim_top_open.jpg");?>) no-repeat top left;
	
	
}


.panel-wrap{
	position:relative;
	width:990px;
	margin:0px auto;
	
}

#hide_button_top{
	background:none !important;
	right:0px;
	
	
	
}

#hide_button_top a{
	color:#FFF;
	background:#009bff;
	padding:5px 20px;
	font-size:12px;
	
}

.top-close a{
text-indent:0px !important;
display:block !important;
}

#panel_contents h3{
	font-size:12px;
	margin:0px;
	padding:0px;
	margin-bottom:10px;
	text-transform:uppercase;
	
	
}

.col-top{
	display:inline-table;
	width:138px;
	margin-bottom:30px;
}




.col-top ul{
	margin:0px;
	padding:0px;
	margin-right:10px;
	float:left;
}


.col-top ul li{
	padding-left:15px;
	list-style:none;
	background:url(<?php echo url::asset("frontend/images/top_bullet.jpg");?>) no-repeat left 6px;
}

.col-top a{
	color:#FFF;
	   -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
}

.col-top a:hover{
	color:#009BFF;
}


.col-large ul{
	width:186px;
	margin:0px;
	padding:0px;
	margin-right:10px;
	float:left;
}

.col-large ul li{
	padding-left:15px;
	list-style:none;
	background:url(<?php echo url::asset("frontend/images/top_bullet.jpg");?>) no-repeat left 6px;
	
}

.col-large ul li a{
	color:#FFF;
	   -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
	
}

.col-large a:hover{
	color:#009BFF;
}

</style>
<div id="toppanel">
    <div id="panel">
    <div class="wrap"><div class="panel_button" id="hide_button_top" style="display: none;"> <a href="#"><i class="fa fa-times-circle-o"></i> Close</a> </div></div>
      <div id="panel_contents">
      <?php
      foreach($stateR as $stateID => $stateName)
      {
		## is not sabah.
		if($stateID != 12)
		{
			if(isset($res_site[$stateID]))
			{
				echo "<div class='col-top'>";
				echo "<h3>$stateName</h3>";
				echo "<ul>";
				foreach($res_site[$stateID] as $row)
				{
					$url	= url::base($row['siteSlug']);
					$name	= $row['siteName'];
					echo "<li><a href='$url'>$name</a></li>";
				}
				echo "</ul>";
				echo "</div>";
			}
		}
      }

      ## sabah part.
      if(isset($res_site[12]))
      {
      	echo "<div class='col-large'>";
  		echo "<h3>Sabah</h3>";

  		## devide by 5 column.
  		$totalPerColumn	= ceil(count($res_site[12])/5);

  		$ULopened	= false;
  		$currNo		= 1;
  		foreach($res_site[12] as $row)
  		{
  			if(!$ULopened)
  			{
  				$ULopened = true;
  				echo "<ul>";
  			}
  			$url	= url::base($row['siteSlug']);
  			echo "<li><a href='$url'>".$row['siteName']."</a></li>";

  			if($currNo == $totalPerColumn)
  			{
  				echo "</ul>";
  				$ULopened = false;
  				continue;
  			}
  			$currNo++;
  		}
  		if($ULopened)
  		{
  			echo "</ul>";
  			$ULopened = false;
  		}

  		echo "</div>";## end of col-large
      }

      ?>
      </div><!-- end of #panel_contents -->
    </div>
	<div class="panel-wrap">
	    <div class="panel_button" style="display: visible;" id="show_button"> <a href="#">Login Here</a> </div>
	    <div class="panel_button" id="hide_button" style="display: none;"> <a href="#">Hide</a> </div>
	</div>
</div>
<?php
#========================================
# SECTION : Pi1M LIST END
#========================================
?>

<div class="main-wrap">
<style type="text/css">
/*temporary*/
input
{
	/*color:#727272 !important;*/
}
.announcement-linked
{
	color:blue !important;
}
.announcement-unlinked
{
	color:inherit;
	cursor: default !important;
}
.announcement-unlinked:hover
{
	color:inherit !important;
	text-decoration:none !important;
}

/* MCMC Update */
.header
{
	border-bottom-color: #f2970e;
	position: relative;
}
.logo
{
	background:url("<?php echo url::asset('frontend/images/vMCMC/logo.jpg');?>") no-repeat scroll left top rgba(0, 0, 0, 0);
	padding-left:83px;
	min-width: 350px;

	/* absolution */
	position: absolute;
	top:5px;
}
.navigation
{
	font-size: 13px;
}
.logo h1
{
	font-size: 22px;
	font-family: 'Roboto Condensed',sans-serif;
	font-weight: 700;
	text-transform: capitalize;
	position: relative;
	top:-3px;
}
.logo h1 a
{
	color:black !important;
}
.navigation ul li a:hover, .navigation ul li a.active, .nav > li:hover > a
{
	color:#f2970e;
	background: url(<?php echo url::asset('frontend/images/vMCMC/navi_arrow_active.jpg');?>) no-repeat bottom center;
}
.footer, .copyright a:hover, .copyright a, .copyright ul li
{
	border-color:#f2970e;

	color:#f2970e;
}
.nav > li > div
{
	background: #f2970e;
}
.footer
{
	background: #000 url(<?php echo url::asset('frontend/images/vMCMC/footer_bg.jpg');?>) no-repeat top center;
}

/* MCMC Update Ends */

/* TEMPORARY */
.frontend-pagination
{
	text-align: right;
}

.frontend-pagination a
{
	padding:5px;
	box-shadow: 0px 0px 3px #7d7d7d;
	margin-left:10px;
}

.frontend-pagination a.active
{
	background: #009bff;
	color:white;
}


</style>
<?php #controller::load("partial","top");?>

<style type="text/css">
	
.social-network .fa
{
	color:inherit;
}

.social-network .fa.fa-facebook:hover
{
	color: #728cc2;
}

.social-network .fa.fa-twitter:hover
{
	color: #55acee;
}

.social-network .fa.fa-envelope:hover
{
	color: white;
}

.top-message
{
	box-shadow: 0px 3px 5px #820000;
	padding:3px 6px 3px 6px;
	line-height:normal;
	width:100%;
	text-align:center;
}

.top-message.in-active
{
	background: #8c2f2f;
	background: #d90000;
	color:white;
}
.user-setting
{
	position: relative;
}

/* Accordion */
 #accordion {  
    width: 280px;  
    margin: 20px auto;
	margin-left:0px;    }

    #accordion .ui-accordion-content {  
    width: 100%;  
    background-color: #f3f3f3;  
    color: #777;  
    font-size: 10pt;  
    line-height: 16pt;  
}  

    #accordion .ui-accordion-content > * {  
        margin: 0;  
        padding: 20px;  
    }  
	
	#accordion .ui-accordion-content a {  
    color: #777;  
	} 

#accordion .ui-accordion-header {  
    background-color: #ccc;  
    margin: 0px;  
} 


    #accordion .ui-accordion-header a {  
        color: #fff;  
        line-height: 42px;  
        display: block;  
        font-size: 12pt;  
        width: 100%;  
        text-indent: 10px;  
    }  
	
	
	
	
	    #accordion .ui-accordion-header {  
        background-color: #009bff;  
    }  
	
	
	div#accordion i{ 
	margin-right:10px;
	
	
	}
	
	#accordion .ui-state-active{
		background:#0062a1 !important;
		
	}


    #accordion .ui-accordion-header a {  
        text-shadow: 1px 1px 0px rgba(0,0,0,0.2);  
        text-shadow: 1px 1px 0px rgba(0,0,0,0.2);  
        
        border-left: 1px solid rgba(0, 0, 0, .2);  
        border-bottom: 1px solid rgba(0, 0, 0, .2);  
        border-top: 1px solid rgba(250, 250, 250, .2);  
    }  
	
	
	
	
	    #accordion .ui-accordion-content {  
					border-bottom:3px solid #e5e5e5;  
					
    }  
	
	.normalBox h1{
		margin-top:0px;
		font-size:15px;
		
	}
	
	
	
	.normalBox a{
		color:#009bff;
		font-size:14px;
		margin-bottom:5px;
		  -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
		
	}
	
	.normalBox a:hover{
		color:#666;
		
		
	}
	
	i.fa-angle-double-down{
		 float: right;
    margin-top: 15px;
    margin-right: 20px !important;
		
	}

</style>
<?php
#========================================
# SECTION : TOP START
#========================================
?>
<div class="top-header">
	<div class="wrap clearfix">
		<!-- <div class="other-location">
			<div class="wrapper-dropdown-2">
				<ul class="dropdown">
					<div id="content_7" class="content">
						<?php # controller::load("partial","pim_list");?>
					</div>
				</ul>			
			</div>
		</div> -->
	<div class="social-network clearfix">
		<ul>
			<?php 
			if($links['siteInfoFacebookUrl']):?>
			<li><a target='_blank' href='//<?php echo str_replace(Array("http://","https://"), "", $links['siteInfoFacebookUrl']);?>' class="fa fa-facebook"></a></li>
			<?php endif;
			if($links['siteInfoTwitterUrl']):?>
			<li><a target='_blank' href='//<?php echo str_replace(Array("http://","https://"), "", $links['siteInfoTwitterUrl']);?>' class="fa fa-twitter"></a></li>
			<?php endif;
			if($links['siteInfoEmail']):?>
			<li><a href='mailto:<?php echo $links['siteInfoEmail'];?>' class="fa fa-envelope"></a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="user-setting">
	<?php 
	## logged in
	if($username):?>
		<span style='vertical-align:top;color:#009BFF;position:relative;left:-5px;font-weight:lighter;'>
		<span style='color:#888888;'>Selamat datang,</span> <a href='<?php echo url::base("{site-slug}/profile");?>' style='color:inherit;'><?php echo $username;?></a></span>
		<a href='<?php echo url::base("{site-slug}/logout");?>' class='fa fa-power-off' style='color:#eb1414;position:relative;top:1px;'></a>

		<?php if(authData("user.memberStatus") == "inactive"):?>
			<!-- <div class='in-active'>
			Akaun anda masih belum aktif.
			</div> -->
		<?php endif;?>
	<?php
	## not logged.
	else:?>
	<form method='post' action='<?php echo url::base("{site-slug}/login");?>'>
		<input type="text" name='login_userIC' class="username" placeholder='Kad Pengenalan'>
		<input type="password" name='login_userPassword' class="password" placeholder='Kata Laluan'> 
		<input type="submit" class="submit" value="Log Masuk">
		<a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>" class="rgstr-button">Daftar</a>
	</form>
<?php endif;?>
	</div>
	</div>	
</div> <!-- Top Header End -->
<?php if(authData("user.memberStatus") == "inactive"):?>
<div class='top-message in-active' style="margin-bottom:5px;">
Akaun anda masih belum aktif. Sila buat bayaran RM <?php echo model::load("config")->get("configMemberFee",5);?> kepada pengurus laman di kawasan anda. [<?php echo authData("site.siteName");?>]
</div>
<?php endif;?>
<?php if($msg = model\server::getAnnouncement()):?>
<div class='top-message in-active'>
<?php echo $msg;?>
</div>
<?php endif;?>
<?php
#========================================
# SECTION : TOP END
#========================================
?>
<?php
#========================================
# SECTION : HEADER START (Top menu was here);
#========================================
?>
<?php #controller::load("partial","header");?>

<style type="text/css">
	
.search-box
{
	position: absolute;
	right:0px;
	top:-30px;
}

.search-box input
{
	padding:5px;
	border:1px solid #e8e8e8;
	border:0px;
	border-bottom:1px solid black;
}
.navigation
{
	position: relative;
}

</style>
<div class="header">
	<div class="wrap">
		<div class="logo">
			<h1><a href='<?php echo url::base("{site-slug}");?>' style='color:inherit;'><?php echo $siteName;?></a></h1>
		</div>

		<!-- Responsive Code -->
		<div class="logo-mobile">
			<img src="<?php echo url::asset("frontend/responsive/images/logo_mobile.jpg");?>">
			<h1><?php echo $siteName;?></h1>
		</div>
		<!-- -->
		<!-- Responsive Code -->
		<div class="navigation-tablet">
			<div class="mobile-navigation-button">
				<div class="sb-toggle-right"><i class="fa fa-bars"></i></div>
			</div>
		</div>
		<!-- -->

		<div class='navigation'>
		<ul class='nav'>
		<?php
		$componentChildR	= model::load("site/menu")->componentChild();
		$mobileMenu			= "";

		if($menuR)
		{
			## prepare list of menu under component.
			$componentMenu	= Array(
							1=>Array("page@index"),
							2=>Array("main@index"),
							3=>Array("activity@index","activity@index","activity@view"),
							4=>Array("gallery","gallery","gallery","forum","video","member"),
							5=>Array("main@contact"),
							6=>Array("blog@article","blog@view")
									);

			$menuNo = 0;
			foreach($menuR as $row)
			{
				$menuNo++;

				## custom hardcoded top menu.
				if($menuNo == count($menuR))
				{
					$faqHref	= url::base("{site-slug}/soalan-lazim");
					$cssActive	= controller::getCurrentMethod() == "faq"?"active":"";
					echo "<li><a href='$faqHref' class='$cssActive'>Soalan Lazim</a></li>";
				}

				## if component status deactivated, skip.
				if($row['componentStatus'] == 0)
				{
					continue;
				}

				$component	= $row['componentNo'];
				$menuName	= !$row['componentName']?$row['menuName']:$row['componentName'];
				$main_url	= url::base(request::named("site-slug")."/".$row['componentRoute']);
				$pageID		= null;

				## skip certain component for not user.
				if(in_array($component,Array(4)) && !session::has("userID"))
					continue;

				## pages
				if($component == 1)
				{
					$page	= model::load("page/page");

					## get default.. (like name or slug, can be used if tyoe = 1[default])
					$defaultR	= $page->getDefault();
					$pageID	= $row['menuRefID'];

					## get page.
					$row_page	= $page->getPage($pageID);

					$defaultType	= $row_page['pageDefaultType'];

					## if type 1, use default slug.
					$main_url		= $row_page['pageType'] == 1?url::base(request::named('site-slug')."/".$defaultR[$defaultType]['pageDefaultSlug']):$main_url;
					$menuName		= $row_page['pageType'] == 1?$defaultR[$defaultType]['pageDefaultName']:$row_page['pageName'];

					## check children page.
					$childPage	= $page->getChildrenPage($pageID);

					if($childPage)
					{
						foreach($childPage as $row)
						{
							$childDefaultType	= $row['pageDefaultType'];

							## get child page url.

							## default check on $defaultR.
							$childPageURL	= $main_url."/".($row['pageType'] == 1?$defaultR[$childDefaultType]['pageDefaultSlug']:$row['pageSlug']);
							$pageName		= $row['pageType'] == 1?$defaultR[$childDefaultType]['pageDefaultName']:$row['pageName'];

							## childpage R
							$childPageR[$pageID][]	= Array($childPageURL,$pageName);
						}
					}
				}
				
				## end page component.

				## echo the menu.
				$controller	= controller::getCurrentController();
				$method		= controller::getCurrentMethod();

				## prepare the active menu css.
				$cm	= $controller."@".$method;

				$cssActive	= "";
				if(isset($componentMenu[$component]) && (in_array($cm,$componentMenu[$component]) || in_array($controller,$componentMenu[$component])))
					$cssActive	= "active";

				$dropdownIcon	= isset($childPageR[$pageID]) || isset($componentChildR[$component])?'<span><i class="fa fa-sort-asc"></i></span>':"";
				echo "<li><a href='$main_url' class='$cssActive'>$menuName $dropdownIcon</a>";

				## mobileMenu
				$mobileMenu	.= "<li><a href='$main_url'>$menuName</a></li>";

				## for first appearance in childmenu. github #10
				$firstmenu	= "<li><a href='".$main_url."'>$menuName</a></li>";
				if($component == 1)
				{
					if(isset($childPageR[$pageID]))
					{
						$mobileMenu	.= "<ul>";

						echo "<div>";
						echo '<div class="nav-column"><div class="menu-block">';
						echo "<ul class='clearfix'>";
						echo "<h3>$menuName</h3>";

						$no	= 1;
						foreach($childPageR[$pageID] as $row)
						{
							if($no == 1)
							{
								echo $firstmenu;
							}
							echo "<li><a href='".$row[0]."'>$row[1]</a></li>";

							$mobileMenu	.= "<li><a href='$row[0]'>$row[1]</a></li>";

							$no++;
						}

						echo "</ul>";
						echo "</div></div>";
						echo "</div>";

						$mobileMenu	.= "</ul>";
					}
				}


				if(isset($componentChildR[$component]))
				{
					$mobileMenu .= "<ul>";

					$columnlimit	= ceil(count($componentChildR[$component]) / 2);

					echo "<div>";
					#echo '<div class="nav-column">';
					
					$no	= 1;
					$colno = 1;
					$blockno	= 1;
					foreach($componentChildR[$component] as $headername=>$rowR)
					{
						if(!isset($submenuBuffer[$colno]))
						{
							$submenuBuffer[$colno] = "";
						}

						$submenuBuffer[$colno] .= "<div class='menu-block'>";
						$submenuBuffer[$colno] .= "<ul class='clearfix'>";

						$mobileMenu .= "<div>";
						$mobileMenu .= "<li class='submenu-heading'>".$headername."</li>";

						$submenuBuffer[$colno] .= "<h3>$headername</h3>";
						foreach($rowR as $row)
						{
							$href	= $row[1] == "#"?"#":url::base("{site-slug}/".$row[1]);
							$submenuBuffer[$colno] .= "<li><a href='$href'>$row[0]</a></li>";

							$mobileMenu	.= "<li><a href='$href'>$row[0]</a></li>";
							$no++;
						}

						$submenuBuffer[$colno] .= "</ul>";
						$submenuBuffer[$colno] .= "</div>";

						if($blockno >= $columnlimit)
						{
							$colno++;
						}

						$blockno++;
					}

					## print the buffer.
					foreach($submenuBuffer as $colno=>$buffer)
					{
						echo "<div class='nav-column'>";
						echo $buffer;
						echo "</div>";
					}


					$mobileMenu	.= "</ul>";
					
					#echo "</div>";
					echo "</div>";
				}


				echo "</li>";
			}
		}

		?>
		</ul>
		</div>
	</div>
</div>
<?php
#========================================
# SECTION : HEADER END
#========================================
?>
<div class="main-container">
	<div class="slideshow">
		<?php
		#========================================
		# SECTION : SLIDESHOW START
		#========================================
		?>
		<style type="text/css">

		</style>
		<div class="slider-wrapper theme-default">
		    <div id="slider" class="nivoSlider">
		    	<?php
		    	foreach($res_slider as $row):
		    		$url	= url::asset("frontend/images/slider/".$row['siteSliderImage']);
		            $href   = $row['siteSliderLink'];
		    		?>
		    	<a href='<?php echo $href;?>' target='_blank'><img src='<?php echo $url;?>' data-thumb='<?php echo $url;?>' alt='' title='<?php echo $row['siteSliderName'];?>' /></a>
		    	<?php endforeach;
		    	?>
		    </div>
		</div>
		<?php
		#========================================
		# SECTION : SLIDESHOW END
		#========================================
		?>
		<?php #controller::load("partial","top_slideshow");?>
	</div>
	<div class="wrap">
	<div class="body-container clearfix">
		<div class="lft-container">
		<!-- Main content show! -->
		<?php template::showContent();?>
		</div>
		<!-- Right container -->
		<div class="rght-container">
			<div class="search-top">
				<form method='get' action='<?php echo url::base("{site-slug}/carian");?>'>
				<input type="text" class="search-top-input" name='q' id='q' value="<?php echo request::get('q');?>" placeholder='Carian'>
				<input type="submit" class="submit-search" value="Cari">
				</form>
			</div>
			<?php controller::load("partial","calendar");?>
			<?php if(controller::getCurrentControllerMethod() == "main@index"):?>
			<!-- Right Accordion -->
			<div id="accordion">  
			<h3><a href="#"><i class="fa fa-clock-o"></i>Waktu Operasi Pi1M<i class="fa fa-angle-double-down"></i></a></h3>  
			<div>  
			<div class="blueBoxContent">
			<strong>Dibuka setiap hari</strong>
			<div class="bluehilite">
			<?php if(in_array(authData("current_site.stateID"),Array(12,13))):?>
			8.00 pagi - 5.00 petang
			<?php else:?>
			9.00 pagi - 6.00 petang
			<?php endif;?>
			</div>
			(kecuali cuti umum)<br><br>

			<strong>Yuran keahlian:</strong><br> 
			<div class="bluehilite">RM5.00</div>
			(tertakluk kepada terma dan syarat)<br><br>

			<strong>Caj Penggunaan:</strong><br> 
			<div class="bluehilite">RM1.00 sejam (ahli)<br>
			RM2.00 sejam (bukan ahli)</div> 
			</div>
			</div>  
			<h3><a href="#"><i class="fa fa-print"></i> Perkhidmatan Lain<i class="fa fa-angle-double-down"></i></a> </h3>  
			<div>  
			<div class="blueBoxContent">
			<strong>Pencetak dan Fotokopi (hitam/putih)</strong><br>
			<div class="bluehilite">RM0.20</div> setiap mukasurat<br><br>

			<strong>Pencetak dan Fotokopi (warna)</strong><br>
			<div class="bluehilite">RM1.00</div> setiap mukasurat<br><br>

			<strong>Pengimbas</strong><br>
			<div class="bluehilite">RM0.20</div> setiap mukasurat<br><br>
			</div> 
			</div>  

			</div>

			<div class="normalBox">
			<h1>Capaian Pantas</h1>
			<a href="#">Universal Service Provision</a><br> 
			<a href="#">Suruhanjaya Komunikasi dan Multimedia</a><br>
			<a href="#">Kementerian Komunikasi dan Multimedia</a><br>
			<a href="#">Service Provider Website</a><br>
			<a href="#">Other Related Website</a> 
			</div>
			<!-- Right Accordion, end -->
			<?php endif;?>

			</div>
		</div>
	<div class="clr"></div>
		<!-- Announcement -->
			<div class="bttm-center">
				<div class="announcement clearfix">
				<?php 
				## set announcement only for site landing page. (main/index)
				if(controller::getCurrentController() == "main" && controller::getCurrentMethod() == "index"):

			  	$annList = model::load("site/announcement")->getAnnouncementList($row_site['siteID'],true);
				if($annList){
				?>
				<div class="label-anncmnt">Pengumuman</div>
				<div class="cntnt-anncmnt">
					<ul id="js-news" class="js-hidden">
				  <?php
				  foreach($annList as $row)
				  {
    					/*if(strpos($row['announcementLink'], 'localhost') !== false || strpos($row['announcementLink'], 'p1m') !== false){
     					   $target = '';
   						}else{
        				   $target = "target='_blank'";
    					}
					    if($row['announcementLink'] != ""){
					        $href = "href='".$row['announcementLink']."' class='announcement-linked'";
					    }else{
					        $href = "href='#'  class='announcement-unlinked'";
					    }*/
					    if($row['announcementLink'] != "http://")
					    {
					    	$attr = "class='announcement-linked' ";
					    	$attr .= "href='".$row['announcementLink']."' ";
					    	$attr .= "target='_blank'";
					    }
					    else
					    {
					    	$attr = "class='announcement-unlinked'";
					    	$attr .= "href='#'";
					    }

    					echo "<li><a $attr>".$row['announcementText']."</a></li>";
				  }
				  ?>
				  </ul>
				</div>
				<?php }?>
				<?php endif;## end main/index check. ?>
				</div>
			</div>
		<!-- Bottom down container -->
		<?php
		#========================================
		# SECTION : BOTTOM START
		#========================================
		?>
		<?php #controller::load("partial","bottom_down");?>
		<!-- Temporary overlay css -->
		<style type="text/css">
		.bttm-1,
		.bttm-2,
		.bttm-3 
		{
			position: relative;
		}

		.akan-datang
		{
			background: rgba(255, 255, 255, 0.85) url("<?php echo url::asset('frontend/images/akan_datang.png');?>") no-repeat center center;
			color: rgba(255, 255, 255, 0.85);
			width:100%;
			height:100%;
			position:absolute;
			top:0px;
			z-index:999;
		}

		.no-result{
			color:#999;
			font-style:italic;
			
		}
		</style>
		<!-- temporary overly css ends -->
		<div class="bttm-down clearfix">
			<div class="bttm-1">
				<div class="maps-bottom">
					<div class="maps-container">
					<?php
					$row_site['siteInfoLatitude']	= !is_numeric($row_site['siteInfoLatitude'])?3.0714381964016:$row_site['siteInfoLatitude'];
					$row_site['siteInfoLongitude']	= !is_numeric($row_site['siteInfoLongitude'])?101.39110565186:$row_site['siteInfoLongitude'];
					?>
					<style type="text/css">
					
					#mymap
{
	width:390px;
	height:200px;
}
					</style>
					<script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJYP04YDNcHuvcjj317GNsZblEK32L76M&sensor=false"></script>
					<script type="text/javascript">
					jQuery(document).ready(function()
					{
						var latLng	= new google.maps.LatLng(<?php echo $row_site['siteInfoLatitude'].",".$row_site['siteInfoLongitude'];?>)
						var options	= {
							zoom:13,
							center: latLng
						};

						//initiate map.
						var map	= new google.maps.Map(document.getElementById("mymap"),options);

						//add marker
						var marker	= new google.maps.Marker({
							position: latLng,
							map: map,
							title: "<?php echo $row_site['siteName'];?>"
						});
						
					});
					</script>
					<div id='mymap' style="width:100%;height:200px;">
					</div>
					<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1982.6780417481739!2d99.76039763995057!3d6.347917847778613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304c78508590bd0f%3A0xa081e5cf738400d2!2sKampung+Bukit+Tangga!5e0!3m2!1sen!2s!4v1394689646969?center=" width="390" height="200" frameborder="0" style="border:0"></iframe> -->
			
					</div>
				<div class="maps-label"></div>
				</div>
			</div>
			<div class="bttm-2"> 
				<!-- <div class="akan-datang"></div> -->
				<div class="news-bottom">
					<h3 class="bottom-heading">Berita Terkini</h3>
					<div class="bottom-content">
						<ul>
							<?php if($articles): ?>
							<?php foreach($articles as $article): 
							$url	= model::load("helper")->buildDateBasedUrl($article['articleSlug'],$article['articlePublishedDate'],url::base($article['siteSlug']."/blog"));
							?>
							<li>
								<a href="<?php echo $url;?>">
								<div class='news-title'><?php echo $article['articleName']; ?></div>
								<div class="news-info"><?php echo date("F j Y",strtotime($article['articlePublishedDate']));?><!-- October 31 2013, 8:00 AM --></div>
								</a>
							</li>
							<?php endforeach; ?>
							<?php else: ?>
								<div class="no-result">- Tiada artikel terkini - </div>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="bttm-3 foto-wrapper">
				<h3 class="bottom-heading">Foto Terkini</h3>
				<div class="bottom-content foto-bottom">
					<?php if($latestPhotoLink):?>
						<a href='<?php echo $latestPhotoLink;?>'>
							<img src="<?php echo $latestPhotoUrl;?>" width="270" height="208"  alt=""/>
						</a>
					<?php else:?>
					<div class="no-result">- Tiada foto terkini - </div>
					<?php endif;?>
				</div>
			</div>
		</div> 

		<?php
		#========================================
		# SECTION : BOTTOM END
		#========================================
		?>
		<!-- bttm-down clearfix -->
	</div> <!-- body-container clearfix -->
	</div> <!-- wrap -->
</div>
<?php
#========================================
# SECTION : ALL BOTTOM START
#========================================
?>
<?php #view::render("partial/all_bottom");?>
<div class="footer">
	<div class="wrap clearfix">
		<div class="copyright">
		Hakcipta Terpelihara © <?php echo date("Y");?> <a href="#">Pusat Internet 1 Malaysia</a>
		<ul class="clearfix">
			<li><a href="<?php echo url::base("{site-slug}");?>">Utama</a></li>
			<li><a href="<?php echo url::base("{site-slug}/mengenai-kami");?>">Mengenai Kami</a></li>
			<li><a href="<?php echo url::base("{site-slug}/hubungi-kami");?>">Hubungi Kami</a></li>
		</ul>
		</div>
	<div class="logo-bottom">
		<ul class="clearfix">
			<!-- <li><a target="_blank" href='http://www.skmm.gov.my'><img src="<?php echo url::asset("frontend/images/mcmc_logo.png");?>" width="72" height="46"  alt=""/></a></li> -->
			<li><a target="_blank" href='http://www.celcom.com.my'><img src="<?php echo url::asset("frontend/images/vMCMC/celcom_bottom.png");?>" width="87" height="46"  alt=""/></a></li>
			<!-- <li><a href='<?php echo url::base();?>'><img src="<?php echo url::asset("frontend/images/pi1m_bottom.png");?>" width="241" height="46"  alt=""/></a></li> -->
		</ul>
	</div>
	</div>
</div>
</div><!-- main-wrap -->

<!-- Responsive Code -->
<div class="sb-slidebar sb-right sb-style-overlay">
<div class="mobile-navigation-header clearfix"><div class="sb-close menu-close"><i class="fa fa-times"></i></div><div class="menu-name">Menu</div></div>
	<div class="mobile-navigation-content">
		<ul>
		<?php
		echo $mobileMenu;?>
		</ul>
		<!-- <ul>
		    <li><a href="#">Utama</a></li>
		    <li><a href="#">Mengenai Kami</a></li>
		    <li><a href="#">Aktiviti</a></li>
		    <li>Ruangan Ahli</li>
		   	 <ul>
		 		<div>
				<li class="submenu-heading">Kalendar Aktiviti</li>
		        <li><a href="#">Aktiviti Akan Datang</a></li>
		        <li> <a href="#">Aktiviti Lepas</a></li>
				</div>
		        <div>
				<li class="submenu-heading">Galeri Media</li>
		        <li> <a href="#">Galeri Foto</a></li>
					<li><a href="#">Galeri Video</a></li>
		            <li><a href="#">Galeri Muat Turun</a></li>
				</div>
			</ul>
	    </ul> -->
    </div>
</div>
<!-- Slidebars for responsive top menu -->
<script src="<?php echo url::asset("frontend/responsive/js/slidebars.js");?>"></script>
<script>
	(function($) {
		$(document).ready(function() {
			$.slidebars();
		});
	}) (jQuery);
</script>
<!------>
	<!-- <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script> -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
	    <script type="text/javascript" src="<?php echo url::asset("frontend/js/jquery.nivo.slider.js");?>"></script>
	    <script type="text/javascript">
		var $s = jQuery.noConflict();
	    $s(window).load(function() {
	    $s('#slider').nivoSlider();
	    });
	    </script>
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
	<script type="text/javascript">
				function DropDown(el) {
	    this.dd = el;
	    this.initEvents();
	}
	DropDown.prototype = {
	    initEvents : function() {
	        var obj = this;
	 
	        obj.dd.on('click', function(event){
	            $(this).toggleClass('active');
	            event.stopPropagation();
	        }); 
	    }
	}
	</script>
</body>
</html>
<?php
#========================================
# SECTION : ALL BOTTOM END
#========================================
?>