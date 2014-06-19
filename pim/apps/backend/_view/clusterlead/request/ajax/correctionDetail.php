<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Correction detail
			<span style='font-size:11px;display:block;'>
				You have previously sent a change request correction message. Below is the detail. 
			</span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
		<div class='row'>
			<div class='col-sm-9'>
			<b>Subject</b> : <?php echo $typeName;?>
			</div>
		</div>
		<b>Message</b> :<br>
		<?php echo nl2br($siteRequestCorrectionMessage);?>
		</div>
	</div>
</div>