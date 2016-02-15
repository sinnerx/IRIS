<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if (IE 9)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<head>
<style type="text/css">
  
#mengenai-kami-list li
{
  padding:5px;
}

</style>
<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Pusat Internet 1 Malaysia - Tentang Kami</title>   

<meta name="description" content="Insert Your Site Description" /> 

<!-- Mobile Specifics -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="HandheldFriendly" content="true"/>
<meta name="MobileOptimized" content="320"/>   

<!-- Mobile Internet Explorer ClearType Technology -->
<!--[if IEMobile]>  <meta http-equiv="cleartype" content="on">  <![endif]-->
<?php view::render("partial/landing_scripts");?>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
      <script src="js/jquery.mousewheel.js"></script>
      <script src="<?php echo url::asset("_landing/js/perfect-scrollbar.js");?>"></script>
      
    <script>
      jQuery(document).ready(function ($) {
        "use strict";
        $('#Default').perfectScrollbar();
      });
    </script>
    
    
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
            
            
            <div class="span8 right-content">
            
            <h3>Mengenai Kami</h3>
            <div class="content-wrap">
           
             <div id="Default" class="contentHolder">
      <div class="content">
      <img src="<?php echo url::asset("_landing/images/1000px-1Malaysia.svg.png");?>" alt="" width="100" class="image-left"/> 
      <p>
      PI1M adalah antara projek yang terawal di bawah inisiatif Program 
      Pemberian Perkhidmatan Sejagat (PPS) di mana PI1M yang pertama 
      telah dibina pada tahun 2007. Projek ini telah membawa impak sosial 
      dan juga ekonomi kepada masyarakat. Dilengkapkan dengan komputer 
      meja serta perkakasannya beserta dengan capaian internet jalurlebar, 
      PI1M juga menawarkan peluang-peluang latihan berkaitan ICT yang 
      dikendalikan oleh dua orang penyelia sepenuh masa di PI1M tersebut.</p>
      <p>Program Pemberian Perkhidmatan Sejagat adalah inisiatif Kerajaan 
      melalui Suruhanjaya Komunikasi dan Multimedia Malaysia yang 
      dilaksanakan bagi mencapai objektif utama berikut: </p>

      <ol id='mengenai-kami-list'>
        <li>Menyediakan capaian komunikasi kepada kumpulan & individu di kawasan kurang liputan</li>
        <li>Meningkatkan penggunaan ICT di kalangan komuniti ke arah pembangunan sosio ekonomi masyarakat setempat</li>
        <li>Merapatkan jurang digital.</li>
      </ol>

      <p>Sehingga Julai 2014, sebanyak 85 PI1M telah dibangunkan dan diuruskan 
      oleh pihak NuSuara Technologies Sdn Bhd di bawah naungan Celcom. 
      PI1M tersebut terdapat di sekitar negeri Johor, Perlis, Kedah, Negeri 
      Sembilan, Sabah, Sarawak dan Wilayah Persekutuan Kuala Lumpur.</p>
      
      </div>
    </div>
            
            
            
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
    if($row['announcementLink'] != ""){
        $href = "href='".$row['announcementLink']."'";
    }else{
        $href = "";
    }
    echo "<li><a ".$target." ".$href.">".$row['announcementText']."</a></li>";
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
<script src="<?php echo url::asset("_landing/js/about-relative.js");?>"></script> <!-- Default JS -->
<!-- End Js -->


</body>
</html>