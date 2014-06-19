<div class='request-info'>
	New Article : <u><?php echo $row['articleName'];?></u>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th width="200px">To be published after</th><td><?php echo $row['articlePublishedDate'];?></td>
				</tr>
				<tr>
					<td colspan='2' rowspan="2">
						<?php echo $row['articleText'];?>
					</td>
					<td colspan="2">
						<b>Categories</b><br>
						<?php 
						$address	= $row['activityAddressFlag'] == 1?model::load("access/auth")->getAuthData("site","siteInfoAddress"):$row['activityAddress'];
						echo $address;?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>Tags</b><br>
						<?php echo nl2br($row['activityDescription']);?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>