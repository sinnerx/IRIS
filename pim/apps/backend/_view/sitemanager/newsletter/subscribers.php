<h3 class='m-b-xs text-black'>Subscribers</h3>
<div class='well well-sm'>
List of subscribers on your mailing lists. <?php if($subscribers):?><a href='<?php echo url::base('newsletter/syncSubscriber');?>' class='label label-primary'>Sync</a><?php endif;?>
</div>
<?php echo flash::data();?>
<div class="row">
	<div class="col-sm-6">
		<?php if($subscribers):?>
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
					<tr>
						<td colspan="2">No email(s) subscribed to this list yet.</td>
					</tr>
				<?php endif;?>
			</table>
		</div>
		<?php else:?>
		<div>
			This site hasn't been connected to any mailchimp list ID yet. Please contact the superadmin.
		</div>
		<?php endif;?>
	</div>
</div>