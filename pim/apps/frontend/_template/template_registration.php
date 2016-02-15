<?php view::render("partial/all_top");?>
<script type="text/javascript" src='<?php echo url::asset("frontend/js/easyResponsiveTabs.js");?>'></script>
<?php controller::load("partial","top");?>
<div class="main-container">
	<div class="wrap">
		<div class="body-container front-info clearfix">
			<?php template::showContent();?>
		</div>
	</div>
</div>
<?php view::render("partial/all_bottom");?>