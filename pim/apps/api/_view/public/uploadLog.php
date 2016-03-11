<table>
	<tr>
		<th>Site</th>
		<th>Time start</th>
		<th>Time taken</th>
		<th>Number of transactions</th>
	</tr>
	<?php foreach($uploads as $upload):?>
	<tr>
		<td><?php echo $upload['siteName'];?></td>
		<td><?php echo date('d-m-Y g:i A', strtotime($upload['billingTransactionUploadCreatedDate']));?></td>
		<td><?php echo strtotime($upload['billingTransactionUploadCompletedDate']) - strtotime($upload['billingTransactionUploadCreatedDate']);?> seconds</td>
		<td><?php echo $upload['billingTransactionUploadTotal'];?></td>
	</tr>
	<?php endforeach;?>
</table>