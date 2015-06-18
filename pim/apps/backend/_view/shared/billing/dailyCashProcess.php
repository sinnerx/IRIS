<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript">

	$(document).ready(function() {

		$('.row').floatingScrollbar();

		$(".a").hide();
		$(".b").hide();
		$(".c").hide();
		$(".d").hide();
		$('#a1').attr('colspan','2');
		$('#b1').attr('colspan','3');
		$('#c1').attr('colspan','3');
		$('#d1').attr('colspan','1');
		$('#e1').attr('colspan','15');
		$('#f1').attr('colspan','17');
		$('#g1').attr('colspan','16');
		$('.j1').attr('colspan','14');

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

	var test = new function()
	{	
		this.select	= function()
		{	
			if	($('#a2').hasClass('fa-minus-square')){
				$(".a").hide();
				$('#a1').attr('colspan','2');
				$('#a2').removeClass('fa-minus-square').addClass('fa-plus-square');

				$('#e1').attr('colspan','15');
				$('#f1').attr('colspan','17');
				$('#g1').attr('colspan','16');
				$('.j1').attr('colspan','14');

			} else {
				$(".a").show();
				$('#a1').attr('colspan','6');
				$('#a2').removeClass('fa-plus-square').addClass('fa-minus-square');

				$('#e1').attr('colspan','45');
				$('#f1').attr('colspan','47');
				$('#g1').attr('colspan','46');
				$('.j1').attr('colspan','44');

			}	 

			if	($('#b2').hasClass('fa-minus-square')){
				$(".b").hide();
				$('#b1').attr('colspan','3');
				$('#b2').removeClass('fa-minus-square').addClass('fa-plus-square');
			} else {
				$(".b").show();
				$('#b1').attr('colspan','15');
				$('#b2').removeClass('fa-plus-square').addClass('fa-minus-square');
			}	 

			if	($('#c2').hasClass('fa-minus-square')){
				$(".c").hide();
				$('#c1').attr('colspan','3');
				$('#c2').removeClass('fa-minus-square').addClass('fa-plus-square');
			} else {
				$(".c").show();
				$('#c1').attr('colspan','15');
				$('#c2').removeClass('fa-plus-square').addClass('fa-minus-square');
			}	 

			if	($('#d2').hasClass('fa-minus-square')){
				$(".d").hide();
				$('#d1').attr('colspan','1');
				$('#d2').removeClass('fa-minus-square').addClass('fa-plus-square');
			} else {
				$(".d").show();
				$('#d1').attr('colspan','3');
				$('#d2').removeClass('fa-plus-square').addClass('fa-minus-square');
			}	 

			

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
				<?php echo form::select("selectMonth",model::load("helper")->monthYear("month"),"onchange='billing.select();'",$selectMonth);?>
				<?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='billing.select();'",$selectYear);?>			
			</div>			
		</form>	
	</div>
</div>

<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			 <?php 
			  echo array_search($siteID, array_flip($siteList)); ?> Monthly Cash Report for <?php echo $selectYear; ?>/<?php echo $selectMonth; ?>
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
					<th colspan="2"><a id ="a2" href="#" onclick='test.select();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>
					<th class="a" colspan="2">Student</th>	
					<th class="a" colspan="2">Adult</th>

					<th colspan="3"><a id ="b2" href="#" onclick='test.select();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>
					<th class="b" colspan="3">Student Member</th>
					<th class="b" colspan="3">Student nonMember</th>
					<th class="b" colspan="3">Adult Member</th>
					<th class="b" colspan="3">Adult NonMember</th>

					<th colspan="3"><a id ="c2" href="#" onclick='test.select();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>
					<th class="c" colspan="3">Student Member</th>
					<th class="c" colspan="3">Student nonMember</th>
					<th class="c" colspan="3">Adult Member</th>
					<th class="c" colspan="3">Adult NonMember</th>

					<th><a id ="d2" href="#" onclick='test.select();' class='fa fa-plus-square pull-right' style='font-size:13px;'></a>Total</th>	
					<th class="d">B/W</th>	
					<th class="d">Color</th>	
					<th></th><th></th><th></th><th></th><th></th><th></th><th></th>
				</tr>			

				<tr bgcolor="#ededed">	
					<th>Day</th> 
					<th>RM</th> <th>User</th> 
					<th class="a">RM</th> <th class="a">User</th>	
					<th class="a">RM</th> <th class="a">User</th> 
					
					<th>RM</th> <th>User</th> <th>Hr</th> 
					<th class="b">RM</th>	<th class="b">User</th> <th class="b">Hr</th>	
					<th class="b">RM</th>	<th class="b">User</th> <th class="b">Hr</th>	
					<th class="b">RM</th>	<th class="b">User</th> <th class="b">Hr</th>	
					<th class="b">RM</th>	<th class="b">User</th> <th class="b">Hr</th>	
					
					<th>RM</th>	<th>User</th> <th>Hr</th>	
					<th class="c">RM</th>	<th class="c">User</th> <th class="c">Hr</th>
					<th class="c">RM</th>	<th class="c">User</th> <th class="c">Hr</th>
					<th class="c">RM</th>	<th class="c">User</th> <th class="c">Hr</th>
					<th class="c">RM</th>	<th class="c">User</th> <th class="c">Hr</th>
										
					<th>RM</th>	<th class="d">RM</th>	<th class="d">RM</th>	<th>RM</th>	<th>RM</th>	
					<th>RM</th>	<th>Utilities</th>	<th>Description </th>	 <th>Total</th>	<th>Balance</th>
				</tr>
				
			<?php if(count($list) > 0):?>
				<tr>					
					<td><?php echo date('d', strtotime($alldate[0]))  ?></td> 
					<td  id="e1" colspan="45"> Monthly Revenue (Previous Balance) </td>
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
					<td><?php echo date('d', strtotime($key))  ?></td> 
									
					<td><?php $totalMemberMonthly = $totalMemberMonthly + $row['Membership Student']['total'] +  $row['Membership Adult']['total']; 
							  $totalMember = $row['Membership Student']['total'] +  $row['Membership Adult']['total'];
						echo number_format($totalMember, 2, '.', '') ?></td> 
					<td><?php $quantityMemberMonthly = $quantityMemberMonthly + $row['Membership Student']['quantity'] + $row['Membership Adult']['quantity'];
								echo $row['Membership Student']['quantity'] + $row['Membership Adult']['quantity'] ?></td> 

					<td class="a"><?php $totalStudentMonthly = $totalStudentMonthly + $row['Membership Student']['total'];
									echo number_format($row['Membership Student']['total'], 2, '.', '') ?></td> 
					<td class="a"><?php $quantityStudentMonthly = $quantityStudentMonthly +  $row['Membership Student']['quantity'];
									echo number_format($row['Membership Student']['quantity'], 0, '', '') ?></td> 


					<td class="a"><?php $totalAdultMonthly = $totalAdultMonthly + $row['Membership Adult']['total'];
									echo number_format($row['Membership Adult']['total'], 2, '.', '') ?></td> 
					<td class="a"><?php $quantityAdultMonthly = $quantityAdultMonthly +  $row['Membership Adult']['quantity'];
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
					
					<td class="b"><?php $totalPcStudent = $totalPcStudent + number_format($pcMemStudentTotal, 2, '.', '');
							  echo number_format($pcMemStudentTotal, 2, '.', ''); ?></td> 
					<td class="b"><?php $quantityPcStudent = $quantityPcStudent + $pcMemStudentQuantity; 
							  echo $pcMemStudentQuantity; ?></td> 
					<td class="b"><?php $unitPcStudent = $unitPcStudent + $pcMemStudentUnit; 
					          echo $pcMemStudentUnit; ?></td> 
					
					<td class="b"><?php $totalPcNonStudent = $totalPcNonStudent + number_format($pcNonMemStudentTotal, 2, '.', '');
					          echo number_format($pcNonMemStudentTotal, 2, '.', ''); ?></td> 
					<td class="b"><?php $quantityPcStudent1 = $quantityPcStudent1 + $pcNonMemStudentQuantity; 
					          echo $pcNonMemStudentQuantity; ?></td>
					<td class="b"><?php $unitPcStudent1 = $unitPcStudent1 + $pcNonMemStudentUnit; 
					          echo $pcNonMemStudentUnit; ?></td>
					
					<td class="b"><?php $totalPcAdult = $totalPcAdult + number_format($pcMemAdultTotal, 2, '.', '');
					          echo number_format($pcMemAdultTotal, 2, '.', ''); ?></td> 
					<td class="b"><?php $quantityPcAdult = $quantityPcAdult + $pcMemAdultQuantity; 
					          echo $pcMemAdultQuantity; ?></td>
					<td class="b"><?php $unitPcAdult = $unitPcAdult + $pcMemAdultUnit; 
					          echo $pcMemAdultUnit; ?></td>
					
					<td class="b"><?php $totalPcNonAdult = $totalPcNonAdult + number_format($pcNonMemAdultTotal, 2, '.', '');
							  echo number_format($pcNonMemAdultTotal, 2, '.', ''); ?></td> 
					<td class="b"><?php $quantityPcAdult1 = $quantityPcAdult1 + $pcNonMemAdultQuantity; 
					   	 	  echo $pcNonMemAdultQuantity; ?></td>
					<td class="b"><?php $unitPcAdult1 = $unitPcAdult1 + $pcNonMemAdultUnit; 
							  echo $pcNonMemAdultUnit; ?></td>
					
					
					<!--  PC, Member Student    NIGHT -->
					<td><?php $totalpcN = $totalpcN + $pcMemStudentTotalN +  $pcNonMemStudentTotalN + $pcMemAdultTotalN +  $pcNonMemAdultTotalN;  
							  $totalN =  $pcMemStudentTotalN +  $pcNonMemStudentTotalN + $pcMemAdultTotalN +  $pcNonMemAdultTotalN; 
							  echo number_format($totalN, 2, '.', ''); ?></td> 
					<td><?php $pcQuantityN = $pcQuantityN + $pcMemStudentQuantityN +  $pcNonMemStudentQuantityN + $pcMemAdultQuantityN +  $pcNonMemAdultQuantityN;
							  echo 	$pcMemStudentQuantityN +  $pcNonMemStudentQuantityN + $pcMemAdultQuantityN +  $pcNonMemAdultQuantityN; ?></td> 
					<td><?php $pcUnitN = $pcUnitN + $pcMemStudentUnitN +  $pcNonMemStudentUnitN + $pcMemAdultUnitN +  $pcNonMemAdultUnitN;
						      echo 	$pcMemStudentUnitN +  $pcNonMemStudentUnitN + $pcMemAdultUnitN +  $pcNonMemAdultUnitN; ?></td> 
					
					<td class="c"><?php $totalPcStudentN = $totalPcStudentN + number_format($pcMemStudentTotalN, 2, '.', '');
					                    echo number_format($pcMemStudentTotalN, 2, '.', ''); ?></td> 
					<td class="c"><?php $quantityPcStudentN = $quantityPcStudentN + $pcMemStudentQuantityN; 
					                    echo $pcMemStudentQuantityN; ?></td> 
					<td class="c"><?php $unitPcStudentN = $unitPcStudentN + $pcMemStudentUnitN; 
					                    echo $pcMemStudentUnitN; ?></td> 
					
					<td class="c"><?php $totalPcNonStudentN = $totalPcNonStudentN + number_format($pcNonMemStudentTotalN, 2, '.', '');
					                    echo number_format($pcNonMemStudentTotalN, 2, '.', ''); ?></td> 
					<td class="c"><?php $quantityPcStudent1N = $quantityPcStudent1N + $pcNonMemStudentQuantityN; 
					                    echo $pcNonMemStudentQuantityN; ?></td>
					<td class="c"><?php $unitPcStudent1N = $unitPcStudent1N + $pcNonMemStudentUnitN; 
					                    echo $pcNonMemStudentUnitN; ?></td>
					
					<td class="c"><?php $totalPcAdultN = $totalPcAdultN + number_format($pcMemAdultTotalN, 2, '.', '');
					          			echo number_format($pcMemAdultTotalN, 2, '.', ''); ?></td> 
					<td class="c"><?php $quantityPcAdultN = $quantityPcAdultN + $pcMemAdultQuantityN; 
					          			echo $pcMemAdultQuantityN; ?></td>
					<td class="c"><?php $unitPcAdultN = $unitPcAdultN + $pcMemAdultUnitN; 
					          			echo $pcMemAdultUnitN; ?></td>
					
					<td class="c"><?php $totalPcNonAdultN = $totalPcNonAdultN + number_format($pcNonMemAdultTotalN, 2, '.', '');
							  			echo number_format($pcNonMemAdultTotalN, 2, '.', ''); ?></td> 
					<td class="c"><?php $quantityPcAdult1N = $quantityPcAdult1N + $pcNonMemAdultQuantityN; 
					   	 	  			echo $pcNonMemAdultQuantityN; ?></td>
					<td class="c"><?php $unitPcAdult1N = $unitPcAdult1N + $pcNonMemAdultUnitN; 
							  			echo $pcNonMemAdultUnitN; ?></td>					

					<td><?php $totalAllPrint = $totalAllPrint + $row['Printing & Photostat Color']['total'] + $row['Printing & Photostat B&W']['total']; 
							  $totalPrint = $row['Printing & Photostat Color']['total'] + $row['Printing & Photostat B&W']['total']; 
							  echo number_format($totalPrint, 2, '.', ''); ?></td> 	

					<?php if ($row['Printing & Photostat B&W']['date'] == $key) { ?>
					<td class="d"><?php $totalPrintBW = $totalPrintBW + $row['Printing & Photostat B&W']['total'];
										echo number_format($row['Printing & Photostat B&W']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td class="d">0.00</td> 
					<?php } ?>

					<?php if ($row['Printing & Photostat Color']['date'] == $key) { ?>										
					<td class="d"><?php $totalPrintC = $totalPrintC + $row['Printing & Photostat Color']['total'];
										echo number_format($row['Printing & Photostat Color']['total'], 2, '.', ''); ?></td> 
					<?php } else { ?>
					<td class="d">0.00</td> 
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
					<td class="a">0.00</td><td class="a">0</td>	
					<td class="a">0.00</td><td class="a">0</td> 
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td class="b">0.00</td><td class="b">0</td><td class="b">0</td>	
					<td class="b">0.00</td><td class="b">0</td><td class="b">0</td>	
					<td class="b">0.00</td><td class="b">0</td><td class="b">0</td>	
					<td class="b">0.00</td><td class="b">0</td><td class="b">0</td>	
					
					<td>0.00</td><td>0</td><td>0</td> 
					<td class="c">0.00</td><td class="c">0</td><td class="c">0</td>	
					<td class="c">0.00</td><td class="c">0</td><td class="c">0</td>	
					<td class="c">0.00</td><td class="c">0</td><td class="c">0</td>	
					<td class="c">0.00</td><td class="c">0</td><td class="c">0</td>	
										
					<td>0.00</td><td class="d">0.00</td><td class="d">0.00</td><td>0.00</td><td>0.00</td>	
					<td>0.00</td><td>0.00</td><td> </td> <td>0.00</td><td><?php echo $beginningbalance = number_format($beginningbalance, 2, '.', '');?></td>
				</tr>

	<?php } ?>
				


<?php endforeach;?>
				<tr>	
					<td>Total</td> 
					<td><?php echo number_format($totalMemberMonthly, 2, '.', '') ?></td>
					<td><?php echo $quantityMemberMonthly ?></td>  
					<td class="a"><?php echo number_format($totalStudentMonthly, 2, '.', '') ?></td>
					<td class="a"><?php echo $quantityStudentMonthly ?></td>  
					<td class="a"><?php echo number_format($totalAdultMonthly, 2, '.', '') ?></td>
					<td class="a"><?php echo $quantityAdultMonthly ?></td>  
					
					<td><?php echo number_format($totalpc, 2, '.', '') ?></td>
					<td><?php echo $pcQuantity ?></td>  
					<td><?php echo $pcUnit ?></td>  
					<td class="b"><?php echo number_format($totalPcStudent, 2, '.', '') ?></td>
					<td class="b"><?php echo $quantityPcStudent ?></td>  
					<td class="b"><?php echo $unitPcStudent ?></td>  
					<td class="b"><?php echo number_format($totalPcNonStudent, 2, '.', '') ?></td>
					<td class="b"><?php echo $quantityPcStudent1 ?></td>  
					<td class="b"><?php echo $unitPcStudent1 ?></td>  
					<td class="b"><?php echo number_format($totalPcAdult, 2, '.', '') ?></td>
					<td class="b"><?php echo $quantityPcAdult ?></td>  
					<td class="b"><?php echo $unitPcAdult ?></td>  
					<td class="b"><?php echo number_format($totalPcNonAdult, 2, '.', '') ?></td>
					<td class="b"><?php echo $quantityPcAdult1 ?></td>  
					<td class="b"><?php echo $unitPcAdult1 ?></td>  
					
					<td><?php echo number_format($totalpcN, 2, '.', '') ?></td>
					<td><?php echo $pcQuantityN ?></td>  
					<td><?php echo $pcUnitN ?></td>  
					<td class="c"><?php echo number_format($totalPcStudentN, 2, '.', '') ?></td>
					<td class="c"><?php echo $quantityPcStudentN ?></td>  
					<td class="c"><?php echo $unitPcStudentN ?></td>  
					<td class="c"><?php echo number_format($totalPcNonStudentN, 2, '.', '') ?></td>
					<td class="c"><?php echo $quantityPcStudent1N ?></td>  
					<td class="c"><?php echo $unitPcStudent1N ?></td>  
					<td class="c"><?php echo number_format($totalPcAdultN, 2, '.', '') ?></td>
					<td class="c"><?php echo $quantityPcAdultN ?></td>  
					<td class="c"><?php echo $unitPcAdultN ?></td>  
					<td class="c"><?php echo number_format($totalPcNonAdultN, 2, '.', '') ?></td>
					<td class="c"><?php echo $quantityPcAdult1N ?></td>  
					<td class="c"><?php echo $unitPcAdult1N ?></td>  
										
					<td><?php echo number_format($totalAllPrint, 2, '.', '') ?></td>
					<td class="d"><?php echo number_format($totalPrintBW, 2, '.', '') ?></td>
					<td class="d"><?php echo number_format($totalPrintC, 2, '.', '') ?></td>
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