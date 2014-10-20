<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<!-- Responsive Code -->
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

	<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed:700);
	</style>
	<link href="<?php echo url::asset("frontend/css/styles.css");?>" rel="stylesheet" type="text/css">
	<!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> -->
	<link rel="stylesheet" type="text/css" href="<?php echo url::asset("_scale/css/font-awesome.min.css");?>">
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
	<script type="text/javascript" src='<?php echo url::asset("_scale/js/jquery.min.js");?>'></script>
	<script src="<?php echo url::asset("frontend/js/jquery.ticker.js");?>" type="text/javascript"></script>
	<link href="<?php echo url::asset("_landing/css/jquery.mCustomScrollbar.css");?>" rel="stylesheet" type="text/css" /> <!-- used by partial/top -->
	<script src="<?php echo url::asset("_landing/js/jquery.mCustomScrollbar.concat.min.js");?>"></script> <!-- used by partial/top -->
	<script src="<?php echo url::asset("frontend/js/site.js");?>" type="text/javascript"></script>

	<!-- Responsive Code -->
	<style type="text/css">

	.area-mobile, .mobile-top, .logo-mobile
	{
		display: none;
	}

	</style>
	<link href="<?php echo url::asset("frontend/responsive/css/responsive.css");?>" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo url::asset("frontend/responsive/css/slidebars.css");?>">
	<link rel="stylesheet" href="<?php echo url::asset("frontend/responsive/css/example-styles.css");?>">
	<!------>
</head>
<body>
<div class="mobile-top">
	<div class="mobile-icon-left"></div>
	<div class="mobile-site-name">Felda Bukit Tangga</div>
	<div class="mobile-navigation-button">
	<div class="sb-toggle-right"><i class="fa fa-bars"></i></div>
	</div>
</div>
<div class="area-mobile">
	<div class="mobile-cp">
		<ul>
		<li><a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>">Login</a></li>
		<li><a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>">Register</a></li>
		</ul>
	</div>
	<div class="area-mobile-wrap">
		<select>
			<option selected="selected">Ke Pi1M Lain</option>
			<option disabled>Johor</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option disabled>Kedah</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option disabled>Negeri Sembilan</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option disabled>Sabah</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
			<option>Kahang Barat</option>
			<option>Ayer Hitam</option>
			<option>Kahang Timur</option>
			<option>Bukit Tongkat</option>
			<option>Palong Timur 1</option>
			<option>Inas Utara</option>
			<option>Palong Timur 3</option>
			<option>Bukit Permai</option>
			<option>Bukit Batu</option>
			<option>Layang-Layang</option>
			<option>Hj Idris</option>
			<option>Mensudut Lama</option>
		</select>
	</div>
</div>
<?php controller::load("partial","pim_list");?>
<div class="main-wrap">