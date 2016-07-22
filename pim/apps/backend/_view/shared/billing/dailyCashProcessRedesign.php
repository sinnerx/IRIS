<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript">

	$(document).ready(function() {

		if($("#siteID").val() == "")
			$("#export_excel_btn").hide();
		else
			$("#export_excel_btn").show();

		$('.row').floatingScrollbar();

		// table.toggleExpand();

		// show hide column
		$('.showhidecol').click(function(){

			//change color button
			$(this).toggleClass( 'btn-warning', 'add' );

			var row  = $(this).data('row');
			var col  = $(this).data('col');

			// separate col data
			eachCol = col.split(",");

			// row 1
			// split column data
			var colData1 = eachCol[0].split(":"); // eg: 2:4 / 2 - start / 4 - end
			
			// if only 1 column
			if (colData1[1] == 1) {
				$('.custom-table tr:nth-child(1) th:nth-child('+colData1[0]+')').toggle();
			} else {
				var colEnd1 = 1+Number(colData1[1]); // eg: 1 + 4 = 5 / Total Loop

				//loop for total column
				for (var i = colData1[0]; i < colEnd1; i++) {
					$('.custom-table tr:nth-child(1) th:nth-child('+i+')').toggle();
				};
			}

			// row 2
			// split column data
			var colData2 = eachCol[1].split(":"); // eg: 2:4 / 2 - start / 4 - end

			// if only 1 column
			if (colData2[1] == 1) {
				$('.custom-table tr:nth-child(2) th:nth-child('+colData2[0]+')').toggle();
			} else {
				var colEnd2 = 1+Number(colData2[1]); // eg: 1 + 4 = 5 / Total Loop

				//loop for total column
				for (var i = colData2[0]; i < colEnd2; i++) {
					$('.custom-table tr:nth-child(2) th:nth-child('+i+')').toggle();
				};
			}

			// row 3
			// split column data
			var colData3 = eachCol[2].split(":"); // eg: 2:4 / 2 - start / 4 - end
			
			// if only 1 column
			if (colData3[1] == 1) {
				$('.custom-table tr:nth-child(3) th:nth-child('+colData3[0]+')').toggle();
			} else {
				var colEnd3 = 1+Number(colData3[1]); // eg: 1 + 4 = 5 / Total Loop

				//loop for total column
				for (var i = colData3[0]; i < colEnd3; i++) {
					$('.custom-table tr:nth-child(3) th:nth-child('+i+')').toggle();
				};
			}

			// get all row
			var rowCount = $('.custom-table tr').length;
			// minus 7 row
			// 3 bottom row
			// 4 top row
			var totalRow = rowCount - 7;

			// row loop data
			// loop for total column
			for (var i = 5; i < 5+totalRow+1; i++) {
				if (colData3[1] == 1) {
					$('.custom-table tr:nth-child('+i+') td:nth-child('+colData3[0]+')').toggle();
				} else {
					for (var ii = colData3[0]; ii < colEnd3; ii++) {
						$('.custom-table tr:nth-child('+i+') td:nth-child('+ii+')').toggle();
						// last row
						$('.custom-table tr:nth-child('+rowCount+') td:nth-child('+ii+')').toggle();
					}
				}
			};

			// column 4
			// get all column
			var colCount = $('.custom-table tr:nth-child(5) td:visible').length;
			$('#e1').attr('colspan',colCount-2);
			//alert(colCount-2);

		});

		$('#export_excel_btn').click(function () {
			console.log($("#siteID").val());
			window.location.href = pim.url('expExcel/getDailyCashProcess/' + $("#siteID").val() + '/' + $("#selectMonth").val() + '/' + $("#selectYear").val());
		});

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

	/* bold left border for th and td */
	.custom-table tr th, tr td
	{
		border-left: 2px solid #ddd;
		border-right: 2px solid #ddd;
	}

	/* remove bottom border for td */
	.custom-table .skip-top
	{
		border-top: none !important;
	}

	.custom-table .skip-border td
	{
		border-top: 1px solid #ddd !important;
		border-bottom: 1px solid #ddd !important;
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
				<input type="button" id="export_excel_btn" class="btn btn-sm btn-default" value="Export to Excel">			
			</div>			
		</form>	
	</div>
</div>

<?php if($site):?>
<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			 <?php 
			  echo $site->siteName; ?> Monthly Cash Report for <?php echo $selectYear; ?>/<?php echo $selectMonth; ?>
		</div>
		<div class='well well-sm'>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="2:1,2:4,2:7">Member</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="3:1,5:9,8:22">PC Day</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="4:1,10:14,23:37" >PC Night</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="5:1,15:17,38:40" >Print</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="6:1,18:1,41:1" >Scan</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="7:1,19:1,42:1" >Laminate</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="8:10,20:22,43:45" >Other</a>
			<a href="#" class="btn btn-info btn-xs showhidecol" role="button" data-row="3" data-col="11:1,23:24,46:47" >Day End</a>
		</div>
		<div class="table-responsive">
			<table class='table b-t b-light custom-table'>

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

				<tr bgcolor="#ededed" >	
					<th class="skip-top"></th> 		
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
					<th class="skip-top">Day</th> 
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

				<tr class="skip-border">					
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
			<tr class="left-td">
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
<?php elseif(authData('user.userLevel') != 2):?>
<div class='well well-sm'>Please choose one of the sites</div>
<?php endif;?>




<?php 






































































return;
/* BELOW HERE IS A POINT OF NO RETURN CODES! */





























































































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