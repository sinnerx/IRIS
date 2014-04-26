<?php view::render("partial/all_top");?>
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
				if(controller::getCurrentController() == "main" && controller::getCurrentMethod() == "index"):?>
				<div class="label-anncmnt">Pengumuman</div>
				<div class="cntnt-anncmnt">
					<ul id="js-news" class="js-hidden">
				  <?php
				  $annList = model::load("announcement")->getAnnouncement();
				  foreach($annList as $row)
				  {
				    echo "<li class='news-item'><a href='#'>".$row['announcementText']."</a></li>";
				  }
				  ?>
				  </ul>
				</div>
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