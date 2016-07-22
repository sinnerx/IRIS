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
Select Date
</div>
<?php echo flash::data();?>
<?php echo $siteEnabled; ?>
<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method='post' action='<?php echo url::base('billing/addTransaction/'.$itemSelect->billingItemID);?>'>
			<?php  if(session::get("userLevel") == 99): ?>	
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select($itemID);'",request::get("siteID"),"[SELECT SITE]");?>			
			</div>
			<?php endif;?>
			<div  class="form-group" style="margin-left:10px">
			From <?php echo form::text("selectDateStart","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateStart)));?>			
			</div>
			<div  class="form-group" style="margin-left:10px">
			To  <?php echo form::text("selectDateEnd","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateEnd)));?>			
			</div>
		</form>	
	</div>
</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
			Transaction Per Item
		</div>
		
		<table>
			
		</table>
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th width="15px">No.</th>
					<th width="100px">Date/Time</th>
					<th>Transaction Items</th>
					<th style="width: 100px;">Quantity</th>
					<th style="width: 100px;">Total (RM)</th>
					<th style="width: 100px;"></th>
				</tr>
				<?php if(count($groupedTransactions) === 0):?>
				<tr>
					<td colspan="5" style="text-align: center;">No transaction found.</td>
				</tr>
				<?php else:?>
				<?php foreach($groupedTransactions as $date => $transactions):?>
				<tr>
					<td style="background: #e4e9ef;"></td>
					<td style="background: #e4e9ef; font-size: 1.1em;" colspan="5"><?php echo date('d F Y', strtotime($date));?></td>
				</tr>
					<?php 
					foreach($transactions as $transaction):
						$biltransID = $transaction['billingTransactionID'];
					?>
					<tr>
						<td>#<?php echo $transactionID = $transaction['billingTransactionLocalID'];?></td>
						<td><?php echo date('g:i A', strtotime($transaction['billingTransactionDate']));?></td>
						<td colspan="3">
							<table width="100%" class='item-table'>
								<?php foreach($transactionItems[$transaction['billingTransactionID']] as $transactionItem):?>
								<tr>
									<td><?php echo $transactionItem['billingItemName'];?></td>
									<td></td>
									<td style="text-align: center;"><?php echo (float) $transactionItem['billingTransactionItemQuantity'];?></td>
									<td style="text-align: center;"><?php echo number_format($transactionItem['billingTransactionItemPrice'] * $transactionItem['billingTransactionItemQuantity'], 2, '.', '');?></td>

								</tr>
								<?php endforeach;?>
								<tr>
									<td><strong>Total</strong></td>
									<td></td>
									<td width="100px"></td>
									<td width="100px" style="text-align: center; font-weight: bold; font-size: 1.2em;">RM <?php echo number_format($transaction['billingTransactionTotal'], 2, '.', '');?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php endforeach;?>
				<?php endforeach;?>
				<?php endif;?>

			</table>
			
		</div>
	</div>
</div>	