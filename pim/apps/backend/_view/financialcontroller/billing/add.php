<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

	$(document).ready(function() {
		jQuery("#selectDate").datetimepicker({
  								timepicker:false,
  								format:'Y-m-d',
  								step:5,
		});


$('#selectDate').change(function() {
		$('#description').attr('value', function() {

			if ($('#transactionType').val() == 1){
				type = 'Monthly Revenue';
			} else {
				type = 'Cash Out';
			}

	        result = type + ' on ' + $('#selectDate').val();
	        return result;
	    });

	});


$('#transactionType').change(function() {
		$('#description').attr('value', function() {

			if ($('#transactionType').val() == 1){
				type = 'Monthly Revenue';
			} else {
				type = 'Cash Out';
			}

	        result = type + ' on ' + $('#selectDate').val();
	        return result;
	    });

	});


});

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
	Billing
</h3>
<div class='well well-sm'>
	HQ Billing Input
</div>
<?php echo flash::data();?>
<div class='row'>

	<div class='col-sm-6'>
		<form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/addTransaction/');?>'>
		<div style="margin-bottom:10px">
		
		</div>

	<section class="panel panel-default">
      <div class="panel-body">
		<div class="form-group">
            <label class="col-lg-2 control-label">Site</label>
            <div class="col-lg-5">
              <?php
        		echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle'",request::get("siteID"),"[SELECT SITE]");
              ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Type</label>
            <div class="col-lg-10">
              <?php 
              echo form::select("transactionType",$typeList,"class='input-sm form-control input-s-sm inline v-middle'",null,"[SELECT TYPE]");	
              	?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Transaction Date</label>
            <div class="col-lg-5">
				<?php echo form::text("selectDate","class='input-sm input-s form-control'",date('Y-m-d', strtotime($selectDate)));?>
            </div>
        </div>
                
        <div class="form-group">
            <label class="col-lg-2 control-label">Description</label>
            <div id='desc' class="col-lg-10">
             <?php              
				echo form::text("description","class='input-sm form-control input-s-sm inline v-middle' ");              	
              ?>       
            </div>        
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Amount (RM)</label>
            <div class="col-lg-5">
              <?php            
				echo form::text("total","class='form-control amount' ");
               ?>              
            </div>
        </div>
          
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <button type="submit" class="btn btn-sm btn-default">Insert</button>
                         
            </div>
        </div>
        </form>
      </div>
    </section>
	</div>
</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
			Transaction  
		</div>
		
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th></th>
					<th>Date</th>
					<th>Transaction Date</th>
					<th>Site</th>			
					<th>Type</th>
					<th>Description</th>					
					<th>Amount</th>
					<!-- <th>Payment</th> -->					
				</tr>
				<?php if(count($list) > 0):?>
			<?php	foreach ($list as $key => $row):?>
				<tr>
					<td>
					<a href='<?php echo url::base("billing/editTransaction/".$row[billingTransactionID]);?>' style="margin-left:20px"  data-toggle='ajaxModal' class='fa fa-edit pull-right' style='font-size:13px;'></a>
					<a id='delete-button' onclick='return confirm("Delete this transaction, are you sure?");' href='<?php echo url::base('billing/delete/'.$row[billingTransactionID]); ?>' class='fa fa-trash-o pull-right' style='font-size:13px;'></a>				
					</td>
					<td><?php echo date('Y-m-d', strtotime($row[billingTransactionCreatedDate])); ?></td>
					<td><?php echo date('Y-m-d', strtotime($row[billingTransactionDate])); ?></td>
					<td><?php echo $row[siteName]; ?></td>
					<?php if ($row[billingFinanceTransactionType] == 1){
						$type = "Monthly Revenue"; } else { $type = "Cash Out"; } ?> 
					<td><?php echo $type; ?></td>
					<td><?php echo $row[billingTransactionDescription];  ?></td>
					<td><?php echo abs($row[billingTransactionTotal]); ?></td>
					<!-- <td><?php echo $row[billingTransactionQuantity]; ?></td> -->			
				</tr>
			<?php endforeach;?>
			<?php else:?>		
				<tr>
					<td colspan="6"> No Transaction</td>
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
