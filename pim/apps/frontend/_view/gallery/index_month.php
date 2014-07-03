<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/css/album.css");?>">
<script type="text/javascript" src='<?php echo url::asset("_templates/js/jquery.easydropdown.js");?>'></script>
<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<style type="text/css">
	
.month-left .active-month, .month-right .active-month
{
	background: #009bff;
	color: white;
}



</style>
<script type="text/javascript">
	
var pim = new pim({base_url:"<?php echo url::base('{site-slug}');?>"});
function yearChange()
{
	var y = jQuery("#activityYear").val();
	pim.redirect("gallery/"+y);
}

function monthChange(month)
{
	var y = jQuery("#activityYear").val();
	pim.redirect("gallery/"+y+"/"+month);
}

</script>
<h3 class="block-heading">
<a href='<?php echo url::base("{site-slug}");?>'>Home</a>
<span class="subheading"> >
<a href='<?php echo url::base("{site-slug}/gallery");?>'>Galeri Foto</a> > 
<a href='<?php echo url::base("{site-slug}/gallery/$year");?>'><?php echo $year;?></a> > 
<?php echo model::load("helper")->monthYear("month",$month);?></span></h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description"> 
		Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.
		</div>
		<div class="page-sub-wrapper album-page clearfix">
			<div class="album-gallery-heading">
				<div class="album-category">
				<!-- <select class="dropdown" tabindex="9" data-settings='{"wrapperClass":"flat-type"}'>
					<option value="1">Pilih Album</option>
					<option value="2">Tahun</option>
					<option value="3">Aktiviti</option>
					<option value="4">Tarikh</option>
				</select> -->
				</div>
				<h5 class="album-heading">Album <?php echo model::load("helper")->monthYear("month",$month)." ".$year;?></h5>
			</div>
			<div class="activity-year-select clearfix">
				<div class="month-left">
					<ul>
						<?php
						$monthLeft	= Array(1=>"Jan",2=>"Feb",3=>"Mac",4=>"Apr",5=>"Mei",6=>"Jun");
						foreach($monthLeft as $m=>$name):
						$active	= $month==$m?"class='active-month'":"";?>
						<li><a <?php echo $active;?> href='javascript:monthChange(<?php echo $m;?>);'><?php echo $name;?></a></li>
						<?php endforeach;?>
					</ul>
				</div>
				<div class="select-year-activity">
					<?php echo form::select("activityYear",model::load("helper")->monthYear("year",date("Y")-4,date("Y")+1),"onchange='yearChange();' class='dropdown' data-settings='{\"wrapperClass\":\"select-year\"}'",$year,false);?>
				</div>
				<div class="month-right">
					<ul>
						<?php
							$monthRight	= Array(7=>"Jul",8=>"Ogs",9=>"Sep",10=>"Okt",11=>"Nov",12=>"Dis");
							foreach($monthRight as $m=>$name):
							$active	= $month==$m?"class='active-month'":"";?>
							<li><a <?php echo $active;?> href='javascript:monthChange(<?php echo $m;?>);'><?php echo $name;?></a></li>
							<?php
							endforeach;?>
					</ul>
				</div>
			</div>
			<?php if($res_album):?>
			<div class="album-page-gallery">
				<ul>
					<?php foreach($res_album as $row):

					$albumName	= $row['albumName'];
					$totalPhoto	= $photoTotalR[$row['siteAlbumID']];
					$url		= model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);
					list($year,$month)	= explode(" ",date("Y m",strtotime($row['albumCreatedDate'])));
					$href		= url::base("{site-slug}/gallery/$year/$month/".($row['siteAlbumSlug']?:"id/".$row['siteAlbumID']));


					?>
					<li>
						<div class="gallery-thumnbail"><a href="<?php echo $href;?>"><img src="<?php echo $url;?>" width="270" alt=""/></a>
						<div class="album-comments-count">0</div></div>
						<div class="album-gallery-info">
						<div class="album-name"><?php echo $albumName;?></div>
						<div class="album-count-gallery"><?php echo $totalPhoto?:"Tiada";?> Gambar</div>
						</div>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
			<?php else:?>
			<div style="text-align:center;">Tiada album untuk bulan ini.</div>
			<?php endif;?>

		</div>
	</div>
</div>