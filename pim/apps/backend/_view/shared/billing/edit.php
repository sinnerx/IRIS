<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<script type="text/javascript">

	$(document).ready(function() {

	$("#selectDate").on("changeDate", function(ev)
	{

		var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";  		
		var itemID	= itemID != ""?"&itemID="+getParameterByName('itemID'):"";
		var selectDate	= $("#selectDate").val() != ""?"&selectDate="+$("#selectDate").val():"";		
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $$siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/edit?"+siteID+itemID+selectDate;			    
		});

		function getParameterByName(name) {
    		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        	results = regex.exec(location.search);
    		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	});
	
	
	var base_url	= "<?php echo url::base();?>/";
	
	var billing	= new function()
	{	
		this.select	= function(itemID)
		{		
				
			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";
			var itemID	= $("#itemID").val() != ""?"&itemID="+$("#itemID").val():"";
			var selectDate	= $("#selectDate").val() != ""?"&selectDate="+$("#selectDate").val():"";		
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/edit?"+siteID+itemID+selectDate;		

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
	Cashier Edit
</h3>
<div class='well well-sm'>
	Search Transaction to Edit
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method='post' action='<?php echo url::base('billing/addTransaction/'.$itemSelect->billingItemID);?>'>
			<?php  if(session::get("userLevel") == 99): ?>
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select($itemID);'",request::get("siteID"),"[SELECT SITE]");?>			
			</div>
			<?php endif; ?>
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::text("selectDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDate)));?>			
			</div>
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::select("itemID",$itemList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select($itemID);'",request::get("itemID"),"[SELECT TRANSACTION]");?>			
			</div>
		</form>	
	</div>
</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
			Daily Transaction  
		</div>
		
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th></th>
					<th>Date</th>
					<th>Item</th>			
					<th>Description</th>
					<th>Unit Price</th>
					<th>Unit</th>
					<th>Quantity</th>
					<th>Total</th>
				</tr>
				<?php if(count($list) > 0):?>
			<?php foreach ($list as $key => $row):?>

				<?php
					 	$billingTransactionTotal = $row[billingTransactionTotal];
				?>

				<tr>
					
					<?php  if((session::get("userLevel") == 2) && ($verified != 1)): ?>					
					<td>
					<a href='<?php echo url::base("billing/editForm/".$row[billingItemID]."/".$row[billingTransactionID]);?>' style="margin-left:20px"  data-toggle='ajaxModal' class='fa fa-edit pull-right' style='font-size:13px;'></a>
					<a id='delete-button' href='javascript:void(0)' rel=<?php echo $row[billingTransactionID]; ?> class='fa fa-trash-o pull-right' style='font-size:13px;'></a></a>
					</td>
					<?php else: ?> 
					<td></td>	
					<?php endif; ?>
					<td><?php echo $row[billingTransactionDate]; ?></td>
					<td><?php echo $row[billingItemName]; ?></td>
					<td><?php echo $row[billingItemDescription]; ?></td>
					<td><?php echo $billingItemPrice = $row[billingItemPrice] ? :  $row[billingTransactionTotal] / $row[billingTransactionQuantity] / $row[billingTransactionUnit]; ?></td>
					<td><?php echo $row[billingTransactionUnit]; ?></td>
					<td><?php echo $row[billingTransactionQuantity]; ?></td>
					<td><?php echo number_format($billingTransactionTotal, 2, '.', ''); ?></td>
				</tr>
			<?php 
					$todayTotal = $todayTotal + $billingTransactionTotal;
					$todayBalance = $row[billingTransactionBalance];

			endforeach;?>
			<?php else:?>		
				<tr>
					<td colspan="8"> No Transaction</td>
				</tr>
				<?php endif; ?>	

			</table>
			
		</div>
	</div>

	<div class="col-sm-10  ">
		<div class='well well-sm'>
			
		</div>
<?php  


 	$settlementdate = request::get("selectDate");


if(session::get("userLevel") == 2): ?>	
	<form class="form-inline bs-example" method='post' action='<?php echo url::base('billing/settlement/'.$settlementdate);?>'>	
		<div class="table-responsive">
			<table class='table ' border='1'>
				<tr>	
					<th></th> 
					<th>Site Manager</th>
				</tr>		
				<tr>	
					<td>Today Total</td> 
					<td><?php echo number_format($todayTotal, 2, '.', ''); ?></td>			
				</tr>	
				<tr>	
					<td>Status</td> 
					<td><?php if ($verified == 1) { ?>
					Verified
					<?php } else { ?>
					<button type="submit" class="btn btn-sm btn-default">Verify</button>
					<?php } ?>
					</td>

				</tr>				
			</table>
		</div>
	</form>	
	<?php endif; ?>
	</div>

</div>	
<script type="text/javascript">


var base_url	= "<?php echo url::base();?>/";

		$("#delete-button").click(function(){
    if(confirm("Are you sure you want to delete this?")){
        
        var transactionId	= $(this).attr("rel") != ""?"&transactionId="+$(this).attr("rel"):""; 

        window.location.href	= base_url+"billing/delete?"+transactionId;	
    }
    else{
        return false;
    }
});
</script>