<?php
$no = 1;
$opened = false;
if($res_photos):

## if got album info.
if($row_album)
{?>
<h5 style="margin-top:0px;"><?php echo $row_album['albumName'];?></h5>
<?php
}

foreach($res_photos as $row)
{
	$url	= model::load("image/services")->getPhotoUrl($row['photoName']);

	if($no == 1)
	{
		$opened	= true;
		echo "<div class='row'>";
	}
?>
<div class='col-sm-3 photo-list' data-photopath='<?php echo $row['photoName'];?>' data-photourl='<?php echo $url;?>'>
	<div>
		<img src='<?php echo $url;?>' width='100%' />
	</div>
</div>
<?php
if($no == 4)
	{
		echo "</div>";
		$opened	= false;
		$no = 0;
	}
	$no++;
}

if($opened)
{
	echo "</div>";
}?>
<div class='row ajxgal-pagination'>
	<div class='col-sm-4 pull-right'>
	<?php echo pagination::link();?>
	</div>
</div>
<?php
else:?>
No photo yet.
<?php endif;?>