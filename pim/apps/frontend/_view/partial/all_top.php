<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
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
</head>
<body>
<?php controller::load("partial","pim_list");?>
<div class="main-wrap">