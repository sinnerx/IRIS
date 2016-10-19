<h3 class="m-b-xs text-black">
Public message : <?php echo $message->getEncryptedID();?>
</h3>
<div class='well well-sm'>
Message detail
</div>
<div class='row'>

<div class='col-sm-7'>
	<section class='panel panel-default'>
		<div class='panel-heading'>
			<?php //var_dump($message); ?>
		Subject : <?php echo $message->messageSubject;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo "Date: ".$message->messageCreatedDate;?> <div class='pull-right badge badge-primary'><?php echo $message->getCategory();?></div>
		</div>
		<div class='panel-body' style='min-height:135px;'>
		<?php echo $message->messageContent;?>
		</div>
		<div class='panel-footer' style="text-align:right;">
			<?php if($message->siteMessageStatus != 2):?>
				<label><a href='<?php echo url::base('site/messageClose/'.$message->siteMessageID);?>' data-toggle='ajaxModal' class='text-success'><span class='fa fa-circle-o'></span> Mark as closed</a></label>
			<?php else: // message has been marked as closed?>
				<div class='row'>
					<div class='col-sm-8' style="text-align:left;">
						<?php if($message->siteMessageRemark != ''):?>
						<label>Remark</label>
						<div style="text-align:left;">
							<?php echo $message->siteMessageRemark;?>
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
				<td width='100px'>Name</td><td>: <?php echo $message->contactName;?></td>
			</tr>
			<tr>
				<td>Email</td><td>: <?php echo $message->contactEmail;?></td>
			</tr>
			<tr>
				<td>Phone No.</td><td>: <?php echo $message->contactPhoneNo;?></td>
			</tr>
		</table>
		</div>
	</section>
</div>
</div>