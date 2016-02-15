<h3 class="m-b-xs text-black">
Public message : <?php echo $referenceNo;?>
</h3>
<div class='well well-sm'>
Message detail
</div>
<div class='row'>

<div class='col-sm-7'>
	<section class='panel panel-default'>
		<div class='panel-heading'>
		Subject : <?php echo $row['messageSubject'];?><div class='pull-right badge badge-primary'><?php echo $category;?></div>
		</div>
		<div class='panel-body' style='min-height:135px;'>
		<?php echo $row['messageContent'];?>
		</div>
		<div class='panel-footer' style="text-align:right;">
			<?php if($row['siteMessageStatus'] == 2):?>
				<div class='row'>
					<div class='col-sm-8' style="text-align:left;">
						<?php if($row['siteMessageRemark'] != ''):?>
						<label>Remark</label>
						<div style="text-align:left;">
							<?php echo $row['siteMessageRemark'];?>
						</div>
						<?php endif;?>
					</div>
					<div class='col-sm-4'>
						<label class='text-success'><span class='fa fa-check-circle'></span> Closed</label>
					</div>
				</div>
			<?php endif;?>
		</div>
	</section>
</div>
<div class='col-sm-5'>
	<section class='panel panel-default'>
		<div class='panel-heading'>
		Contact Information
		</div>
		<div class='panel-body table-responsive'>
		<table class='table'>
			<tr>
				<td width='100px'>Name</td><td>: <?php echo $row['contactName'];?></td>
			</tr>
			<tr>
				<td>Email</td><td>: <?php echo $row['contactEmail'];?></td>
			</tr>
			<tr>
				<td>Phone No.</td><td>: <?php echo $row['contactPhoneNo'];?></td>
			</tr>
		</table>
		</div>
	</section>
</div>
</div>