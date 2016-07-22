<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Pusat Internet 1Malaysia</title>
<link href="<?php echo url::asset("skmm/css/reset.css");?>" rel="stylesheet" type="text/css">
<link href="<?php echo url::asset("skmm/css/style.css?v20160106");?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("_scale/css/font-awesome.min.css");?>">
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
          <a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>" style="color: #fda319;">Log Masuk</a> <span style='font-size:0.8em;color:#fdcf84;'>│</span> <a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>"style="color: #fda319;">Daftar</a>
          </div>
        <div id="splogo" style="position:relative;top:10px;">
           
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
                <li><a href="<?php echo url::base("{site-slug}/mengenai-kami");?>">Maklumat Setempat</a>
                <ul>
                    <?php foreach($pages as $page):
                    if($page['pageDefaultType'] == 1)
                        continue;
                    ?>
                        <li><a href="<?php echo url::base('{site-slug}/mengenai-kami/'.$page['pageDefaultSlug']);?>"><?php echo $page['pageDefaultName'];?></a></li>
                    <?php endforeach;?>
                    <!-- <li><a href="#">Sub 1</a></li>
                    <li><a href="#">Sub 2</a></li>
                    <li><a href="#">Sub 3</a></li>
                    <li><a href="#">Sub 4</a></li>
                    <li><a href="#">Sub 5</a></li>
                    <li><a href="#">Sub 6</a></li> -->
                </ul>    
                </li>
                <li><a href="<?php echo url::base("{site-slug}/aktiviti");?>">Jadual Latihan & Aktiviti</a></li>
                <li><a href="<?php echo url::base("{site-slug}/galeri");?>">Galeri</a></li>
                <li><a href="<?php echo url::base("{site-slug}/faq");?>">Soalan Lazim</a></li>
                <li><a href="<?php echo url::base("{site-slug}/hubungi-kami");?>">Hubungi Kami</a></li>
                <div class="socialIcon">
                <?php if($links['siteInfoFacebookUrl'] != ""):?>
                    <a href="<?php echo url::createByRoute("api-redirect-link",Array(),true)."?link=$links[siteInfoFacebookUrl]";?>" target="_blank"><img src="<?php echo url::asset("skmm/images/fb-icon.png");?>" width="24" height="24" alt="facebook"></a>
                <?php endif;?>

                </div>
                <div class="socialIcon">
                <a target='_blank' href='<?php echo url::base("{site-slug}/blog/rss");?>' class="fa fa-rss" style="color:white;font-size:24px;" ></a>

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
                    <div class="bluehilite">
                  <?php if(in_array(authData("current_site.stateID"),Array(12,13))):?>
                    8.00 pagi - 5.00 petang
                  <?php else:?>
                    9.00 pagi - 6.00 petang
                  <?php endif;?>

                    </div>
                    (kecuali cuti umum)<br><br>
                    
                    Yuran keahlian:<br> 
                    <div class="bluehilite">RM5.00</div>
                    (tertakluk kepada terma dan syarat)<br><br>
                    
                    Caj Penggunaan:<br> 
                    <div class="bluehilite">RM1.00 sejam (ahli)<br>
                    RM2.00 sejam (bukan ahli)</div> <br><br>

                     Pelajar:<br> 
                    <div class="bluehilite">PERCUMA</div>
                    -Berumur 20 tahun kebawah; atau <br>
                    -Mempunyai kad pelajar (Universiti dll)<br><br>

                    Warga Emas:<br> 
                    <div class="bluehilite">PERCUMA</div>
                    *berumur 60 tahun keatas<br>
                    -Perlu mengemukakan kad pengenalan<br><br>

                    Orang Kelainan Upaya (OKU):<br> 
                    <div class="bluehilite">PERCUMA</div>
                    -Perlu mengemukakan kad OKU; atau<br>
                    -atas budibicara Penyelia<br><br>
                </div>
            <div class="blueBoxRibbon"></div>
            </div>
            
            <div class="blueBox">
            <span class="printer">Perkhidmatan Lain</span>
                    <div class="blueBoxContent">
                    Mencetak (hitam/putih)<br>
                    <div class="bluehilite">Ahli RM0.40</div>
                    <div class="bluehilite">Bukan Ahli RM0.70</div> 1 hingga 5 mukasurat * (A4)<br><br>

                    <div class="bluehilite">Ahli RM0.20</div>
                    <div class="bluehilite">Bukan Ahli RM0.50</div> 5 dan lebih mukasurat * (A4)<br>
                    --------------------------------------------
                    <br><br> Mencetak (warna)<br>
                    <div class="bluehilite">Ahli RM0.70</div>
                     <div class="bluehilite">Bukan Ahli RM1.00</div> 1 hingga 5 mukasurat * (A4)<br><br>

                     <div class="bluehilite">Ahli RM0.50</div>
                     <div class="bluehilite">Bukan Ahli RM1.00</div> 5 dan lebih mukasurat * (A4)<br>
                      --------------------------------------------
                    <br><br>Pengimbas<br>
                    <div class="bluehilite">Ahli RM0.10</div>
                    <div class="bluehilite">Bukan Ahli RM0.30</div> sekeping<br>
                    --------------------------------------------
                    <br><br> Laminate (warna)<br>
                    <div class="bluehilite">Ahli RM2.00</div>
                     <div class="bluehilite">Bukan Ahli RM2.50</div> saiz A4<br><br>

                     <div class="bluehilite">Ahli RM1.00</div>
                     <div class="bluehilite">Bukan Ahli RM1.50</div>saiz IC<br>
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
              <a href='' ><img src="<?php echo $url;?>" /></a>
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
  ga('create', '<?php echo model::load("api/ga")->gaid; ?>', {'cookieDomain': 'none'});
  ga('send', 'pageview');

</script>



          
        © 2014 Semua Hakcipta Terpelihara.
        </div>
    </div>
   
</body>
</html>