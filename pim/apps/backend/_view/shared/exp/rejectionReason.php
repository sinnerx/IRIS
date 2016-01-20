<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><?php echo $title;?></h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<p></p>
			<div class='form-group'>
				<label>Rejected By</label>
				<span class='pull-right'><?php echo date('g:i A, d-F Y', strtotime($rejectionDate));?></span>
				<p><?php echo $rejection->getUser()->getProfile()->userProfileFullName;?> (<?php echo $rejection->getUser()->getLevelName();?>)</p>
			</div>
			<div class='form-group'>
				<label>Reason</label>
				<p><?php echo $reason ? nl2br($reason) : '- This user did not specify any reason -';?></p>
			</div>
			<?php if(user()->isManager()):?>
				<div class='form-group' style="text-align: right;">
					<a href='<?php echo $rlEditLink;?>' class='btn btn-primary'>Update <?php if($isRL):?>RL<?php else:?>PR<?php endif;?></a>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>