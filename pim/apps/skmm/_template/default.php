<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Calent</title>
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
          <a href="<?php echo url::base("{site-slug}/registration#horizontalTab1");?>" style="color:  #29a3a3;">Log Masuk</a> <span style='font-size:0.8em;color: #29a3a3;'>│</span> <a href="<?php echo url::base("{site-slug}/registration#horizontalTab2");?>"
          style="color:  #29a3a3;">Daftar</a>
          </div>
        <div id="splogo" style="position:relative;top:10px;">
        </div>
        <div id="siteLogo">
        <img src="<?php echo url::asset("skmm/images/calent_logo.png");?>" width="79" height="56" alt="Calent">
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



          
        © 2017 Semua Hakcipta Terpelihara.
        </div>
    </div>
   
</body>
</html>