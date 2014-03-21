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
						<li class="news-item"><a href="#">Pertandingan Wau Di Felda Bukit Tangga.</a> </li>
						<li class="news-item"><a href="#">GMBO - Get Malaysian Bussiness Online.</a></li>
						<li class="news-item"><a href="#">MBFM Felda Bukit Tangga Menerima Anugerah Persatuan Belia Terbaik 2013.</a></li>
						<li class="news-item"><a href="#">SAMBUTAN MAULIDUR RASUL 2014.</a> </li>
						<li class="news-item"><a href="#">Tentera akur wakil rakyat mampu selesai isu negara.</a></li>
						<li class="news-item"><a href="#">Syor had tayang filem seram di TV.</a> </li>
						<li class="news-item"><a href="#">Bunuh haiwan diet baru pengasas Facebook.</a></li>
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