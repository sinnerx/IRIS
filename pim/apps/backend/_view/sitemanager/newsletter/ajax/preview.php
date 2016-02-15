<div class="modal-dialog" style="width:800px;">
	<div class="modal-content">
		<div class="modal-header">
		<h3><?php echo $siteNL->siteNewsletterSubject;?>
		<a href='<?php echo url::base('newsletter/template');?>' class='btn btn-primary pull-right'>Update</a>
		</h3>

		</div>
		<div class="modal-body">
			<div><?php echo $siteNL->prepareTemplate();?></div>
		</div>
	</div>
</div>