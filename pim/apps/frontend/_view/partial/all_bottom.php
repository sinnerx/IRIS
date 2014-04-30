<div class="footer">
	<div class="wrap clearfix">
		<div class="copyright">
		Hakcipta Terpelihara © <?php echo date("Y");?> <a href="#">Pusat Internet 1 Malaysia</a>. All Rights Reserved
		<ul class="clearfix">
			<li><a href="<?php echo url::base("{site-slug}");?>">Utama</a></li>
			<li><a href="<?php echo url::base("{site-slug}/mengenai-kami");?>">Mengenai Kami</a></li>
			<li><a href="<?php echo url::base("{site-slug}/hubungi-kami");?>">Hubungi Kami</a></li>
		</ul>
		</div>
	<div class="logo-bottom">
		<ul class="clearfix">
			<li><a target="_blank" href='http://www.skmm.gov.my'><img src="<?php echo url::asset("frontend/images/skmm_logo.jpg");?>" width="72" height="46"  alt=""/></a></li>
			<li><a target="_blank" href='http://www.celcom.com.my'><img src="<?php echo url::asset("frontend/images/celcom_bottom.jpg");?>" width="87" height="46"  alt=""/></a></li>
			<li><a href='<?php echo url::base();?>'><img src="<?php echo url::asset("frontend/images/pi1m_bottom.jpg");?>" width="241" height="46"  alt=""/></a></li>
		</ul>
	</div>
	</div>
</div>
</div><!-- main-wrap -->
	<script src="<?php echo url::asset("frontend/js/site.js");?>" type="text/javascript"></script>
	
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