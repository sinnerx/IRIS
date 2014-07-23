<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if (IE 9)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Pusat Internet 1 Malaysia - Hubungi Kami</title>   

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
    
    <style type="text/css">
    #category-container label
    {
      display: inline-block;
      padding:0 5px 0 5px;
    }
    .contact-form table
    {
      width:400px;
    }
    .contact-form table tr td:first-child
    {
      color:#009bff;
      font-weight: bold;
    }

    .contact-form
    {
      border-top:1px dashed #5f5f5f;
      padding-top:20px;
    }

     .content-info b
    {
      color:#009bff;
      margin-left: 0px;
    }

    .contact-form input[type=text], .contact-form textarea
    {
      width: 93%;
    }

    .contact-form input[type=submit]
    {
      background: #009bff;
      color:white;
      padding:5px;
      border:0px;
    }

    </style>
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
            
            <h3>Hubungi Kami</h3>
            <div class="content-wrap">
           
             <div id="Default" class="contentHolder">
      <div class="content">
      Maklumat kami untuk dihubungi

      <div class='content-info'><b>EMEL LAMAN : </b> support@celcom1cbc.com </div>
      <div class='content-info'><b>NO. TELEFON : </b> 03-7451 8082 </div>
      <div class='content-info'><b>NO. FAX : </b> 03- 7451 8081 </div>
      <div class='content-info'><b>ALAMAT :</b>

      <p>Pengurusan Operasi Pusat Internet 1Malaysia,
      NuSuara Technologies Sdn Bhd
      Unit No. 2-19-01, Block 2, VSQ@PJ City Centre,
      Jalan Utara 46200 Petaling Jaya, Selangor.</p>
      </div>
      
      <p>
      Jika anda mempunyai sebarang aduan atau pertanyaan, sila hubungi 
      kami menerusi borang online di bawah ini. Kami mengalu-alukan 
      sebarang maklum balas daripada anda
      </p>
      <div class='contact-form'>
        <table>
          <tr>
            <td style="width:80px;">Kategori</td><td id='category-container'>: <?php echo form::radio("siteMessageCategory",$categoryNameR,null,null,"<div style='display:inline;'> {content} </div>");?></td>
          </tr>
          <tr>
            <td>Nama</td><td>: <?php echo form::text("contactName");?></td>
          </tr>
          <tr>
            <td>Email</td><td>: <?php echo form::text("contactEmail");?></td>
          </tr>
          <tr>
            <td>Telefon</td><td>: <?php echo form::text("contactPhoneNo");?></td>
          </tr>
          <tr>
            <td>Tajuk</td><td>: <?php echo form::text("messageSubject");?></td>
          </tr>
          <tr>
            <td>Mesej</td><td>: <?php echo form::textarea("messageContent","style='height:120px;'");?></td>
          </tr>
          <tr>
            <td></td><td><input class='pull-right' type='submit' value='Hantar' /></td>
          </tr>
        </table>
      </div>
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
<script src="<?php echo url::asset("_landing/js/hubungi-relative.js");?>"></script> <!-- Default JS -->
<!-- End Js -->


</body>
</html>