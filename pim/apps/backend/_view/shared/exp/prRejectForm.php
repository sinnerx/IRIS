<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title">PR #<?php echo $pr->prID;?> Rejection </h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<p>Please provide reason of rejection, or leave it empty.</p>
			<form method='post' action="<?php echo url::base('exp/prReject/'.$pr->prID);?>">
			<div class='form-group'>
				<label>Reason</label>
				<?php echo form::textarea('prRejectionReason', 'class="form-control" style="height: 100px;"');?>
			</div>
			<div class='form-group'>
				<?php echo form::submit('Cancel', 'class="btn" value="Cancel" data-dismiss="modal"');?>
				<?php echo form::submit('Submit', 'class="btn btn-danger" value="Reject"');?>
			</div>
			</form>
		</div>
	</div>
</div>