<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>


<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>


<script type="text/javascript">

	$(document).ready(function() {

/*	$("#selectMonth").on("changeDate", function(ev)
	{

		var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";  		
		var selectMonth	= $("#selectMonth").val() != ""?"&selectMonth="+$("#selectMonth").val():"";		
		var selectYear	= $("#selectYear").val() != ""?"&selectYear="+$("#selectYear").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $$siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/esdidt?"+siteID+selectMonth+selectYear;			    
		});*/

$('.row').floatingScrollbar();
	});
	
	
	var base_url	= "<?php echo url::base();?>/";
	
	var billing	= new function()
	{	
		this.select	= function(itemID)
		{		
				
			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";
			var selectMonth	= $("#selectMonth").val() != ""?"&selectMonth="+$("#selectMonth").val():"";		
			var selectYear	= $("#selectYear").val() != ""?"&selectYear="+$("#selectYear").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/dailyCashProcess?"+siteID+selectMonth+selectYear;	

		}
	}
	
</script>

<style type="text/css">
	
	label {

    	font-size: 13px;
    	font-weight: bold;
	}
	.input-s-sm {

		width: 250px;
	}
	.ov {

		 overflow-x: auto;
  width: 100%;
   
   
	}

</style>

<h3 class='m-b-xs text-black'>
	Daily Cash Process
</h3>
<div class='well well-sm'>
	
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method='post' action=''>
			<?php  if((session::get("userLevel") == 99) || (session::get("userLevel") == 3)): ?>
			<div class="form-group" style="margin-left:10px">
				<?php echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select();'",request::get("siteID"),"[SELECT SITE]");?>			
			</div>
			<?php endif;?>
			<div class="form-group" style="margin-left:10px">
				<?php echo form::select("selectMonth",model::load("helper")->monthYear("month"),"onchange='billing.select();'",$selectMonth);?>
				<?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='billing.select();'",$selectYear);?>			
			</div>			
		</form>	
	</div>
</div>

<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			
		</div>
		
		<div class="table-responsive">
			<table class='table ' border='1'>

				<tr>	
					<th></th> 
					<th colspan="6">Member</th>
					<th colspan="15">PC Day</th>
					<th colspan="15">PC Night</th>
					<th colspan="3">Print</th>
					<th>Scan</th>	
					<th>Laminate</th>	
					<th>Other</th>		
					<th></th><th></th>
					<th colspan="2">Day End</th>
				</tr>			

				<tr>	
					<th></th> 		
					<th colspan="2">Total</th>
					<th colspan="2">Student</th>	
					<th colspan="2">Adult</th>

					<th colspan="3">Total</th>
					<th colspan="3">Student Member</th>
					<th colspan="3">Student nonMember</th>
					<th colspan="3">Adult Member</th>
					<th colspan="3">Adult NonMember</th>

					<th colspan="3">Total</th>
					<th colspan="3">Student Member</th>
					<th colspan="3">Student nonMember</th>
					<th colspan="3">Adult Member</th>
					<th colspan="3">Adult NonMember</th>

					<th>Total</th>	
					<th>B/W</th>	
					<th>Color</th>	
					<th></th><th></th><th></th><th></th><th></th><th></th><th></th>
				</tr>			

				<tr>	
					<th>Day</th> 
					<th>RM</th> <th>User</th> 
					<th>RM</th> <th>User</th>	
					<th>RM</th>	<th>User</th> 
					
					<th>RM</th> <th>User</th> <th>Hr</th> 
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					
					<th>RM</th> <th>User</th> <th>Hr</th> 
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th>RM</th>	<th>User</th> <th>Hr</th>	
										
					<th>RM</th>	<th>RM</th>	<th>RM</th>	<th>RM</th>	<th>RM</th>	
					<th>RM</th>	<th>Utilities</th>	<th>Description </th>	 <th>Total</th>	<th>Balance</th>
				</tr>
				
			<?php if(count($list) > 0):?>
				<tr>
					<td><?php echo $alldate[0];?> </td>
					<td colspan="45"> Beginning Balance</td>
					<td><?php echo number_format($balance, 2, '.', '')?></td>
				</tr>

<?php 
	$beginningbalance = $balance;
foreach ($alldate as $date => $day):?>

			<?php foreach ($list as $key => $row):?>
			<?php 	
				$row = $list[$key];
				if ($day == $key) {
			?> 
				 	
				<tr>
					<td><?php echo $key ?></td> 
									
					<td><?php $total = $row['Membership Student']['total'] +  $row['Membership Adult']['total']; 
						echo number_format($total, 2, '.', '') ?></td> 
					<td><?php echo $row['Membership Student']['quantity'] + $row['Membership Adult']['quantity'] ?></td> 

					<td><?php echo number_format($row['Membership Student']['total'], 2, '.', '') ?></td> 
					<td><?php echo number_format($row['Membership Student']['quantity'], 0, '', '') ?></td> 

					<td><?php echo number_format($row['Membership Adult']['total'], 2, '.', '') ?></td> 
					<td><?php echo number_format($row['Membership Adult']['quantity'], 0, '', '') ?></td> 

					<?php 

 					if ($row['PC, Member Student']['pcType'] == 'Day') { 
					$pcMemStudentTotal  = number_format($row['PC, Member Student']['total'], 2, '.', '');  
					$pcMemStudentQuantity  = $row['PC, Member Student']['quantity'];  
					$pcMemStudentUnit  =   $row['PC, Member Student']['unit'];  
					} else {

					$pcMemStudentTotal  = 0.00;  
					$pcMemStudentQuantity  = 0;  
					$pcMemStudentUnit  =   0;  			
					}

					if ($row['PC, Member Student']['pcType'] == 'Night') { 
					$pcMemStudentTotalN  = number_format($row['PC, Member Student']['total'], 2, '.', '');  
					$pcMemStudentQuantityN  = $row['PC, Member Student']['quantity'];  
					$pcMemStudentUnitN  =   $row['PC, Member Student']['unit'];  
					} else {

					$pcMemStudentTotalN  = 0.00;  
					$pcMemStudentQuantityN  = 0;  
					$pcMemStudentUnitN  =   0;  		

					}

					 if ($row['PC, NonMem Student']['pcType'] == 'Day') { 
					$pcNonMemStudentTotal = number_format($row['PC, NonMem Student']['total'], 2, '.', ''); 
					$pcNonMemStudentQuantity = $row['PC, NonMem Student']['quantity'];  
					$pcNonMemStudentUnit = $row['PC, NonMem Student']['unit'];  
					 } else { 
					$pcNonMemStudentTotal  = 0.00;  
					$pcNonMemStudentQuantity  = 0;  
					$pcNonMemStudentUnit  = 0;  
					 } 

					 if ($row['PC, NonMem Student']['pcType'] == 'Night') { 
					 $pcNonMemStudentTotalN = number_format($row['PC, NonMem Student']['total'], 2, '.', ''); 
					$pcNonMemStudentQuantityN = $row['PC, NonMem Student']['quantity'];  
					$pcNonMemStudentUnitN = $row['PC, NonMem Student']['unit'];  
					} else { 
					$pcNonMemStudentTotalN  = 0.00;  
					$pcNonMemStudentQuantityN  = 0;  
					$pcNonMemStudentUnitN  = 0;  
					 } 

					if ($row['PC, Member Adult']['pcType'] == 'Day') { 
					$pcMemAdultTotal = number_format($row['PC, Member Adult']['total'], 2, '.', ''); 
					$pcMemAdultQuantity = $row['PC, Member Adult']['quantity'];  
					$pcMemAdultUnit = $row['PC, Member Adult']['unit'];  
					 } else { 
					$pcMemAdultTotal = 0.00;  
					$pcMemAdultQuantity  = 0;  
					$pcMemAdultUnit  = 0;  
					 } 

					if ($row['PC, Member Adult']['pcType'] == 'Night') { 
					$pcMemAdultTotalN = number_format($row['PC, Member Adult']['total'], 2, '.', ''); 
					$pcMemAdultQuantityN = $row['PC, Member Adult']['quantity'];  
					$pcMemAdultUnitN = $row['PC, Member Adult']['unit'];  
					 } else { 
					$pcMemAdultTotalN = 0.00;  
					$pcMemAdultQuantityN  = 0;  
					$pcMemAdultUnitN  = 0;  
					 } 

					 if ($row['PC, NonMem Adult']['pcType'] == 'Day') { 
					$pcNonMemAdultTotal = number_format($row['PC, NonMem Adult']['total'], 2, '.', ''); 
					$pcNonMemAdultQuantity = $row['PC, NonMem Adult']['quantity'];  
					$pcNonMemAdultUnit = $row['PC, NonMem Adult']['unit'];  
					 } else { 
					$pcNonMemAdultTotal = 0.00;  
					$pcNonMemAdultQuantity  = 0;  
					$pcNonMemAdultUnit  = 0;  
					 } 

					 if ($row['PC, NonMem Adult']['pcType'] == 'Night') { 
					$pcNonMemAdultTotalN = number_format($row['PC, NonMem Adult']['total'], 2, '.', ''); 
					$pcNonMemAdultQuantityN = $row['PC, NonMem Adult']['quantity'];  
					$pcNonMemAdultUnitN = $row['PC, NonMem Adult']['unit'];  
					 } else { 
					$pcNonMemAdultTotalN = 0.00;  
					$pcNonMemAdultQuantityN  = 0;  
					$pcNonMemAdultUnitN  = 0;  
					 } 

					?>

					<td><?php $total =  $pcMemStudentTotal +  $pcNonMemStudentTotal + $pcMemAdultTotal +  $pcNonMemAdultTotal; 
							  echo number_format($total, 2, '.', ''); ?></td> 
					<td><?php echo $pcMemStudentQuantity +  $pcNonMemStudentQuantity + $pcMemAdultQuantity +  $pcNonMemAdultQuantity; ?></td> 
					<td><?php echo $pcMemStudentUnit +  $pcNonMemStudentUnit + $pcMemAdultUnit +  $pcNonMemAdultUnit; ?></td> 
					
					<td><?php echo number_format($pcMemStudentTotal, 2, '.', ''); ?></td> 
					<td><?php echo $pcMemStudentQuantity; ?></td> 
					<td><?php echo $pcMemStudentUnit; ?></td> 
					
					<td><?php echo number_format($pcNonMemStudentTotal, 2, '.', ''); ?></td> 
					<td><?php echo $pcNonMemStudentQuantity; ?></td>
					<td><?php echo $pcNonMemStudentUnit; ?></td>
					
					<td><?php echo number_format($pcMemAdultTotal, 2, '.', ''); ?></td> 
					<td><?php echo $pcMemAdultQuantity; ?></td>
					<td><?php echo $pcMemAdultUnit; ?></td>
					
					<td><?php echo number_format($pcNonMemAdultTotal, 2, '.', ''); ?></td> 
					<td><?php echo $pcNonMemAdultQuantity; ?></td>
					<td><?php echo $pcNonMemAdultUnit; ?></td>
					
					
					<!--  PC, Member Student    NIGHT -->
					<td><?php $totalN =  $pcMemStudentTotalN +  $pcNonMemStudentTotalN + $pcMemAdultTotalN +  $pcNonMemAdultTotalN; 
							  echo number_format($totalN, 2, '.', ''); ?></td> 
					<td><?php echo 	$pcMemStudentQuantityN +  $pcNonMemStudentQuantityN + $pcMemAdultQuantityN +  $pcNonMemAdultQuantityN; ?></td> 
					<td><?php echo 	$pcMemStudentUnitN +  $pcNonMemStudentUnitN + $pcMemAdultUnitN +  $pcNonMemAdultUnitN; ?></td> 
					
					<td><?php echo number_format($pcMemStudentTotalN, 2, '.', ''); ?></td> 
					<td><?php echo $pcMemStudentQuantityN; ?></td> 
					<td><?php echo $pcMemStudentUnitN; ?></td> 
					
					<td><?php echo number_format($pcNonMemStudentTotalN, 2, '.', ''); ?></td> 
					<td><?php echo $pcNonMemStudentQuantityN; ?></td>
					<td><?php echo $pcNonMemStudentUnitN; ?></td>
					
					<td><?php echo number_format($pcMemAdultTotalN, 2, '.', ''); ?></td> 
					<td><?php echo $pcMemAdultQuantityN; ?></td>
					<td><?php echo $pcMemAdultUnitN; ?></td>
					
					<td><?php echo number_format($pcNonMemAdultTotalN, 2, '.', ''); ?></td> 
					<td><?php echo $pcNonMemAdultQuantityN; ?></td>
					<td><?php echo $pcNonMemAdultUnitN; ?></td>					

					<td><?php $totalPrint = $row['Printing & Photostat Color']['total'] + $row['Printing & Photostat B&W']['total']; 
							  echo number_format($totalPrint, 2, '.', ''); ?></td> 	

					<?php if ($row['Printing & Photostat B&W']['date'] == $key) { ?>
					<td><?php echo $row['Printing & Photostat B&W']['total']; ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Printing & Photostat Color']['date'] == $key) { ?>										
					<td><?php echo number_format($row['Printing & Photostat Color']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>
					
					<?php if ($row['Scanning']['date'] == $key) { ?>										
					<td><?php echo number_format($row['Scanning']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Laminating (A4)']['date'] == $key) { ?>										
					<td><?php echo number_format($row['Laminating (A4)']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Other Service']['date'] == $key) { ?>										
					<td><?php echo number_format($row['Other Service']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Utilities']['date'] == $key) { ?>										
					<td><?php echo number_format($row['Utilities']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Utilities']['date'] == $key) { ?>										
					<td><?php echo $row['Utilities']['desc']; ?></td> 
					<?php } else { ?>
					<td></td> 
					<?php } ?>


					<td><?php 
								$dailytotal = $dailytotal + $totallist[$key]['total']; 
								echo $totalperday = number_format($totallist[$key]['total'], 2, '.', ''); 

					?></td>
					<td><?php 	
								$beginningbalance = $totallist[$key]['total'] + $beginningbalance;
								echo $beginningbalance = number_format($beginningbalance, 2, '.', '');
					 ?></td>	
					
					
				</tr>

				<?php break; } ?>


			<?php  endforeach;?>




	<?php if ($day != $availableDate[$day][0]) {  ?>
				<tr>	
					<td><?php echo $day;?></td> 
					<td>0.00</td><td>0</td> 
					<td>0.00</td><td>0</td>	
					<td>0.00</td><td>0</td> 
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
										
					<td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>	
					<td>0.00</td><td>0.00</td><td> </td> <td>0.00</td><td><?php echo $beginningbalance = number_format($beginningbalance, 2, '.', '');?></td>
				</tr>

	<?php } ?>
				


<?php endforeach;?>
				<tr>	
					<td>Total</td> 
					<td>0.00</td><td>0</td> 
					<td>0.00</td><td>0</td>	
					<td>0.00</td><td>0</td> 
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
					<td>0.00</td><td>0</td><td>0</td>	
										
					<td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
					<td>0.00</td><td>0.00</td><td>0.00</td><td> </td> 
					<td><?php echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
					<td><?php echo $beginningbalance = number_format($beginningbalance, 2, '.', '');?></td>
				</tr>
				<tr>					
					<th colspan="47"></th>
				</tr>
				<tr>
					<th> </th>
					<th colspan="46">  Bank In </th>
				</tr>
			<?php  foreach ($transferList as $key => $row):?>
				<tr>
					<td><?php echo $row['date']?></td>
					<td colspan="44"><?php echo $row['description']?></td>
					<td><?php echo number_format($row['total'], 2, '.', '');?></td>
					<td><?php $beginningbalance = $beginningbalance + $row['total'];
					echo number_format($beginningbalance, 2, '.', '');?></td>
				</tr>
			<?php endforeach;?>

			<?php else:?>		
				<tr>
					<td colspan="47"> No Transaction</td>
				</tr>
				<?php endif; ?>	

			</table>


			
		</div>
	</div>



<div class="col-sm-12  ">
		<div class='well well-sm'>
			
		</div>

		<div class="table-responsive">
		<form class="form-inline bs-example" method='post' action='<?php echo url::base('billing/dailyCashProcess/'.$siteID);?>'>
			<table class='table ' border='1'>

				<tr>	
					<th></th> 
					<th>Site Manager</th>
					<th>Cluster Lead</th>
					<th>Financial Controller</th>
				</tr>		

				<tr>	
					<td>Month Total</td> 
					<td><?php $dailytotal = $beginningbalance - $balance;
							echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
					<td><?php $dailytotal = $beginningbalance - $balance;
							echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
					<td></td>
				</tr>	

				<tr>	
					<td>Balance</td> 
					<td><?php echo number_format($beginningbalance, 2, '.', '');?></td>
					<td><?php echo number_format($beginningbalance, 2, '.', '');?></td>
					<td></td>
				</tr>	

				<tr>	
					<td>Status</td> 
					<td>
					<?php 
					if((session::get("userLevel") != 3) && (session::get("userLevel") != 5)) { 

						if ($checked == 1) { ?>

							verified	

						<?php } else { ?>	

						<button name="submit" type="submit" class="btn btn-sm btn-default" value="1">Check</button>
					<?php } 
					}  else {

						 	if ($checked == 1) { ?>

							verified	

						<?php } } ?>

					</td>


					<td>
					<?php  if((session::get("userLevel") != 2) && (session::get("userLevel") != 5)) { 

					if ($approved == 1) { ?>

							verified	

						<?php } else { ?>	


					<button name="submit" type="submit" class="btn btn-sm btn-default" value="1">Approve</button> <button name="submit" type="submit" class="btn btn-sm btn-default"  value="2">Reject</button>
					<?php } }  else {

						 	if ($approved == 1) { ?>

							verified	

						<?php } } ?>

					</td>





					<td>
					<?php /* if((session::get("userLevel") != 2) && (session::get("userLevel") != 3)) { 

						if ($checked == 1) { ?>

							verified	

						<?php } else { ?>	

					<button type="submit" class="btn btn-sm btn-default">Close</button> <button type="submit" class="btn btn-sm btn-default">Reject</button>
					<?php } 

					}*/ ?>
					
					</td>
				</tr>	
			</table>
			</form>>
		</div>	
	</div>




</div>	