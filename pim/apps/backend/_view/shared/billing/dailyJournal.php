<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<script type="text/javascript">

var base_url	= "<?php echo url::base();?>/";

	$(document).ready(function() {

	$("#selectDateStart").on("changeDate", function(ev)
	{
		var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";  		
		var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
		var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/dailyJournal?"+siteID+selectDateStart+selectDateEnd;
		});

	$("#selectDateEnd").on("changeDate", function(ev)
		{

			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";  		
			var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
			var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/dailyJournal?"+siteID+selectDateStart+selectDateEnd;
		});

		function getParameterByName(name) {
    		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        	results = regex.exec(location.search);
    		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	});
	
		var billing	= new function()
	{	
		this.select	= function()
		{					
			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";			
			var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
			var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/dailyJournal?"+siteID+selectDateStart+selectDateEnd;
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

</style>

<h3 class='m-b-xs text-black'>
	Daily Journal
</h3>
<div class='well well-sm'>
	Daily Journal For 
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method='post' action='<?php echo url::base('billing/addTransaction/'.$itemSelect->billingItemID);?>'>
			<?php  if(session::get("userLevel") == 99): ?>	
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select($itemID);'",request::get("siteID"),"[SELECT SITE]");?>			
			</div>
			<?php endif;?>
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::text("selectDateStart","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateStart)));?>			
			</div>
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::text("selectDateEnd","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateEnd)));?>			
			</div>
		</form>	
	</div>
</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
			A
		</div>
		
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th>Date</th>					
					<th>Description</th>				
					<th>User</th>
					<th>Unit/Hours</th>					
					<th>Total</th>
				</tr>
				
			<?php if(count($journal) > 0):?>
			<?php foreach($journal as $key => $journalItem):?>

			<?php 

			$checkDate = date('Y-m-d', strtotime($journalItem['billingTransactionDate']));
				
			   if (($date1 != $checkDate) && ($key != 0)) { ?>
				<tr>
					<td></td>
					<th>Total : </th>
					<th><?php echo $journalQuantity[array_search($date1, $journalDate)]; ?></th>
					<th><?php echo $journalUnit[array_search($date1, $journalDate)]; ?></th>
					<th><?php echo number_format($journalTotal[array_search($date1, $journalDate)], 2, '.', ''); ?></th>
				</tr>
			<?php $date1 = $checkDate; } else { $date1 = $checkDate; } ?>


				<tr>
				<?php if ($date != $checkDate)  { ?>
					<td><?php echo $checkDate; ?></td>
				<?php $date = $checkDate; } else { ?>
					<td></td>
				<?php $date = $checkDate; } ?>
					<td><?php echo $journalItem[billingItemName]." : ".$journalItem[billingItemDescription]; ?></td>
					<td><?php echo $journalItem[quantity]; ?></td>
					<td><?php echo $journalItem[unit]; ?></td>					
					<td><?php echo number_format($journalItem[total], 2, '.', ''); ?></td>
				</tr>

			<?php   if ($key == (count($journal)-1)) { ?>
				<tr>
					<td></td>
					<th>Total : </th>
					<th><?php echo $journalQuantity[array_search($date1, $journalDate)]; ?></th>
					<th><?php echo $journalUnit[array_search($date1, $journalDate)]; ?></th>
					<th><?php echo number_format($journalTotal[array_search($date1, $journalDate)], 2, '.', ''); ?></th>
				</tr>

			<?php } ?>
			<?php endforeach;?>
			<?php else:?>		
				<tr>
					<td colspan="8"> No Transaction</td>
				</tr>
				<?php endif; ?>	

			</table>
			
		</div>
	</div>
</div>	