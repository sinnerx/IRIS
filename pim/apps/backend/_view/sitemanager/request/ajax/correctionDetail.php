<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Request changes correction message
			<span style='font-size:11px;display:block;'>
				Your clusterlead has sent message regarding your latest changes about <u><?php echo $typeName;?></u>. Below is the detail.
			</span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class='row'>
				<div class='col-sm-9'>
				<b>Subject</b> : <?php echo $typeName;?>
				</div>
				<div class='col-sm-3 pull-right' style='text-align:right;'>
				<a href='<?php echo $urlToSubject;?>'>Link to subject ></a>
				</div>
			</div>
			<b>Message</b> :<br>
			<?php echo nl2br($row['siteRequestCorrectionMessage']);?>
		</div>
	</div>
</div>