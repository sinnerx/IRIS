<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/css/album.css");?>">
<script type="text/javascript" src='<?php echo url::asset("_templates/js/jquery.easydropdown.js");?>'></script>
<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<script type="text/javascript">
	
var pim = new pim({base_url:"<?php echo url::base('{site-slug}');?>"});
function yearChange()
{
	var y = jQuery("#activityYear").val();
	pim.redirect("galeri/"+y);
}

function monthChange(month)
{
	var y = jQuery("#activityYear").val();
	pim.redirect("galeri/"+y+"/"+month);
}

</script>
<h3 class="block-heading">
<?php
echo model::load("template/frontend")
->buildBreadCrumbs(Array(
			Array("Galeri Foto"),
			Array($year)
						));

?>
 </h3>
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
				<h5 class="album-heading">Album <?php echo $year;?></h5>
			</div>
			<div class="activity-year-select clearfix">
				<div class="month-left">
					<ul>
						<li><a href="javascript:monthChange(1);">Jan</a></li>
						<li><a href="javascript:monthChange(2);">Feb</a></li>
						<li><a href="javascript:monthChange(3);">Mac</a></li>
						<li><a href="javascript:monthChange(4);">Apr</a></li>
						<li><a href="javascript:monthChange(5);">Mei</a></li>
						<li><a href="javascript:monthChange(6);">Jun</a></li>
					</ul>
				</div>
				<div class="select-year-activity">
					<?php echo form::select("activityYear",model::load("helper")->monthYear("year",date("Y")-4,date("Y")+1),"onchange='yearChange();' class='dropdown' data-settings='{\"wrapperClass\":\"select-year\"}'",$year,false);?>
				</div>
				<div class="month-right">
					<ul>
						<li><a href="javascript:monthChange(7);">Jul</a></li>
						<li><a href="javascript:monthChange(8);">Ogs</a></li>
						<li><a href="javascript:monthChange(9);">Sep</a></li>
						<li><a href="javascript:monthChange(10);">Okt</a></li>
						<li><a href="javascript:monthChange(11);">Nov</a></li>
						<li><a href="javascript:monthChange(12);">Dis</a></li>
					</ul>
				</div>
			</div>
			<!-- start of the loop -->
			<div class="album-page-gallery">
			<?php
			$monthR	= Array("JANUARI","FEBRUARI","MAC","APRIL","MEI","JUN","JULAI","OGOS","SEPTEMBER","OKTOBER","NOVEMBER","DISEMBER");

			## loop from top;
			for($i=count($monthR)-1;$i>=0;$i--)
			{
				$monthName	= $monthR[$i];
				## increment 1 to index.
				$no = $i+1;

				if(!isset($res_album[$no]))
					continue;

				?>
				<div class="album-gallery-heading">
					<h5 class="album-heading"><?php echo $monthName;?></h5>
				</div>
				<ul>
					<?php foreach($res_album[$no] as $row):

					$albumName	= $row['albumName'];
					$totalPhoto	= $photoTotalR[$row['siteAlbumID']];
					$url		= model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);
					list($year,$month)	= explode(" ",date("Y m",strtotime($row['albumCreatedDate'])));
					$href		= url::base("{site-slug}/galeri/$year/$month/".($row['siteAlbumSlug']?:"id/".$row['siteAlbumID']));

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
				<?php
			}
			if(!$res_album)
			{
				echo "<div style='text-align:center;'>Tiada album untuk tahun ini.</div>";
			}

			?>
			</div>
		</div>
	</div>
</div>