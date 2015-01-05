<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Pusat Internet 1Malaysia</title>
<link href="<?php echo url::asset("skmm/css/reset.css");?>" rel="stylesheet" type="text/css">
<link href="<?php echo url::asset("skmm/css/style.css");?>" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?php echo url::asset("skmm/css/flexslider.css");?>" type="text/css">
<script src="<?php echo url::asset("skmm/js/jquery.min.js");?>"></script>
<script src="<?php echo url::asset("skmm/js/jquery.flexslider.js");?>"></script>
<script type="text/javascript" src="<?php echo url::asset("skmm/js/jquery.accordion.js");?>"></script>

<script type="text/javascript" charset="utf-8">
  $(window).load(function() {
    $('.flexslider').flexslider();
  });
</script>





</head>


<style type="text/css">

    #header
    {
        position: relative;
    }
    #login
    {
        position: absolute;
        right: 0;
        top : -14px;
        font-size: 12px;
        
        margin-right:10px;
    }

    </style>


<body>
    <div id="header">
         <div id="login">
          <a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>" style="color: #fda319;">Login</a>│<a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>"style="color: #fda319;">Register</a>
          </div>
        <div id="splogo">
           
        <img src="<?php echo url::asset("frontend_facade/images/celcom_top.jpg");?>" alt="TM">
        </div>
        <div id="siteLogo">
        <img src="<?php echo url::asset("skmm/images/mcmc-logo.png");?>" width="79" height="56" alt="MCMC">
        </div>
        <div id="siteTitle">
        <img src="<?php echo url::asset("skmm/images/pi1m-title.png");?>" width="348" height="30" alt="PI1M">
        </div>
        <div id="pi1mSite">
        <?php echo $siteName;?>
        </div>
        <div class="clear"></div>
    </div>
    
    <div id="nav">
            <ul>
                <li><a href="<?php echo url::base("{site-slug}");?>">Utama</a></li>
                <li><a href="<?php echo url::base("{site-slug}/mengenai-kami");?>">Mengenai PI1M</a></li>
                <li><a href="<?php echo url::base("{site-slug}/aktiviti");?>">Jadual Latihan & Aktiviti</a></li>
                <li><a href="<?php echo url::base("{site-slug}/galeri");?>">Galeri</a></li>
                <li><a href="<?php echo url::base("{site-slug}/faq");?>">Soalan Lazim</a></li>
                <li><a href="<?php echo url::base("{site-slug}/hubungi-kami");?>">Hubungi Kami</a></li>
                <div class="socialIcon">
                <?php if($links['siteInfoFacebookUrl'] != ""):?>
                    <a href="<?php echo url::createByRoute("api-redirect-link",Array(),true)."?link=$links[siteInfoFacebookUrl]";?>" target="_blank"><img src="<?php echo url::asset("skmm/images/fb-icon.png");?>" width="24" height="24" alt="facebook"></a>
                <?php endif;?>
                </div>
            </ul>
            <div class="clear"></div>
    </div>
    
    <div id="content">
    <?php if(controller::getCurrentMethod() == "index"):?>
    <?php $no = 1;?>
        <div id="sidebar">
        
            <?php controller::load("skmm:main","latestActivity");?>
        
            <div class="blueBox">
            <span class="clock">Waktu Operasi</span>
                <div class="blueBoxContent">
                    Dibuka setiap hari
                    <div class="bluehilite">9.00 pagi - 6.00 petang</div>
                    (kecuali cuti umum)<br><br>
                    
                    Yuran keahlian:<br> 
                    <div class="bluehilite">RM5.00</div>
                    (tertakluk kepada terma dan syarat)<br><br>
                    
                    Caj Penggunaan:<br> 
                    <div class="bluehilite">RM1.00 sejam (ahli)<br>
                    RM2.00 sejam (bukan ahli)</div> 
                </div>
            <div class="blueBoxRibbon"></div>
            </div>
            
            <div class="blueBox">
            <span class="printer">Perkhidmatan Lain</span>
                    <div class="blueBoxContent">
                    Pencetak dan Fotokopi (hitam/putih)<br>
                    <div class="bluehilite">RM0.20</div> setiap mukasurat<br><br>
                    
                    Pencetak dan Fotokopi (warna)<br>
                    <div class="bluehilite">RM1.00</div> setiap mukasurat<br><br>
                    
                    Pengimbas<br>
                    <div class="bluehilite">RM0.20</div> setiap mukasurat<br><br>
                    </div>
            <div class="blueBoxRibbon"></div>
            </div>
            
            <div class="normalBox">
            <h1>Capaian Pantas</h1>
            <a href="http://www.skmm.gov.my/Resources/eForm/Universal-Service-Provision.aspx">Universal Service Provision</a><br> 
            <a href="http://www.skmm.gov.my/">Suruhanjaya Komunikasi dan Multimedia</a><br>
            <a href="http://www.kkmm.gov.my/index.php?lang=ms">Kementerian Komunikasi dan Multimedia</a><br>
            <a href="#">Service Provider Website</a><br>
            <a href="#">Other Related Website</a> 
            </div>   
            
        </div>
        
        <div id="imageSlider">
            <div class="flexslider">
            <ul class="slides">
             <?php
             if($res_slider):?>
            <?php foreach($res_slider as $row):
                $url    = url::asset("frontend/images/slider/".$row['siteSliderImage']);
                $href   = $row['siteSliderLink'];
                ?>
            <li>
              <img src="<?php echo $url;?>" />
            </li>
            <?php endforeach;?>
            <?php endif;?>   
                <!-- <li>
                  <img src="<?php echo url::asset("skmm/images/slide1.jpg");?>" />
                </li>
                <li>
                  <img src="<?php echo url::asset("skmm/images/slide2.jpg");?>" />
                </li>
                <li>
                  <img src="<?php echo url::asset("skmm/images/slide3.jpg");?>" />
                </li>
                <li>
                  <img src="<?php echo url::asset("skmm/images/slide4.jpg");?>" />
                </li> -->
              </ul>
            </div>
        </div>
        <?php else:
        $no = 2;
        ?>

        <?php endif; ## ?>
        <div style='min-height:450px;<?php if($no == 1):?>width:670px;<?php endif;?>' id="news<?php echo $no;?>" >
        <?php template::showContent();?>
        </div>
        
        <div class="clear"></div>
    </div>
    
    <div id="footer">
        <div id="footerContent">


         

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  //ga('create', 'UA-58096979-1', 'auto');
  ga('create', 'UA-58096979-1', {'cookieDomain': 'none'});
  ga('send', 'pageview');

</script>



          
        © 2014 Semua Hakcipta Terpelihara.
        </div>
    </div>
   
</body>
</html>
