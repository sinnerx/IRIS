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
					    	$attr .= "href='".$row['announcementLink']."'";
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