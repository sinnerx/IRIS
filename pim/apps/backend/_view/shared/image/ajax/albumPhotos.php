<div id='photo-list'>
	<h3 class='m-b-xs text-black'>
	<?php echo $row['albumName'];?>
	</h3>
	<?php if(!$res_photo):?>
	<div class='well well-sm'>
	This album has no photo yet. <a href='javascript:album.addPhotoForm.show(<?php echo $row['albumID'];?>);'>Add?</a>
	</div>
	<?php else:?>

	<?php endif;?>
</div>