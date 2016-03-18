<style type="text/css">
	

	
</style>
<table style="margin-left: 200px;">
	<?php foreach($sites as $day => $rows):?>
		<tr>
			<td><b style='font-size: 1.5em;'><?php echo $day;?></b></td>
		</tr>
		<?php foreach($rows as $row):?>
			<tr>
				<td style="border-bottom: 1px solid #dadada; padding: 5px;"><?php echo $row['siteName'];?></td>
			</tr>
		<?php endforeach;?>
	<?php endforeach;?>
</table>