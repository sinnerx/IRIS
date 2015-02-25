<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<script type="text/javascript">
	
var sales = new function()
{
	this.changeMonth = function()
	{
		window.location.href = pim.base_url+'/sales/add';
		pim.redirect('sales/add/'+$("#month").val()+"/"+$("#year").val());
	}
}

$(document).ready(function()
{
	 var cdate = new Date();

	$("#selectDate").on("changeDate", function(ev)
	{

		var date = new Date(ev.date);
	

		 if (cdate.valueOf() < date.valueOf()){

			alert("Selected date cannot be greater than current date");

			rt = cdate.getFullYear()+"-"+(cdate.getMonth()+1)+"-"+cdate.getDate();
	    	window.location.href = pim.base_url+"sales/add/"+rt;

		} else {

			rt = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	    	window.location.href = pim.base_url+"sales/add/"+rt;
		

		}


	    
	});
})


</script>
<h3 class='m-b-xs text-black'>
	Sales
</h3>
<div class='well well-sm'>
	Daily sales record.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive bg-white'>		
			<form method="post">
				<table class='table'>
	

					 <tr>

					<?php foreach($types as $row=>$no):?>
						
						<td><?php echo $no[productName]?></td>
					
					 <?php endforeach;?>
					 	<td></td>
						<td>
					   	<div class="col-sm-9"><?php echo form::text("selectDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDate)));?></div>
						</td>
					</tr>


					<?php if($todaySales->count() > 0):?>
					<?php
						$todaySales = $todaySales->getFirst();
						$products = $todaySales->getProducts();
					?>

					 <tr>
						<?php foreach($products as $salesProduct):?>
							
							<?php if($salesProduct->productID == "1"):  ?>							
							<td>RM <?php echo form::text($salesProduct->productID,"class='form-control' style='width:60%;display:inline;'",$salesProduct->salesProductQuantity);  ?></td>
							<?php else:?>		
							<td><?php echo form::text($salesProduct->productID,"class='form-control' style='width:60%;display:inline;'",$salesProduct->salesProductQuantity);  ?> Unit</td>
							<?php endif;?>

						<?php endforeach;?>
						 	<td><input type='submit' class='btn btn-primary'  value='SUBMIT' /></td> 
						 	
					</tr>

					<?php else:?>
					 <tr>
						<?php foreach($types as $row=>$no):?>
							

							<?php if($no[productID] == "1"):  ?>
							<td>RM <?php echo form::text($no[productID],"class='form-control' style='width:60%;display:inline;'","0");  ?></td>
							<?php else:?>
							<td><?php echo form::text($no[productID],"class='form-control' style='width:60%;display:inline;'","0");  ?> Unit</td>
							<?php endif;?>								

						 <?php endforeach;?>
						 	<td><input type='submit' class='btn btn-primary'  value='SUBMIT' /></td> 
						 	<td></td>
					</tr>
					<?php endif;?>
					<!-- <tr>
						 <td><input type='submit' class='btn btn-primary'  value='SUBMIT' /></td> 						
					</tr>  -->
				</table>
            </form>  
		</div>
	</div>
	<!-- <div class="col-sm-5">
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
	</div> -->
</div>