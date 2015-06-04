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
        		var siteID = "<?php echo $$siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/transactionJournal?"+siteID+selectDateStart+selectDateEnd;
		});

		$("#selectDateEnd").on("changeDate", function(ev)
		{

			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";  		
			var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
			var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $$siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/transactionJournal?"+siteID+selectDateStart+selectDateEnd;
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

	   		window.location.href	= base_url+"billing/transactionJournal?"+siteID+selectDateStart+selectDateEnd;
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
	Transaction
</h3>
<div class='well well-sm'>
	Transaction Journal 
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
			From <?php echo form::text("selectDateStart","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateStart)));?>			
			</div>
			<div  class="form-group" style="margin-left:10px">
			to  <?php echo form::text("selectDateEnd","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateEnd)));?>			
			</div>
		</form>	
	</div>
</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>			
		</div>
		
		<div class="table-responsive">
			<table class='table'>
				<tr>		
					<th>Date</th>
					<th>Item</th>			
					<th>Description</th>
					<th>Unit Price</th>
					<th>Unit</th>
					<th>Quantity</th>
					<th>Total</th>
					<th>Balance</th>
				</tr>
				
			<?php if($journal->count() > 0):?>
			<?php foreach($journal as $journalItem):?>
				<tr>
					<td><?php echo $journalItem->billingTransactionDate; ?></td>
					<td><?php echo $journalItem->billingItemName; ?></td>
					<td><?php echo $journalItem->billingItemDescription; ?></td>
					<td><?php echo $billingItemPrice = $journalItem->billingItemPrice ? : $journalItem->billingTransactionTotal / $journalItem->billingTransactionQuantity / $journalItem->billingTransactionUnit; ?></td>
					<td><?php echo $journalItem->billingTransactionUnit; ?></td>
					<td><?php echo $journalItem->billingTransactionQuantity; ?></td>
					<td><?php echo number_format($journalItem->billingTransactionTotal, 2, '.', ''); ?></td>
					<!-- <td><?php echo number_format($journalItem->billingTransactionBalance, 2, '.', ''); ?></td>	 -->

					<td><?php $beginningbalance = $beginningbalance + $journalItem->billingTransactionTotal;
					echo number_format($beginningbalance, 2, '.', '');?></td>


				</tr>
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

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
		
		</div>
		
	
	</div>
</div>	