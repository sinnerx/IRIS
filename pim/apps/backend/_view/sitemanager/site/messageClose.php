<div class="modal-dialog">
	<form method='post' id='myform' action="<?php echo url::base('site/messageClose/'.$siteMessageID);?>">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="modal-title">Close Message
				<span style='font-size:11px;display:block;'>
					Close this issue with a remark (optional).
				</span>
				</h4>
			</div>
			<div class="modal-body" style='padding-bottom:0px;'>
				<?php echo form::textarea('siteMessageRemark', 'class="form-control" style="height:150px;"');?>
			</div>
			<div class="modal-footer">
				<input type='button' onclick='if(confirm("Mark this issue as closed. Are you sure?")){$("#myform").submit();}' value='Mark as closed' class='btn btn-success' />
			</div>
		</div>
	</form>
</div>