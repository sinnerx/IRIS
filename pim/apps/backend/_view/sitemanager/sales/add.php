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
	<div class='col-sm-7'>
		<div class='table-responsive bg-white'>		
			<form method="post">
				<table class='table'>
	

					 <tr>

					<?php foreach($types as $row=>$no):?>

						<td><?php echo $no[productName]?></td>
					
					 <?php endforeach;?>

					</tr>
					 <tr>

					<?php foreach($types as $row=>$no):?>

						<td><?php  echo form::text($no[productID],null,"0");  ?></td>
					
					 <?php endforeach;?>

					</tr>



				
				<tr>
						 <td><input type='submit' class='btn btn-primary'  value='SUBMIT' /></td> 
						
					</tr> 
				</table>
            </form>  
		</div>
	</div>
	<div class="col-sm-5">
		<h4>Latest Sales Records 
		<span class='pull-right' style='font-size:12px;'>
			Month/year : 
			<?php echo form::select('month', model::load('helper')->monthYear('month'), 'onchange="sales.changeMonth();"', $month);?>
			<?php echo form::select('year', model::load('helper')->monthYear('year'), 'onchange="sales.changeMonth();"', $year);?>
		</span></h3>
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th>No.</th>
					<th>Type</th>
			
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