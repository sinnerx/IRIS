<?php
if(!$res_album):?>
<p style="opacity:0.8;">You have no album at all for the site.</p>
<?php
else:
$no = 1;
$opened = false;
foreach($res_album as $row){
	$coverimageUrl	= model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);

	if($no == 1)
	{
		$opened	= true;
		echo "<div class='row'>";
	}
	?>
	<div class='col-sm-3 album-list' data-said='<?php echo $row['siteAlbumID'];?>'>
			<div class='panel-heading'>
			<?php echo $row['albumName'];?>
			</div>
			<div>
			<img src='<?php echo $coverimageUrl;?>' width='100%' />
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
}
endif;?>