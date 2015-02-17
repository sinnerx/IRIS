<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span><span class='fa fa-dollar'></span> Sales Update</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		<div class="modal-body" style='padding-top:5px;'>
		<form method='post' action='<?php echo url::base('sales/edit/'.$sales->salesID);?>'>
			<div class='mb-content'>
				<table class='table'>
					<tr>
						<td>Type</td>
						<td><?php echo form::select("salesType", model::load("sales/sales")->type(), 'class="form-control"', $sales->salesType);?></td>
					</tr>
					<tr>
						<td>Total</td>
						<td><?php echo form::text('salesTotal', 'class="form-control"', $sales->salesTotal);?></td>
					</tr>
					<tr>
						<td>Remark</td>
						<td><?php echo form::textarea('salesRemark', 'class="form-control"', $sales->salesRemark);?></td>
					</tr>
				</table>
			</div>
			<div class='mb-footer'>
				<input type='submit' value='Update' class='btn btn-primary' />
			</div>
		</form>
		</div>
	</div>
</div>