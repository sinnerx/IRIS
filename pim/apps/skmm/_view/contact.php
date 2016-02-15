<style type="text/css">
	
.radio-skmm label
{
	display:inline;
}

.alert.alert-danger
{
	padding:10px 0 10px 0;
	color:red;
	font-weight: bold;
	display: block;
}

.msgbox.success
{
	font-weight: bold;
	color: #375cbd;
	display: block;
	padding: 10px 0 10px 0;
}

.label.label-danger
{
	color:red;
	display: inline;
}

</style>
<h1>Hubungi Kami</h1>

<?php
$latitude	= authData("current_site.siteInfoLatitude");
$longitude	= authData("current_site.siteInfoLongitude");
// $latitude	= $latitude == ""?"3.46419":$latitude;
// $longitude	= $longitude == ""?"101.65987070000006":$longitude;
?>
<?php if($latitude != "" && $longitude != ""):?>
<div id="containerMap">
    <div class="border">
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&sensor=false"></script><div id="gmap_canvas" style="height:282px; width:100%;"></div><style type="text/css" media="screen">#gmap_canvas img{max-width:none !important;background:none !important;}.gm-style-iw span{height:auto !important;display:block;white-space:nowrap;overflow:hidden !important;}.gm-style-iw strong{font-weight:400;}.map-data{ position:absolute;top:-1668px;}.gm-style-iw{ height:auto !important;color:#000000;display:block;white-space:nowrap;width:auto !important;line-height:18px;overflow:hidden !important;}</style><iframe id="data" src="http://www.addmap.org/maps.php" class="map-data"></iframe><a id="get-map-data" href="http://www.stromleo.de"class="map-data">stromleo.de</a><script type="text/javascript">function init_map(){ var myOptions={zoom:14, center: new google.maps.LatLng (<?php echo $latitude.",".$longitude;?>), mapTypeId: google.maps.MapTypeId.ROADMAP}; map = new google.maps.Map (document.getElementById("gmap_canvas"), myOptions); marker = new google.maps.Marker({map: map, position: new google.maps.LatLng (<?php echo $latitude.",".$longitude;?>)}); infowindow = new google.maps.InfoWindow ({content:"<span style='height:auto !important; display:block; white-space:nowrap; overflow:hidden !important;'><strong style='font-weight:400;'>Pusat Internet 1Malaysia</strong><br><?php echo authData('current_site.siteName');?><br> </span>" }); google.maps.event.addListener (marker, "click", function(){ infowindow.open(map,marker);}); infowindow.open(map,marker);} google.maps.event.addDomListener (window, "load", init_map);</script>
    </div>
    </div>
<?php endif;?>

<p>Anda boleh menghubungi kami dengan mengisi borang berikut:</p>
<form class="white-pink" id='the-form' method='post'>
<h1>Borang Maklumbalas</h1>
<?php echo flash::data();?>
<div class="white-pink-left">
<label>Kategori <?php echo flash::data("siteMessageCategory");?></label>
<?php echo form::radio("siteMessageCategory",$categoryNameR,null,null,"<div class='radio-skmm' style='display:inline;'> {content} </div>");?>

<label style="margin-top:10px;">Nama <?php echo flash::data("contactName");?></label> 
<?php echo form::text("contactName","placeholder='Sila masukkan nama anda' tabindex='1'");?>

<label>Email <?php echo flash::data("contactEmail");?></label> 
<?php echo form::text("contactEmail","placeholder='Sila masukkan alamat email' tabindex='2'");?>

<label>No. Telefon <?php echo flash::data("contactPhoneNo");?></label> 
<?php echo form::text("contactPhoneNo","placeholder='Sila masukkan nombor telefon anda. Contohnya : 0126966111.' tabindex='3'");?>

<label>Tajuk <?php echo flash::data("messageSubject");?></label>
<?php echo form::text("messageSubject","placeholder='Sila masukkan tajuk ' tabindex='4'");?>

<label>Mesej <?php echo flash::data("messageContent");?></label> 
<?php echo form::textarea("messageContent","placeholder='Taip mesej anda di sini' tabindex='5'");?>

<!-- <label><input type='submit' class='button' value='Hantar' /></label> -->
<label><a href="#" onclick='$("#the-form").submit();' class="button">Hantar</a></label>
</div>

<div class="white-pink-right"> 
<p><strong>Pusat Internet 1Malaysia</strong><br>
<?php echo $siteInfoAddress == ""?"-":nl2br($siteInfoAddress);?>
<p><strong>Koordinat GPS:</strong> <?php if($siteInfoLatitude != "" && $siteInfoLongitude != ""):?><?php echo $siteInfoLatitude;?>, <?php echo $siteInfoLongitude;?><?php else:?> - <?php endif;?></p>  
<p><strong>Pengurus:</strong> <?php echo $siteManagers[0]['userProfileFullName'];?><br>
<strong>Pen. Pengurus:</strong> <?php echo $siteManagers[1]['userProfileFullName'];?><br><br>
<strong>Tel:</strong> <?php echo $siteInfoPhone?:"-";?><br>
<!-- <strong>Fax:</strong> +603 6048 1074</p> -->
<p><strong>Email:</strong> <a href="#"><?php echo $siteInfoEmail?:"-";?></a></p>
</div>
<div class="clear"></div>
</form>