<div class="bttm-down clearfix">
	<div class="bttm-1">
		<div class="maps-bottom">
			<div class="maps-container">
			<?php if(is_numeric($row_site['siteInfoLatitude']) && is_numeric($row_site['siteInfoLongitude'])):?>
			<style type="text/css">

			#mymap
			{
				width:390px;
				height:200px;
			}

			</style>
			<script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJYP04YDNcHuvcjj317GNsZblEK32L76M&sensor=false"></script>
			<script type="text/javascript">
			$(document).ready(function()
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
			<div id='mymap'>
			</div>
			<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1982.6780417481739!2d99.76039763995057!3d6.347917847778613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304c78508590bd0f%3A0xa081e5cf738400d2!2sKampung+Bukit+Tangga!5e0!3m2!1sen!2s!4v1394689646969?center=" width="390" height="200" frameborder="0" style="border:0"></iframe> -->
		<?php endif;?>
			</div>
		<div class="maps-label"></div>
		</div>
	</div>
	<style type="text/css">

	.news-overlay
	{
		 position:absolute;
		 top:100px;
		 font-weight:bold;
		 left:50px;
		 color: #444444;
		 font-size:18px;
		 letter-spacing: 3px;
	}
	</style>
	<div class="bttm-2" style='position:relative;'> 
		<div class='news-overlay'>AKAN DATANG</div>
		<div class="news-bottom" style="opacity:0.2;">
			<h3 class="bottom-heading">Berita Terkini</h3>
			<div class="bottom-content">
				<ul>
					<li>
						<div class='news-title'>Lawatan SKMM ke Pusat Internet 1Malaysia</div>
						<div class="news-info">October 31 2013, 8:00 AM</div>
					</li>
					<li>
						<div class='news-title'>Latihan Asas 'Blogging' Mendapat Sambutan</div>
						<div class="news-info">October 31 2012, 8:00 AM</div>
					</li>
					<li>
						<div class='news-title'>Hari Terbuka Pusat Internet 1Malaysia Meriah</div>
						<div class="news-info">October 31 2014, 8:00 AM</div>
					</li>
				</ul>
				<!-- <ul> ## original example.
					<li>
						<div class="news-title"><a href="#">TVXQ! Live in L.A.  Backstage & Onstage With the K-Pop</a></div>
						<div class="news-info">October 31 2013, 8:00 AM</div>
					</li>
					<li>
						<div class="news-title"><a href="#">TVXQ! Live in L.A.  Backstage & Onstage With the K-Pop</a></div>
						<div class="news-info">October 31 2013, 8:00 AM</div>
					</li>
					<li>
						<div class="news-title"><a href="#">TVXQ! Live in L.A.  Backstage & Onstage With the K-Pop</a></div>
						<div class="news-info">October 31 2013, 8:00 AM</div>
					</li>
				</ul> -->
			</div>
		</div>
	</div>
	<div class="bttm-3 foto-wrapper" style='position:relative;'>
	<div class='news-overlay'>AKAN DATANG</div>
	<div style='opacity:0.2'>
	<h3 class="bottom-heading">Foto Terkini</h3>
		<div class="bottom-content foto-bottom">
			<ul>
				<li><img src="<?php echo url::asset("frontend/images/1.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/2.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/3.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/4.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/5.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/6.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/7.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/8.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/9.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/1.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/2.jpg");?>" width="64" height="63"  alt=""/></li>
				<li><img src="<?php echo url::asset("frontend/images/3.jpg");?>" width="64" height="63"  alt=""/></li>
			</ul>
		</div>
	</div>
	</div>
</div> 