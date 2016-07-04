<div class='request-info'>
New / Delete Site Announcement
</div>
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