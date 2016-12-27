<div class="footer">
	<div class="wrap clearfix">
		<div class="copyright">
		Hakcipta Terpelihara © <?php echo date("Y");?> <a href="#">Calent</a>
		<ul class="clearfix">
			<li><a href="<?php echo url::base("{site-slug}");?>">Utama</a></li>
			<li><a href="<?php echo url::base("{site-slug}/mengenai-kami");?>">Mengenai Kami</a></li>
			<li><a href="<?php echo url::base("{site-slug}/hubungi-kami");?>">Hubungi Kami</a></li>
		</ul>
		</div>
	<div class="logo-bottom">
		<ul class="clearfix">
			<!-- <li><a target="_blank" href='http://www.skmm.gov.my'><img src="<?php echo url::asset("frontend/images/mcmc_logo.png");?>" width="72" height="46"  alt=""/></a></li> -->
			<li><a target="_blank" href='http://www.celcom.com.my'><img src="<?php echo url::asset("frontend/images/vMCMC/calent_logo.png");?>" width="87" height="46"  alt=""/></a></li>
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
	    </ul>
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