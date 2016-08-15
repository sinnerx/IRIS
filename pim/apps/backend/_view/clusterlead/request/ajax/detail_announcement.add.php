<?php
$announcementID = $row['announcementID'];
$announcementEditUrl = url::base("cluster/editAnnouncement/".$announcementID."/".$requestID);
?>
<div class='request-info'>
New / Delete Site Announcement
</div>
<script>
$( "#btnPanel" ).append( "<a href='<?php echo $announcementEditUrl;?>' class='btn-approval pull-right fa fa-pencil-square-o'></a>" );
</script>
<div class='table-responsive'>
<table class='table'>
	<tr>
		<td width='100px'>Announcement</td><td><?php echo $row['announcementText'];?></td>
	</tr>
	<tr>
		<td>Validate until</td><td><?php echo date("d-F Y",strtotime($row['announcementExpiredDate']));?></td>
	</tr>
	<tr>
		<td>Link</td><td><?php echo $row['announcementLink'];?></td>
	</tr>
</table>
</div>