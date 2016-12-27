<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("div.panel_button").click(function(){
		jQuery("div#panel").animate({
			height: "650px"
		})
		.animate({
			height: "550px"
		}, "fast");
		jQuery("div.panel_button").toggle();
	
	});	
	
   jQuery("div#hide_button").click(function(){
		jQuery("div#panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
   
   jQuery("div#hide_button_top").click(function(){
		jQuery("div#panel").animate({
			height: "0px"
		}, "fast");
		
	
   });	
	
});

</script>
<style type="text/css">
	
.panel_button {
	margin-left: auto;
	margin-right: auto;
	position: absolute;

	width: 210px;
	height: 50px;
	
	z-index: 20;
	
	cursor: pointer;
	background:url(../images/calent_logo.png) no-repeat top center;
}
.panel_button img {
	position: relative;
	top: 10px;
	border: none;
}
#show_button a,
#hide_button a {
	text-indent:-9999px;
	display:block;
}
.panel_button a:hover {
	color: #999999;
}

#toppanel {
	position:relative;
	width: 100%;
	left: 0px;
	z-index: 99999;
	text-align: center;

}
#panel {
	width: 100%;
	position: relative;

	height: 0px;
	margin-left: auto;
	margin-right: auto;
	z-index: 10;
	overflow: hidden;
	text-align: left;
	background:#000;
}
#panel_contents {

	height: 100%;
	width: 1200px;
	margin:auto;
	z-index: -1;
	color:#FFF;
	margin-top:50px;
	
	font-size:12px;
}

#hide_button{
	background:url(../images/pim_top_close.jpg) no-repeat top left;
	
	
}


.panel-wrap{
	position:relative;
	width:990px;
	margin:0px auto;
	
}

#hide_button_top{
	background:none !important;
	right:0px;
	
	
	
}

#hide_button_top a{
	color:#FFF;
	background:#009bff;
	padding:5px 20px;
	font-size:12px;
	
}

.top-close a{
text-indent:0px !important;
display:block !important;
}

#panel_contents h3{
	font-size:12px;
	margin:0px;
	padding:0px;
	margin-bottom:10px;
	text-transform:uppercase;
	
	
}

.col-top{
	display:inline-table;
	width:138px;

	margin-bottom:30px;

	
}




.col-top ul{
	margin:0px;
	padding:0px;
	margin-right:10px;
	float:left;
	
	
	
}


.col-top ul li{
	padding-left:15px;
	list-style:none;
	background:url(<?php echo url::asset("frontend/images/top_bullet.jpg");?>) no-repeat left 6px;
	
}

.col-top a{
	color:#FFF;
	   -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
	
	
}

.col-top a:hover{
	color:#009BFF;
	
}


.col-large ul{
	width:186px;
	margin:0px;
	padding:0px;
	margin-right:10px;
	float:left;
	
}

.col-large ul li{
	
	padding-left:15px;
	list-style:none;
	background:url(<?php echo url::asset("frontend/images/top_bullet.jpg");?>) no-repeat left 6px;
	
}

.col-large ul li a{
	color:#FFF;
	   -webkit-transition: all 0.3s ease-out;
   -moz-transition: all 0.3s ease-out;
   -ms-transition: all 0.3s ease-out;
   -o-transition: all 0.3s ease-out;
   transition: all 0.3s ease-out;
	
}


.col-large a:hover{
	color:#009BFF;
	
}

</style>
<div id="toppanel">
    <div id="panel">
    <div class="wrap"><div class="panel_button" id="hide_button_top" style="display: none;"> <a href="#"><i class="fa fa-times-circle-o"></i> Close</a> </div></div>
      <div id="panel_contents">
      <?php
      foreach($stateR as $stateID => $stateName)
      {
      	## is not sabah.
      	if($stateID != 12)
      	{
      		if(isset($res_site[$stateID]))
      		{
      			echo "<div class='col-top'>";
      			echo "<h3>$stateName</h3>";
      			echo "<ul>";
      			foreach($res_site[$stateID] as $row)
      			{
      				$url	= url::base($row['siteSlug']);
      				$name	= $row['siteName'];
      				echo "<li><a href='$url'>$name</a></li>";
      			}
      			echo "</ul>";
      			echo "</div>";
      		}
      	}
      	## is sabah.
      }

      ## sabah part.
      if(isset($res_site[12]))
      {
      	echo "<div class='col-large'>";
  		echo "<h3>Sabah</h3>";

  		## devide by 5 column.
  		$totalPerColumn	= ceil(count($res_site[12])/5);

  		$ULopened	= false;
  		$currNo		= 1;
  		foreach($res_site[12] as $row)
  		{
  			if(!$ULopened)
  			{
  				$ULopened = true;
  				echo "<ul>";
  			}
  			$url	= url::base($row['siteSlug']);
  			echo "<li><a href='$url'>".$row['siteName']."</a></li>";

  			if($currNo == $totalPerColumn)
  			{
  				echo "</ul>";
  				$ULopened = false;
  				continue;
  			}
  			$currNo++;
  		}
  		if($ULopened)
  		{
  			echo "</ul>";
  			$ULopened = false;
  		}

  		echo "</div>";## end of col-large
      }

      ?>
      </div><!-- end of #panel_contents -->
     
      
    </div>
    <div class="panel-wrap">
    <div class="panel_button" style="display: visible;" id="show_button"> <a href="#">Login Here</a> </div>
    <div class="panel_button" id="hide_button" style="display: none;"> <a href="#">Hide</a> </div>
  </div>
  
  </div>


<?php
/*if(count($siteListR) > 0):
foreach($siteListR as $siteID=>$row)
{
	$slug	= $row['siteSlug'];
	$name	= ucwords($row['siteName']);
	$state	= $stateR[$row['stateID']];
	$href	= url::base("$slug");
	echo "<li><a href='$href'>$name, $state</a></li>";
}
endif;*/
?>