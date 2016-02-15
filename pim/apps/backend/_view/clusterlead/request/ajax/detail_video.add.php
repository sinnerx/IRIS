<div class='request-info'>
New Video
</div>
<div class='table-responsive'>
<table class='table'>
	<TR>
		<td>On Album</td><td><?php echo $row['videoAlbumName'];?></td>
	</TR>
	<tr>
		<td width='200px'>Video Name</td><td><?php echo $row['videoName'];?></td>
	</tr>
	<tr>
		<td>Video</td><td><?php
		$url	= model::load("video/album")->buildEmbedVideoUrl($row['videoType'],$row['videoRefID']);?>
		<?php #$url = "http://www.youtube.com/embed/$row[videoRefID]"; ?>
		<embed src="<?php echo $url;?>" style="width:100%;height:250px;" scale="aspect" controller="true">
		</td>
	</tr>
	<tr>
		
	</tr>
</table>
</div>