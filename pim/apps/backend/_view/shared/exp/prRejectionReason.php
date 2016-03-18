<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title">PR #<?php echo $pr->prID;?> Rejection Reason</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<p></p>
			<form method='post' action="<?php echo url::base('exp/prReject/'.$pr->prID);?>">
			<div class='form-group'>
				<label>Rejected By</label>
				<span class='pull-right'><?php echo date('g:i A, d-F Y', strtotime($rejection->prRejectionCreatedDate));?></span>
				<p><?php echo $rejection->getUser()->getProfile()->userProfileFullName;?> (<?php echo $rejection->getUser()->getLevelName();?>)</p>
			</div>
			<div class='form-group'>
				<label>Reason</label>
				<p><?php echo $rejection->prRejectionReason ? nl2br($rejection->prRejectionReason) : '- This user did not specify any reason -';?></p>
			</div>
			<?php if(user()->isManager()):?>
				<div class='form-group' style="text-align: right;">
					<a href='<?php echo url::base('exp/prEdit/'.$pr->prID);?>' class='btn btn-primary'>Update PR</a>
				</div>
			<?php endif;?>
			</form>
		</div>
	</div>
</div>