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
	<div class="bttm-3 foto-wrapper">
		<div class="akan-datang"></div>
		<h3 class="bottom-heading">Foto Terkini</h3>
		<div class="bottom-content foto-bottom">
			<img src="<?php echo url::asset("frontend/images/gallery1.gif");?>" width="270" height="208"  alt=""/>
			<?php /*<ul>
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
			</ul>*/
			?>
		</div>
	</div>
</div> 