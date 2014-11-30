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

<body>
    <div id="header">
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
                <div class="socialIcon"><a href="#" target="_blank"><img src="<?php echo url::asset("skmm/images/fb-icon.png");?>" width="24" height="24" alt="facebook"></a></div>
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
            <a href="#">Universal Service Provision</a><br> 
            <a href="#">Suruhanjaya Komunikasi dan Multimedia</a><br>
            <a href="#">Kementerian Komunikasi dan Multimedia</a><br>
            <a href="#">Service Provider Website</a><br>
            <a href="#">Other Related Website</a> 
            </div>   
            
        </div>
        
        <div id="imageSlider">
            <div class="flexslider">
            <ul class="slides">
             <?php
            foreach($res_slider as $row):
                $url    = url::asset("frontend/images/slider/".$row['siteSliderImage']);
                $href   = $row['siteSliderLink'];
                ?>
            <li>
              <img src="<?php echo $url;?>" />
            </li>
            <?php endforeach;?>   
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
        <div style='min-height:450px;' id="news<?php echo $no;?>">
        <?php template::showContent();?>
        </div>
        
        <div class="clear"></div>
    </div>
    
    <div id="footer">
        <div id="footerContent">
        © 2014 Semua Hakcipta Terpelihara.
        </div>
    </div>
   
</body>
</html>
