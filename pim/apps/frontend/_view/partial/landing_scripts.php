<link href="<?php echo url::asset("_landing/css/bootstrap.min.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/main.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/supersized.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/supersized.shutter.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/fancybox/jquery.fancybox.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/fonts.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/shortcodes.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/bootstrap-responsive.min.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/responsive.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/supersized.css");?>" rel="stylesheet">
<link href="<?php echo url::asset("_landing/css/supersized.shutter.css");?>" rel="stylesheet">

<!-- Google Font -->
<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900' rel='stylesheet' type='text/css'>

<!-- Fav Icon -->
<link rel="shortcut icon" href="#">

<link rel="apple-touch-icon" href="#">
<link rel="apple-touch-icon" sizes="114x114" href="#">
<link rel="apple-touch-icon" sizes="72x72" href="#">
<link rel="apple-touch-icon" sizes="144x144" href="#">

<link href="<?php echo url::asset("_landing/css/style.css");?>" rel="stylesheet" type="text/css" />
<link href="<?php echo url::asset("_landing/css/responsive_menu.css");?>" rel="stylesheet" type="text/css" />
<link href="<?php echo url::asset("_landing/css/jquery.mCustomScrollbar.css");?>" rel="stylesheet" type="text/css" />

<script src="<?php echo url::asset("_landing/js/jquery.js");?>"></script> <!-- This is jquery. Use this instead. -->

<script src="<?php echo url::asset("_landing/js/jquery-1.7.2.min.js");?>"></script>
<script src="<?php echo url::asset("_landing/js/modernizr.js");?>"></script>
<script src="<?php echo url::asset("_landing/js/jquery.vticker.js");?>"></script>



<script>
var $j = jQuery.noConflict();
$j(function() {
  $j('#example').vTicker();
});
</script>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

 <style>
    /* styles for desktop */
    .tinynav { display: none }
  
    /* styles for mobile */
    @media screen and (max-width: 600px) {
      .tinynav { display: block }
      #nav { display: none !important; }
    }
  </style>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script src="<?php echo url::asset("_landing/js/tinynav.js");?>"></script>

  <script>
    var $h = jQuery.noConflict();
	$h(function () {

      // TinyNav.js 1
      $h('#nav').tinyNav({
        active: 'selected',
        indent: '-- ',
        label: 'Menu'
      });
      
      

    });
  </script>
