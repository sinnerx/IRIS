<h3 class="m-b-xs text-black">
<a href='info'>Billing Code Assigment</a>
</h3>
<div class='well well-sm'>
Assign Billing code for reporting.
</div>
<div class="row">
	<div class='col-sm-6'>
	<p>Assign new code</p>
	<form method='post'>
		<table class='table'>
			<tr>
				<td><?php echo form::select('billingItemID', $assign['billingItems'], 'class="form-control"');?></td>
				<td><?php echo form::select('billingItemCode', $assign['billingItemCodes'], 'class="form-control"');?></td>
				<td><input type='submit' class='btn btn-primary' /></td>
			</tr>
		</table>
	</form>
	</div>
</div>
<div class='row' style="margin-top: 20px;">
	<div class='col-sm-6'>
		<p>List of existing assignment</p>
		<table class='table'>
			<tr>
				<th>Item Name</th>
				<th>Code</th>
			</tr>
			<?php if(count($billingItems) == 0):?>
				<tr>
					<td colspan="2">No record.</td>
				</tr>
			<?php else:?>
			<?php foreach($billingItems as $item):?>
				<tr>
					<td><?php echo $item->billingItemName;?></td>
					<td><?php echo $item->billingItemCodeName;?></td>
				</tr>
			<?php endforeach;?>
			<?php endif;?>
		</table>
	</div>
</div>