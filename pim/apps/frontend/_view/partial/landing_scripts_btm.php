<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> <!-- jQuery Core -->
	<!-- custom scrollbars plugin -->
	<script src="<?php echo url::asset("_landing/js/jquery.mCustomScrollbar.concat.min.js");?>"></script>
	<script>
var $jj = jQuery.noConflict();
	
		(function($jj){
			$jj(window).load(function(){
			
				$jj(".content").mCustomScrollbar({
					scrollButtons:{
						enable:true
					},
					theme:"light-thin"
				});
			
			});
		})(jQuery);
	</script>


<script src="<?php echo url::asset("_landing/js/bootstrap.min.js");?>"></script> <!-- Bootstrap -->
<script src="<?php echo url::asset("_landing/js/supersized.3.2.7.min.js");?>"></script> <!-- Slider -->
<script src="<?php echo url::asset("_landing/js/waypoints.js");?>"></script> <!-- WayPoints -->
<script src="<?php echo url::asset("_landing/js/waypoints-sticky.js");?>"></script> <!-- Waypoints for Header -->
<script src="<?php echo url::asset("_landing/js/jquery.isotope.js");?>"></script> <!-- Isotope Filter -->
<script src="<?php echo url::asset("_landing/js/jquery.fancybox.pack.js");?>"></script> <!-- Fancybox -->
<script src="<?php echo url::asset("_landing/js/jquery.fancybox-media.js");?>"></script> <!-- Fancybox for Media -->
<script src="<?php echo url::asset("_landing/js/jquery.tweet.js");?>"></script> <!-- Tweet -->
<script src="<?php echo url::asset("_landing/js/plugins.js");?>"></script> <!-- Contains: jPreloader, jQuery Easing, jQuery ScrollTo, jQuery One Page Navi -->
