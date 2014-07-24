<?php view::render("partial/all_top");?>
<style type="text/css">
/*temporary*/
input
{
	color:#727272 !important;
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

</style>
<?php controller::load("partial","top");?>
<?php controller::load("partial","header");?>
<div class="main-container">
	<div class="wrap">
		<div class="slideshow">
			<?php controller::load("partial","top_slideshow");?>
		</div>
	<div class="body-container clearfix">
		<div class="rght-container">
			<?php controller::load("partial","calendar");?>
		</div>
		<div class="lft-container">
		<!-- Main content show! -->
		<?php template::showContent();?>
		</div>
		<!-- Right container -->
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
		<?php controller::load("partial","bottom_down");?>
		<!-- bttm-down clearfix -->
	</div> <!-- body-container clearfix -->
	</div> <!-- wrap -->
</div>
<?php view::render("partial/all_bottom");?>