<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if (IE 9)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Pusat Internet 1 Malaysia</title>   

<meta name="description" content="Insert Your Site Description" /> 

<!-- Mobile Specifics -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="HandheldFriendly" content="true"/>
<meta name="MobileOptimized" content="320"/>   

<!-- Mobile Internet Explorer ClearType Technology -->
<!--[if IEMobile]>  <meta http-equiv="cleartype" content="on">  <![endif]-->
<?php view::render("partial/landing_scripts");?>
</head>


<body>


<!-- This section is for Splash Screen -->
<div class="ole">
<section id="jSplash">
	<div id="circle"></div>
</section>
</div>
<!-- End of Splash Screen -->

<!-- Homepage Slider -->

<div id="home-slider">
<div class="main-wrap clearfix">

<div class="span5"><img src="<?php echo url::asset("_landing/images/skmm_logo.png");?>"/></div>

<div class="span5 right-blck"><img src="<?php echo url::asset("_landing/images/pi1m_logo.png");?>" /></div>

	
  </div>
<div class="span3">

<div id="block_navigation">
	<?php controller::load("partial","landing_menu");?>
</div>

            	
            </div>
            
            
            
    <div class="overlay"></div>

    <div class="slider-text">
    	<div id="slidecaption"></div>
    </div>   
	<div class="footer-front clearfix"><div class="annoucement">
    <div class="label-ann">Kemaskini Terkini:</div>
    <div id="example">
  <ul>
  <?php
  $annList = model::load("site/announcement")->getAnnouncementList(0, true);
  foreach($annList as $row)
  {
    if(strpos($row['announcementLink'], 'localhost') !== false || strpos($row['announcementLink'], 'p1m') !== false){
        $target = '';
    }else{
        $target = "target='_blank'";
    }
    echo "<li><a ".$target." href='".$row['announcementLink']."'>".$row['announcementText']."</a></li>";
  }
  ?>
  </ul>
</div>

    
    </div>
    
    <div class="celcom-logo"><img src="<?php echo url::asset("_landing/images/celcom_logo.png");?>" width="156" height="78"  alt=""/></div>
  </div>

</div>
<!-- End Homepage Slider -->




<!-- Js -->
<?php view::render("partial/landing_scripts_btm");?>
<script src="<?php echo url::asset("_landing/js/main-relative.js");?>"></script> <!-- Default JS -->
<!-- End Js -->

</body>
</html>