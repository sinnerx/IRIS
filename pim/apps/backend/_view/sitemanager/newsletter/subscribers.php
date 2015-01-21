<h3 class='m-b-xs text-black'>Subscribers</h3>
<div class='well well-sm'>
List of subscribers to your mailing lists.
</div>
<?php echo flash::data();?>
<div class="row">
	<div class="col-sm-6">
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th>No.</th><th>Email</th>
				</tr>
				<?php if($subscribers['total'] > 0):?>
				<?php $no = 1;?>
				<?php foreach($subscribers['data'] as $row):?>
					<tr>
						<td style="width:15px;"><?php echo $no;?>.</td>
						<td><?php echo $row['email'];?></td>
					</tr>
				<?php $no++;?>
				<?php endforeach;?>
				<?php else:?>

				<?php endif;?>
			</table>
		</div>
	</div>
</div>