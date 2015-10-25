<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript">

	$(document).ready(function() {

		$('.row').floatingScrollbar();

		// table.toggleExpand();
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

	var table = new function()
	{
		this.collapsed = false;

		this.toggleExpand = function()
		{
			var squareWrapper = $("#a2, #b2, #c2, #d2");
			squareWrapper.removeClass('fa-plus-square').removeClass('fa-minus-square');
			columns = $(".billing-column-member, .billing-column-pc-day, .billing-column-pc-night, .billing-column-print");

			// handle expansion
			if(this.collapsed)
			{
				// show collapsion button
				squareWrapper.addClass('fa-minus-square');

				columns.show();

				// colspan expansion
				$('#a1').attr('colspan','6');

				$('#b1').attr('colspan','15');
				$('#c1').attr('colspan','15');
				$('#d1').attr('colspan','3');

				$('#e1').attr('colspan','45');
				$('#f1').attr('colspan','47');
				$('#g1').attr('colspan','46');
				$('.j1').attr('colspan','44');

				this.collapsed = false;

			}
			// handle collapsion
			else
			{
				// show expansion button
				squareWrapper.addClass('fa-plus-square');

				columns.hide();

				// colspan collapsion
				$('#a1').attr('colspan','2');
				$('#b1').attr('colspan','3');
				$('#c1').attr('colspan','3');
				$('#d1').attr('colspan','1');
				$('#e1').attr('colspan','15');
				$('#f1').attr('colspan','17');
				$('#g1').attr('colspan','16');
				$('.j1').attr('colspan','14');

				this.collapsed = true;
			}
		}

		this.select	= function()
		{
			return this.toggleExpand();
/*
			if	($('#a2').hasClass('fa-minus-square')){
				$(".billing-column-member").hide();
				$('#a1').attr('colspan','2');
				$('#a2').removeClass('fa-minus-square').addClass('fa-plus-square');

				$('#e1').attr('colspan','15');
				$('#f1').attr('colspan','17');
				$('#g1').attr('colspan','16');
				$('.j1').attr('colspan','14');

			} else {
				$(".billing-column-member").show();
				$('#a1').attr('colspan','6');
				$('#a2').removeClass('fa-plus-square').addClass('fa-minus-square');

				$('#e1').attr('colspan','45');
				$('#f1').attr('colspan','47');
				$('#g1').attr('colspan','46');
				$('.j1').attr('colspan','44');

			}	 

			if	($('#b2').hasClass('fa-minus-square')){
				$(".billing-column-pc-day").hide();
				$('#b1').attr('colspan','3');
				$('#b2').removeClass('fa-minus-square').addClass('fa-plus-square');
			} else {
				$(".billing-column-pc-day").show();
				$('#b1').attr('colspan','15');
				$('#b2').removeClass('fa-plus-square').addClass('fa-minus-square');
			}	 

			if	($('#c2').hasClass('fa-minus-square')){
				$(".billing-column-pc-night").hide();
				$('#c1').attr('colspan','3');
				$('#c2').removeClass('fa-minus-square').addClass('fa-plus-square');
			} else {
				$(".billing-column-pc-night").show();
				$('#c1').attr('colspan','15');
				$('#c2').removeClass('fa-plus-square').addClass('fa-minus-square');
			}	 

			if	($('#d2').hasClass('fa-minus-square')){
				$(".billing-column-print").hide();
				$('#d1').attr('colspan','1');
				$('#d2').removeClass('fa-minus-square').addClass('fa-plus-square');
			} else {
				$(".billing-column-print").show();
				$('#d1').attr('colspan','3');
				$('#d2').removeClass('fa-plus-square').addClass('fa-minus-square');
			}*/

			

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

	/* hide for now */
	.fa-plus-square
	{
		display: none;
	}

</style>

<h3 class='m-b-xs text-black'>
	Daily Cash Process
</h3>
<div class='well well-sm'>
	Choose month and year
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method='post' action=''>
			<?php  if((session::get("userLevel") == \model\user\user::LEVEL_ROOT) || (session::get("userLevel") == \model\user\user::LEVEL_CLUSTERLEAD)  || (session::get("userLevel") == \model\user\user::LEVEL_FINANCIALCONTROLLER)): ?>
			<div class="form-group" style="margin-left:10px">
				<?php echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select();'",$siteID,"[SELECT SITE]");?>			
			</div>
			<?php endif;?>
			<div class="form-group" style="margin-left:10px">
				<?php echo form::select("selectMonth",model::load("helper")->monthYear("monthE"),"onchange='billing.select();'",$selectMonth);?>
				<?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='billing.select();'",$selectYear);?>			
			</div>			
		</form>	
	</div>
</div>

<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			 <?php 
			  echo $site->siteName; ?> Monthly Cash Report for <?php echo $selectYear; ?>/<?php echo $selectMonth; ?>
		</div>
		
		<div class="table-responsive">
			<table class='table b-t b-light'>

				<tr style="background-color:#ededed">	
					<th></th> 
					<th colspan="6" id="a1">Member</th>
					<th colspan="15" id="b1">PC Day</th>
					<th colspan="15" id="c1">PC Night</th>
					<th colspan="3" id="d1">Print</th>
					<th>Scan</th>	
					<th>Laminate</th>	
					<th>Other</th>		
					<th></th><th></th>
					<th colspan="2">Day End</th>
				</tr>			

				<tr bgcolor="#ededed">	
					<th></th> 		
					<th colspan="2"><a id ="a2" href="#" onclick='table.toggleExpand();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>
					<th class="billing-column-member" colspan="2">Student</th>	
					<th class="billing-column-member" colspan="2">Adult</th>

					<th colspan="3"><a id ="b2" href="#" onclick='table.toggleExpand();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>
					<th class="billing-column-pc-day" colspan="3">Student Member</th>
					<th class="billing-column-pc-day" colspan="3">Student nonMember</th>
					<th class="billing-column-pc-day" colspan="3">Adult Member</th>
					<th class="billing-column-pc-day" colspan="3">Adult NonMember</th>

					<th colspan="3"><a id ="c2" href="#" onclick='table.toggleExpand();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>
					<th class="billing-column-pc-night" colspan="3">Student Member</th>
					<th class="billing-column-pc-night" colspan="3">Student nonMember</th>
					<th class="billing-column-pc-night" colspan="3">Adult Member</th>
					<th class="billing-column-pc-night" colspan="3">Adult NonMember</th>

					<th><a id ="d2" href="#" onclick='table.toggleExpand();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>	
					<th class="billing-column-print">B/W</th>	
					<th class="billing-column-print">Color</th>	
					<th></th><th></th><th></th><th></th><th></th><th></th><th></th>
				</tr>			

				<tr bgcolor="#ededed">	
					<th>Day</th> 
					<th>RM</th> <th>User</th> 
					<th class="billing-column-member">RM</th> <th class="billing-column-member">User</th>	
					<th class="billing-column-member">RM</th> <th class="billing-column-member">User</th> 
					
					<th>RM</th> <th>User</th> <th>Hr</th> 
					<th class="billing-column-pc-day">RM</th>	<th class="billing-column-pc-day">User</th> <th class="billing-column-pc-day">Hr</th>	
					<th class="billing-column-pc-day">RM</th>	<th class="billing-column-pc-day">User</th> <th class="billing-column-pc-day">Hr</th>	
					<th class="billing-column-pc-day">RM</th>	<th class="billing-column-pc-day">User</th> <th class="billing-column-pc-day">Hr</th>	
					<th class="billing-column-pc-day">RM</th>	<th class="billing-column-pc-day">User</th> <th class="billing-column-pc-day">Hr</th>	
					
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th class="billing-column-pc-night">RM</th>	<th class="billing-column-pc-night">User</th> <th class="billing-column-pc-night">Hr</th>
					<th class="billing-column-pc-night">RM</th>	<th class="billing-column-pc-night">User</th> <th class="billing-column-pc-night">Hr</th>
					<th class="billing-column-pc-night">RM</th>	<th class="billing-column-pc-night">User</th> <th class="billing-column-pc-night">Hr</th>
					<th class="billing-column-pc-night">RM</th>	<th class="billing-column-pc-night">User</th> <th class="billing-column-pc-night">Hr</th>
										
					<th>RM</th>	<th class="billing-column-print">RM</th>	<th class="billing-column-print">RM</th>	<th>RM</th>	<th>RM</th>	
					<th>RM</th>	<th>Utilities</th>	<th>Description </th>	 <th>Total</th>	<th>Balance</th>
				</tr>

				<tr>					
					<td><?php echo date('d', strtotime($alldate[0]))  ?></td> 
					<td  id="e1" colspan="45"> Monthly Revenue (Previous Balance) </td>
					<td><?php echo number_format($balance, 2, '.', '');
					?></td>
				</tr>
<!-- Begin master loop. -->
<?php 
$float = function($val = null)
{
	if(!$val)
		return number_format(0, 2, '.', '');
	else
		return number_format($val, 2, '.', '');
};

$total = function($val = null)
{
	return !$val ? 0 : $val;
}

?>
<?php foreach(range(1, date('t', strtotime($selectYear.'-'.$selectMonth.'-01'))) as $day):
			$date = $selectYear.'-'.$selectMonth.'-'.$day;
			$date = date('Y-m-d', strtotime($date));
			$no = 0;
			?>
			<tr>
				<td><?php echo date('d', strtotime($date));?></td>
				<td><?php echo $amount = $float($report[$date]['Membership']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['Membership']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Membership']['student']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['Membership']['student']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Membership']['adult']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['Membership']['adult']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>

				<td><?php echo $amount = $float($report[$date]['PC']['day']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['day']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['student']['member']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['day']['student']['member']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['student']['member']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['student']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['day']['student']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['student']['nonmember']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['adult']['member']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['day']['adult']['member']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['adult']['member']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['adult']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['day']['adult']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['day']['adult']['nonmember']['total_quantity']); $totals[$no++] += $amount;?></td>

				<td><?php echo $amount = $float($report[$date]['PC']['night']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['night']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['student']['member']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['night']['student']['member']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['student']['member']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['student']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['night']['student']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['student']['nonmember']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['adult']['member']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['night']['adult']['member']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['adult']['member']['total_quantity']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['adult']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['night']['adult']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['PC']['night']['adult']['nonmember']['total_quantity']); $totals[$no++] += $amount;?></td>

				<td><?php echo $amount = $float(($report[$date]['Print Color']['total'] ? : 0) + ($report[$date]['Black And White']['total'] ? : 0)); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Print Color']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Black And White']['total']); $totals[$no++] += $amount;?></td>

				<td><?php echo $amount = $float($report[$date]['Scan']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Laminate']['total']); $totals[$no++] += $amount;?></td>

				<td><?php echo $amount = $float($report[$date]['Other']['total']); $totals[$no++] += $amount;?></td>
				<td></td>
				<td></td>

				<td><?php echo $amount = $float($sum = $report[$date]['total']); $sums += $sum;?></td>
				<?php $balance = $balance + $sum;?>
				<td><?php echo $amount = $float($balance);?></td>
			</tr>
			<?php endforeach;?>
<!--  End of master loop. -->
			<tr>
				<td>Total</td>
				<?php foreach($totals as $value):?>
				<td><?php echo $float($value);?></td>
				<?php endforeach;?>
				<td></td>
				<td></td>
				<td><?php echo $float($sums);?></td>
				<td><?php echo $float($balance);?></td>
			</tr>
			<tr>					
				<th id="f1" colspan="47"></th>
			</tr>
			<tr style="background-color:#ededed">	
				<th> </th>
				<th id="g1" colspan="46"> Bank In </th>
			</tr>


			<?php if(false || count($list) > 0):?>
			
<?php 
	$beginningbalance = $balance;
	foreach ($alldate as $date => $day):?>

			<?php foreach ($list as $key => $row):?>
			<?php 	
				$row = $list[$key];
				if ($day == $key) {
			?> 
				 	
				<tr>
					<td><?php echo date('d', strtotime($key))  ?></td> 
									
					<td><?php $totalMemberMonthly = $totalMemberMonthly + $row['Membership Student']['total'] +  $row['Membership Adult']['total']; 
							  $totalMember = $row['Membership Student']['total'] +  $row['Membership Adult']['total'];
						echo number_format($totalMember, 2, '.', '') ?></td> 
					<td><?php $quantityMemberMonthly = $quantityMemberMonthly + $row['Membership Student']['quantity'] + $row['Membership Adult']['quantity'];
								echo $row['Membership Student']['quantity'] + $row['Membership Adult']['quantity'] ?></td> 

					<td class="billing-column-member"><?php $totalStudentMonthly = $totalStudentMonthly + $row['Membership Student']['total'];
									echo number_format($row['Membership Student']['total'], 2, '.', '') ?></td> 
					<td class="billing-column-member"><?php $quantityStudentMonthly = $quantityStudentMonthly +  $row['Membership Student']['quantity'];
									echo number_format($row['Membership Student']['quantity'], 0, '', '') ?></td> 


					<td class="billing-column-member"><?php $totalAdultMonthly = $totalAdultMonthly + $row['Membership Adult']['total'];
									echo number_format($row['Membership Adult']['total'], 2, '.', '') ?></td> 
					<td class="billing-column-member"><?php $quantityAdultMonthly = $quantityAdultMonthly +  $row['Membership Adult']['quantity'];
									echo number_format($row['Membership Adult']['quantity'], 0, '', '') ?></td> 

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

					<td><?php $totalpc = $totalpc + $pcMemStudentTotal +  $pcNonMemStudentTotal + $pcMemAdultTotal +  $pcNonMemAdultTotal;  
							  $total =  $pcMemStudentTotal +  $pcNonMemStudentTotal + $pcMemAdultTotal +  $pcNonMemAdultTotal; 
							  echo number_format($total, 2, '.', ''); ?></td> 
					<td><?php $pcQuantity = $pcQuantity + $pcMemStudentQuantity +  $pcNonMemStudentQuantity + $pcMemAdultQuantity +  $pcNonMemAdultQuantity;
							  echo $pcMemStudentQuantity +  $pcNonMemStudentQuantity + $pcMemAdultQuantity +  $pcNonMemAdultQuantity; ?></td> 
					<td><?php $pcUnit = $pcUnit + $pcMemStudentUnit +  $pcNonMemStudentUnit + $pcMemAdultUnit +  $pcNonMemAdultUnit;
							  echo $pcMemStudentUnit +  $pcNonMemStudentUnit + $pcMemAdultUnit +  $pcNonMemAdultUnit; ?></td> 
					
					<td class="billing-column-pc-day"><?php $totalPcStudent = $totalPcStudent + number_format($pcMemStudentTotal, 2, '.', '');
							  echo number_format($pcMemStudentTotal, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-day"><?php $quantityPcStudent = $quantityPcStudent + $pcMemStudentQuantity; 
							  echo $pcMemStudentQuantity; ?></td> 
					<td class="billing-column-pc-day"><?php $unitPcStudent = $unitPcStudent + $pcMemStudentUnit; 
					          echo $pcMemStudentUnit; ?></td> 
					
					<td class="billing-column-pc-day"><?php $totalPcNonStudent = $totalPcNonStudent + number_format($pcNonMemStudentTotal, 2, '.', '');
					          echo number_format($pcNonMemStudentTotal, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-day"><?php $quantityPcStudent1 = $quantityPcStudent1 + $pcNonMemStudentQuantity; 
					          echo $pcNonMemStudentQuantity; ?></td>
					<td class="billing-column-pc-day"><?php $unitPcStudent1 = $unitPcStudent1 + $pcNonMemStudentUnit; 
					          echo $pcNonMemStudentUnit; ?></td>
					
					<td class="billing-column-pc-day"><?php $totalPcAdult = $totalPcAdult + number_format($pcMemAdultTotal, 2, '.', '');
					          echo number_format($pcMemAdultTotal, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-day"><?php $quantityPcAdult = $quantityPcAdult + $pcMemAdultQuantity; 
					          echo $pcMemAdultQuantity; ?></td>
					<td class="billing-column-pc-day"><?php $unitPcAdult = $unitPcAdult + $pcMemAdultUnit; 
					          echo $pcMemAdultUnit; ?></td>
					
					<td class="billing-column-pc-day"><?php $totalPcNonAdult = $totalPcNonAdult + number_format($pcNonMemAdultTotal, 2, '.', '');
							  echo number_format($pcNonMemAdultTotal, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-day"><?php $quantityPcAdult1 = $quantityPcAdult1 + $pcNonMemAdultQuantity; 
					   	 	  echo $pcNonMemAdultQuantity; ?></td>
					<td class="billing-column-pc-day"><?php $unitPcAdult1 = $unitPcAdult1 + $pcNonMemAdultUnit; 
							  echo $pcNonMemAdultUnit; ?></td>
					
					
					<!--  PC, Member Student    NIGHT -->
					<td><?php $totalpcN = $totalpcN + $pcMemStudentTotalN +  $pcNonMemStudentTotalN + $pcMemAdultTotalN +  $pcNonMemAdultTotalN;  
							  $totalN =  $pcMemStudentTotalN +  $pcNonMemStudentTotalN + $pcMemAdultTotalN +  $pcNonMemAdultTotalN; 
							  echo number_format($totalN, 2, '.', ''); ?></td> 
					<td><?php $pcQuantityN = $pcQuantityN + $pcMemStudentQuantityN +  $pcNonMemStudentQuantityN + $pcMemAdultQuantityN +  $pcNonMemAdultQuantityN;
							  echo 	$pcMemStudentQuantityN +  $pcNonMemStudentQuantityN + $pcMemAdultQuantityN +  $pcNonMemAdultQuantityN; ?></td> 
					<td><?php $pcUnitN = $pcUnitN + $pcMemStudentUnitN +  $pcNonMemStudentUnitN + $pcMemAdultUnitN +  $pcNonMemAdultUnitN;
						      echo 	$pcMemStudentUnitN +  $pcNonMemStudentUnitN + $pcMemAdultUnitN +  $pcNonMemAdultUnitN; ?></td> 
					
					<td class="billing-column-pc-night"><?php $totalPcStudentN = $totalPcStudentN + number_format($pcMemStudentTotalN, 2, '.', '');
					                    echo number_format($pcMemStudentTotalN, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-night"><?php $quantityPcStudentN = $quantityPcStudentN + $pcMemStudentQuantityN; 
					                    echo $pcMemStudentQuantityN; ?></td> 
					<td class="billing-column-pc-night"><?php $unitPcStudentN = $unitPcStudentN + $pcMemStudentUnitN; 
					                    echo $pcMemStudentUnitN; ?></td> 
					
					<td class="billing-column-pc-night"><?php $totalPcNonStudentN = $totalPcNonStudentN + number_format($pcNonMemStudentTotalN, 2, '.', '');
					                    echo number_format($pcNonMemStudentTotalN, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-night"><?php $quantityPcStudent1N = $quantityPcStudent1N + $pcNonMemStudentQuantityN; 
					                    echo $pcNonMemStudentQuantityN; ?></td>
					<td class="billing-column-pc-night"><?php $unitPcStudent1N = $unitPcStudent1N + $pcNonMemStudentUnitN; 
					                    echo $pcNonMemStudentUnitN; ?></td>
					
					<td class="billing-column-pc-night"><?php $totalPcAdultN = $totalPcAdultN + number_format($pcMemAdultTotalN, 2, '.', '');
					          			echo number_format($pcMemAdultTotalN, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-night"><?php $quantityPcAdultN = $quantityPcAdultN + $pcMemAdultQuantityN; 
					          			echo $pcMemAdultQuantityN; ?></td>
					<td class="billing-column-pc-night"><?php $unitPcAdultN = $unitPcAdultN + $pcMemAdultUnitN; 
					          			echo $pcMemAdultUnitN; ?></td>
					
					<td class="billing-column-pc-night"><?php $totalPcNonAdultN = $totalPcNonAdultN + number_format($pcNonMemAdultTotalN, 2, '.', '');
							  			echo number_format($pcNonMemAdultTotalN, 2, '.', ''); ?></td> 
					<td class="billing-column-pc-night"><?php $quantityPcAdult1N = $quantityPcAdult1N + $pcNonMemAdultQuantityN; 
					   	 	  			echo $pcNonMemAdultQuantityN; ?></td>
					<td class="billing-column-pc-night"><?php $unitPcAdult1N = $unitPcAdult1N + $pcNonMemAdultUnitN; 
							  			echo $pcNonMemAdultUnitN; ?></td>					

					<td><?php $totalAllPrint = $totalAllPrint + $row['Printing & Photostat Color']['total'] + $row['Printing & Photostat B&W']['total']; 
							  $totalPrint = $row['Printing & Photostat Color']['total'] + $row['Printing & Photostat B&W']['total']; 
							  echo number_format($totalPrint, 2, '.', ''); ?></td> 	

					<?php if ($row['Printing & Photostat B&W']['date'] == $key) { ?>
					<td class="billing-column-print"><?php $totalPrintBW = $totalPrintBW + $row['Printing & Photostat B&W']['total'];
										echo number_format($row['Printing & Photostat B&W']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td class="billing-column-print">0.00</td> 
					<?php } ?>

					<?php if ($row['Printing & Photostat Color']['date'] == $key) { ?>										
					<td class="billing-column-print"><?php $totalPrintC = $totalPrintC + $row['Printing & Photostat Color']['total'];
										echo number_format($row['Printing & Photostat Color']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td class="billing-column-print">0.00</td> 
					<?php } ?>
					
					<?php if ($row['Scanning']['date'] == $key) { ?>										
					<td><?php $totalAllScan = $totalAllScan + number_format($row['Scanning']['total'], 2, '.', '');
									echo number_format($row['Scanning']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Laminating (A4)']['date'] == $key) { ?>										
					<td><?php $totalAllLaminate =  $totalAllLaminate + number_format($row['Laminating (A4)']['total'], 2, '.', '');
									echo number_format($row['Laminating (A4)']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Other Service']['date'] == $key) { ?>										
					<td><?php $totalOther = $totalOther + number_format($row['Other Service']['total'], 2, '.', '');
									echo number_format($row['Other Service']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td>0.00</td> 
					<?php } ?>

					<?php if ($row['Utilities']['date'] == $key) { ?>										
					<td><?php $totalUtilities = $totalUtilities + number_format($row['Utilities']['total'], 2, '.', ''); 
									echo number_format($row['Utilities']['total'], 2, '.', ''); ?></td> 
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
					<td><?php echo date('d', strtotime($day))  ?></td> 
					<td>0.00</td><td>0</td> 
					<td class="billing-column-member">0.00</td><td class="billing-column-member">0</td>	
					<td class="billing-column-member">0.00</td><td class="billing-column-member">0</td> 
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td class="billing-column-pc-day">0.00</td><td class="billing-column-pc-day">0</td><td class="billing-column-pc-day">0</td>	
					<td class="billing-column-pc-day">0.00</td><td class="billing-column-pc-day">0</td><td class="billing-column-pc-day">0</td>	
					<td class="billing-column-pc-day">0.00</td><td class="billing-column-pc-day">0</td><td class="billing-column-pc-day">0</td>	
					<td class="billing-column-pc-day">0.00</td><td class="billing-column-pc-day">0</td><td class="billing-column-pc-day">0</td>	
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td class="billing-column-pc-night">0.00</td><td class="billing-column-pc-night">0</td><td class="billing-column-pc-night">0</td>	
					<td class="billing-column-pc-night">0.00</td><td class="billing-column-pc-night">0</td><td class="billing-column-pc-night">0</td>	
					<td class="billing-column-pc-night">0.00</td><td class="billing-column-pc-night">0</td><td class="billing-column-pc-night">0</td>	
					<td class="billing-column-pc-night">0.00</td><td class="billing-column-pc-night">0</td><td class="billing-column-pc-night">0</td>	
										
					<td>0.00</td><td class="billing-column-print">0.00</td><td class="billing-column-print">0.00</td><td>0.00</td><td>0.00</td>	
					<td>0.00</td><td>0.00</td><td> </td> <td>0.00</td><td><?php echo $beginningbalance = number_format($beginningbalance, 2, '.', '');?></td>
				</tr>

	<?php } ?>
				


<?php endforeach;?>
				<tr>	
					<td>Total</td> 
					<td><?php echo number_format($totalMemberMonthly, 2, '.', '') ?></td>
					<td><?php echo $quantityMemberMonthly ?></td>  
					<td class="billing-column-member"><?php echo number_format($totalStudentMonthly, 2, '.', '') ?></td>
					<td class="billing-column-member"><?php echo $quantityStudentMonthly ?></td>  
					<td class="billing-column-member"><?php echo number_format($totalAdultMonthly, 2, '.', '') ?></td>
					<td class="billing-column-member"><?php echo $quantityAdultMonthly ?></td>  
					
					<td><?php echo number_format($totalpc, 2, '.', '') ?></td>
					<td><?php echo $pcQuantity ?></td>  
					<td><?php echo $pcUnit ?></td>  
					<td class="billing-column-pc-day"><?php echo number_format($totalPcStudent, 2, '.', '') ?></td>
					<td class="billing-column-pc-day"><?php echo $quantityPcStudent ?></td>  
					<td class="billing-column-pc-day"><?php echo $unitPcStudent ?></td>  
					<td class="billing-column-pc-day"><?php echo number_format($totalPcNonStudent, 2, '.', '') ?></td>
					<td class="billing-column-pc-day"><?php echo $quantityPcStudent1 ?></td>  
					<td class="billing-column-pc-day"><?php echo $unitPcStudent1 ?></td>  
					<td class="billing-column-pc-day"><?php echo number_format($totalPcAdult, 2, '.', '') ?></td>
					<td class="billing-column-pc-day"><?php echo $quantityPcAdult ?></td>  
					<td class="billing-column-pc-day"><?php echo $unitPcAdult ?></td>  
					<td class="billing-column-pc-day"><?php echo number_format($totalPcNonAdult, 2, '.', '') ?></td>
					<td class="billing-column-pc-day"><?php echo $quantityPcAdult1 ?></td>  
					<td class="billing-column-pc-day"><?php echo $unitPcAdult1 ?></td>  
					
					<td><?php echo number_format($totalpcN, 2, '.', '') ?></td>
					<td><?php echo $pcQuantityN ?></td>  
					<td><?php echo $pcUnitN ?></td>  
					<td class="billing-column-pc-night"><?php echo number_format($totalPcStudentN, 2, '.', '') ?></td>
					<td class="billing-column-pc-night"><?php echo $quantityPcStudentN ?></td>  
					<td class="billing-column-pc-night"><?php echo $unitPcStudentN ?></td>  
					<td class="billing-column-pc-night"><?php echo number_format($totalPcNonStudentN, 2, '.', '') ?></td>
					<td class="billing-column-pc-night"><?php echo $quantityPcStudent1N ?></td>  
					<td class="billing-column-pc-night"><?php echo $unitPcStudent1N ?></td>  
					<td class="billing-column-pc-night"><?php echo number_format($totalPcAdultN, 2, '.', '') ?></td>
					<td class="billing-column-pc-night"><?php echo $quantityPcAdultN ?></td>  
					<td class="billing-column-pc-night"><?php echo $unitPcAdultN ?></td>  
					<td class="billing-column-pc-night"><?php echo number_format($totalPcNonAdultN, 2, '.', '') ?></td>
					<td class="billing-column-pc-night"><?php echo $quantityPcAdult1N ?></td>  
					<td class="billing-column-pc-night"><?php echo $unitPcAdult1N ?></td>  
										
					<td><?php echo number_format($totalAllPrint, 2, '.', '') ?></td>
					<td class="billing-column-print"><?php echo number_format($totalPrintBW, 2, '.', '') ?></td>
					<td class="billing-column-print"><?php echo number_format($totalPrintC, 2, '.', '') ?></td>
					<td><?php echo number_format($totalAllScan, 2, '.', '') ?></td>
					<td><?php echo number_format($totalAllLaminate, 2, '.', '') ?></td>
					<td><?php echo number_format($totalOther, 2, '.', '') ?></td>
					<td><?php echo number_format($totalUtilities, 2, '.', '') ?></td>
					<td> </td> 
					<td><?php echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
					<td><?php echo $beginningbalance = number_format($beginningbalance, 2, '.', '');?></td>
				</tr>
				<tr>					
					<th id="f1" colspan="47"></th>
				</tr>
				<tr style="background-color:#ededed">	
					<th> </th>
					<th id="g1" colspan="46"> Bank In </th>
				</tr>
			<?php

			 foreach ($transferList as $key => $row):?>
				<tr>
					<td><?php echo date('d', strtotime($row['date']))  ?></td> 
					<td class="j1" colspan="44"><?php echo $row['description']?></td>
					<td><?php echo number_format($row['total'], 2, '.', '');?></td>
					<td><?php $beginningbalance = $beginningbalance + $row['total'];
					echo number_format($beginningbalance, 2, '.', '');?></td>
				</tr>
			<?php endforeach;?>

			<?php else:?>		
				<tr>
					<td id="f1" colspan="47"> No Transaction</td>
				</tr>
				<?php endif; ?>	

			</table>			
		</div>
	</div>

<div class="col-sm-12  ">
		<div class='well well-sm'>
			Monthly Verification
		</div>

		<div class="table-responsive">
		<form class="form-inline bs-example" method='post' action='<?php echo url::base('billing/dailyCashProcess/'.$siteID);?>'>
			<table class='table ' border='0'>
					<input type="hidden" name="siteID" value="<?php echo $siteID?>"> 
					<input type="hidden" name="year" value="<?php echo $selectYear?>"> 
					<input type="hidden" name="month" value="<?php echo $selectMonth?>"> 
				<tr style="background-color:#ededed">	
					<th></th> 
					<th>Site Manager</th>
					<th>Cluster Lead</th>
					<th>Financial Controller</th>
				</tr>		
				<?php if(count($list) > 0):?>
				<tr>	
					<td>Month Total</td> 
					<td><?php $dailytotal = $beginningbalance - $balance;
							echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
					<td><?php $dailytotal = $beginningbalance - $balance;
							echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
					<td><?php $dailytotal = $beginningbalance - $balance;
							echo $dailytotal = number_format($dailytotal, 2, '.', '');?></td>
				</tr>	

				<tr>	
					<td>Balance</td> 
					<td><?php echo number_format($beginningbalance, 2, '.', '');?></td>
					<td><?php echo number_format($beginningbalance, 2, '.', '');?></td>
					<td><?php echo number_format($beginningbalance, 2, '.', '');?></td>
				</tr>	

				<tr>	
					<td>Status</td> 
					<td><?php
					
					if((session::get("userLevel") == 2) &&  ($checked == 1) && ($approved != 1)) {  
					
						echo $checkedword;
					 } elseif((session::get("userLevel") == 2) &&  ($checked == 1) && ($approved == 1)) {  

					 	echo $checkedword;
					 } elseif ((session::get("userLevel") == 2) && ($checked != 1)) {  ?>	
					
					<button name="submit" type="submit" class="btn btn-sm btn-default" value="1">Check</button>									
					<?php } elseif ((session::get("userLevel") != 2) && ($checked == 1)) { 
					
					echo $checkedword;
					 } else { ?>
					
					not checked					
					<?php } ?>
					</td>

					<td>
					<?php  if((session::get("userLevel") == 3) && ($approved == 1)) { 

					 echo $approvedword;
					 } elseif((session::get("userLevel") == 3) && ($approved != 1) && ($checked == 1)) {  ?>	

					<button name="submit" type="submit" class="btn btn-sm btn-default" value="1">Approve</button> <button name="submit" type="submit" class="btn btn-sm btn-default"  value="2">Reject</button>
					<?php } elseif ((session::get("userLevel") != 3) && ($approved == 1)) { 
					
					echo $approvedword;
					 } else { ?>
					
					not verified
					<?php } ?>
					</td>

					<td>
					<?php  if((session::get("userLevel") == 5) && ($closed == 1)) { 

					 echo $closedword;
					 } elseif((session::get("userLevel") == 5) && ($closed != 1)&& ($approved == 1) && ($checked == 1)) {  ?>	

					<button name="submit" type="submit" class="btn btn-sm btn-default" value="1">Close</button> <button name="submit" type="submit" class="btn btn-sm btn-default"  value="2">Reject</button>
					<?php } elseif ((session::get("userLevel") != 5) && ($closed == 1)) { 
					
					echo $closedword;
					 } else { ?>
					
					
					<?php } ?>
					</td>
				</tr>	


			<?php else:?>		
				<tr>	
					<td>Month Total</td> 
					<td></td>
					<td></td>
					<td></td>
				</tr>	

				<tr>	
					<td>Balance</td> 
					<td></td>
					<td></td>
					<td></td>
				</tr>	

				<tr>	
					<td>Status</td> 
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php endif; ?>	

			</table>
			</form>
		</div>	
	</div>
</div>	

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
		
		</div>
		
	
	</div>
</div>
<?php 






































































return;






























































































?>
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript">
	
$(document).ready(function()
{
	$('.row').floatingScrollbar();
});

</script>
<style type="text/css">

.ov {
		overflow-x: auto;
  		width: 100%;
	}

</style>
<h3 class="m-b-xs text-black">
	Daily Cash Process
</h3>
<div class="well well-sm">
	Choose month and year
</div>
<div class="row">
	<div class="col-sm-10">
		<form class="form-inline bs-example" method="post" action="">
			<div class="form-group" style="margin-left:10px">
				<select name="selectMonth" id="selectMonth" onchange="billing.select();"><option value="">[PLEASE CHOOSE]</option><option value="1">JANUARY</option><option value="2">FEBRUARY</option><option value="3">MARCH</option><option value="4">APRIL</option><option value="5">MAY</option><option value="6">JUNE</option><option value="7">JULY</option><option value="8">AUGUST</option><option value="9">SEPTEMBER</option><option value="10" selected="">OCTOBER</option><option value="11">NOVEMBER</option><option value="12">DECEMBER</option></select>				<select name="selectYear" id="selectYear" onchange="billing.select();"><option value="">[PLEASE CHOOSE]</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015" selected="">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option></select>			
			</div>			
		</form>	
	</div>
</div>
<div class='row ov'>
	<div class='col-sm-12'>
	<div class="well well-sm">
	 	<?php echo $site->siteName;?> Monthly Cash Report for <?php echo $selectYear;?>/<?php echo $selectMonth;?></div>
	<div class='table-responsive'>
		<table class='table b-t b-light'>
			<tr>
				<tr style="background-color:#ededed">	
					<th></th> 
					<th colspan="3">Member</th>
					<th colspan="5">PC Day</th>
					<th colspan="5">PC Night</th>
					<th colspan="3">Print</th>
					<th>Scan</th>	
					<th>Laminate</th>	
					<th>Other</th>		
					<th></th><th></th>
					<th colspan="2">Day End</th>
				</tr>
				<tr bgcolor="#ededed">	
					<th></th> 		
					<th>
						<a id="a2" href="#" onclick="test.select();" class="fa pull-right fa-plus-square" style="font-size:13px;"></a>Total
					</th>
					<th>Student</th>	
					<th>Adult</th>
					<th>
						<a id="b2" href="#" onclick="test.select();" class="fa pull-right fa-plus-square" style="font-size:13px;"></a>Total
					</th>
					<th>Student Member</th>
					<th>Student nonMember</th>
					<th>Adult Member</th>
					<th>Adult NonMember</th>
					<th>
						<a id="c2" href="#" onclick="test.select();" class="fa pull-right fa-plus-square" style="font-size:13px;"></a>Total
					</th>
					<th>Student Member</th>
					<th>Student nonMember</th>
					<th>Adult Member</th>
					<th>Adult NonMember</th>
					<th><a id="d2" href="#" onclick="test.select();" class="fa pull-right fa-plus-square" style="font-size:13px;"></a>Total</th>	
					<th>B/W</th>	
					<th>Color</th>	
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tr>
			<?php foreach(range(1, date('t', strtotime($selectYear.'-'.$selectMonth.'-01'))) as $day):
			$date = $selectYear.'-'.$selectMonth.'-'.$day;
			$date = date('Y-m-d', strtotime($date));
			?>
			<tr>
				<td><?php echo date('d', strtotime($date));?></td>
			</tr>
			<?php endforeach;?>
		</table>
	</div>
	</div>
</div>