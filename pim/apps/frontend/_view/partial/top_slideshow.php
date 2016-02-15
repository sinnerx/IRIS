<style type="text/css">
	
.nivoSlider
{
	height:314px;
	overflow: hidden;
}

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
		<!-- <div><img src="<?php echo url::asset("frontend/images/slideshow.jpg");?>" data-thumb="<?php echo url::asset("frontend/images/slideshow.jpg");?>" alt="" title="Program Pusat Internet 1 Malaysia
Untuk Rakyat Termiskin di Bandar"/></div>
		<div><img src="<?php echo url::asset("frontend/images/slideshow2.jpg");?>" data-thumb="<?php echo url::asset("frontend/images/slideshow2.jpg");?>" alt="" title="Program Komputer 1 Malaysia Dilancarkan" /></div>
    	<div><img src="<?php echo url::asset("frontend/images/slideshow3.jpg");?>" data-thumb="<?php echo url::asset("frontend/images/slideshow3.jpg");?>" alt="" title="Jom Daftar Ahli Facebook Group 1 Malaysia" /></div> -->
    </div>
</div>