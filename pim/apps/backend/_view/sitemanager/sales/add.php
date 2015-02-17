<script type="text/javascript">
	
var sales = new function()
{
	this.changeMonth = function()
	{
		window.location.href = pim.base_url+'/sales/add';
		pim.redirect('sales/add/'+$("#month").val()+"/"+$("#year").val());
	}
}

</script>
<h3 class='m-b-xs text-black'>
	Sales
</h3>
<div class='well well-sm'>
	Sales record for the month. Please update daily.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-4'>
		<div class='table-responsive bg-white'>		
			<form method="post">
				<table class='table'>
					<tr>
						<td width="150px">Sales Income</td><td><?php echo form::text('salesIncome',"class='form-control'"); ?></td>
					</tr>
					<tr>
						<td>Type of sales</td><td><?php echo form::select('salesType', $types);?></td>
					</tr>
						<tr>
						<td>Remark</td><td><?php echo form::textarea('remark',"class='form-control' style='min-height:100px;'"); ?></td>
					</tr>
					<tr>
						 <td></td><td><input type='submit' class='btn btn-primary'  value='SUBMIT' /></td> 
						<!-- <td></td><td>: <input type='button' class='btn btn-primary'  value='SUBMIT' /></td> -->
					</tr>
				</table>
            </form>  
		</div>
	</div>
	<div class="col-sm-8">
		<h3>Latest Sales Records 
		<span class='pull-right' style='font-size:13px;'>
			Month/year : 
			<?php echo form::select('month', model::load('helper')->monthYear('month'), 'onchange="sales.changeMonth();"', $month);?>
			<?php echo form::select('year', model::load('helper')->monthYear('year'), 'onchange="sales.changeMonth();"', $year);?>
		</span></h3>
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th>No.</th>
					<th>Type</th>
					<th>Remark</th>
					<th>Total</th>
					<th width='200px'>Time</th>
					<th width="50px"></th>
				</tr>
				<?php if(count($sales) > 0):?>
					<?php $no = pagination::recordNo();?>
					<?php foreach($sales as $row):?>
						<tr>
							<td><?php echo $no++;?>.</td>
							<td><?php echo $row->getTypeName();?></td>
							<td><?php echo $row->salesRemark;?></td>
							<td>RM <?php echo $row->salesTotal;?></td>
							<td><?php echo date("g:i a, jS F", strtotime($row->salesCreatedDate));?></td>
							<td>
								<a href='<?php echo url::base('sales/edit/'.$row->salesID);?>' data-toggle='ajaxModal' class='fa fa-edit'></a>
								<a class='i i-cross2' onclick='return confirm("Delete this sales, are you sure?");' href='<?php echo url::base('sales/delete/'.$row->salesID);?>'></a>
							</td>
						</tr>
					<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="6">There's no sales currently.</td>
					</tr>
				<?php endif;?>
			</table>
			<?php echo pagination::link();?>
		</div>
	</div>
</div>