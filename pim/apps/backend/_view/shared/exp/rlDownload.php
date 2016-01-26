<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title">Download RL Summary</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<p>Please select month and cluster</p>
			<div class='form-group form-inline'>
				<label>Month</label>
				<?php echo form::select('downloadMonth', model::load('helper')->monthYear('month'), 'class="form-control"', date('m'));?>
				<?php echo form::select('downloadYear', model::load('helper')->monthYear('year'), 'class="form-control"', date('Y'));?>
			</div>
			<div class='form-group form-inline'>
				<label>Cluster</label>
				<?php echo form::select('clusterID', $clusters, 'class="form-control"');?>
				 <label>PR Type</label>
				<?php echo form::select('rlDownloadPrType', array(1 => 'Collection Money', 2 => 'Cash Advance'), 'class="form-control"');?>
			</div>
			<div class='form-group form-inline'>
				
			</div>
			<div class='form-group'>
				<input type='button' value='Download' class="btn btn-primary" onclick="rlList.downloadSummary();" />
			</div>
		</div>
	</div>
</div>