<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<h3 class="m-b-xs text-black">
<script type="text/javascript">

	$(document).ready(function() {

/*	$('body').keyup(function(e)
	{
		var keybinds = 'abcdefghijklmnopqrstuvwxyz';
		var key = keybinds.split('')[e.keyCode-65];
		var hotkeybinds = {
			'a' : 1
		};

		billing.select(hotkeybinds[key]);
	});*/
		var result = 1.00;
	    $('#total').attr('value', function() {
	        $('.amount').each(function() {
	            if ($(this).val() != '') {
	                result *= parseFloat($(this).val());
	            } else {
	            	 result = 0.00;
	            }
	        });
	        return result;
	    });
	
	$('.amount').keyup(function() {
	  	var result = 1.00;
	    $('#total').attr('value', function() {
	        $('.amount').each(function() {
	            if ($(this).val() !== '') {
	                result *= parseFloat($(this).val());
	            }
	        });
	        return result;
	    });
	});

	if (getParameterByName('itemID') == '14'){
			$("#utilities").show();
			$("#desc").hide('fast');
		} else {
			$("#utilities").hide();
			$("#desc").show();
		}

	if (getParameterByName('itemID') == '15'){
			$("#transfer").show();
			$("#desc").hide('fast');
		} else {
			$("#transfer").hide();
			$("#desc").show();
		}

	

	jQuery("#selectDate").datetimepicker({
  		
  		format:'d-m-Y H:i',
  		step:5,
  		onChangeDateTime:function(dp,$input){
    		
    			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";  		
				var itemID	= itemID != ""?"&itemID="+getParameterByName('itemID'):"";
				var selectDate	= $input.val() != ""?"&selectDate="+$input.val():"";		
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/add?"+siteID+itemID+selectDate;		
  		}
	
	});

		function getParameterByName(name) {
    		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        	results = regex.exec(location.search);
    		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	});
	
	
	var base_url	= "<?php echo url::base();?>/";
	var x;
	var billing	= new function()
	{	
		this.select	= function(itemID)
		{						
			if (itemID == x){
				var itemID	=  itemID != ""?"&itemID="+$("#itemID").val():"";
			} else {
				var itemID	=  itemID != ""?"&itemID="+itemID:"";
			}

			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";			
			var selectDate	= $("#selectDate").val() != ""?"&selectDate="+$("#selectDate").val():"";		
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}
	   			
	   		window.location.href	= base_url+"billing/add?"+siteID+itemID+selectDate;		
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
<style>
	
	.billing-wrap .panel-default{
		text-align:center;
		cursor:pointer;
				  
	}
			 
	.hotkey{
		font-size:50px;
		color:#1aae88;
		font-weight: 700;
		-webkit-transition: all 0.3s ease-out;
   		-moz-transition: all 0.3s ease-out;
   		-ms-transition: all 0.3s ease-out;
   		-o-transition: all 0.3s ease-out;
   		transition: all 0.3s ease-out;
	}
			  
	.hotkey:hover{
		color:#177bbb;
	}
			  
	.itemname{
		height: 30px;
    	line-height: 14px;
    	margin-bottom: 15px;
    	margin-left: -20%;
    	width: 140%;
				  
	}
			  
	.price{
		border-radius: 5px;
    	padding-left: 10px;
    	padding-right: 10px;  
	}
	
	a.editItem{
		background: none repeat scroll 0 0 #e33244;
    	color: #fff;
    	font-size: 10px;
    	padding: 5px;
    	position: absolute;
    	right: 0;
    	top: 0;
    	width: 19px;
				  
			  }
</style>  


<h3 class='m-b-xs text-black'>
	Billing
</h3>
<div class='well well-sm'>
	Billing Input
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-6'>
		<div align="right">
		<?php  if(session::get("userLevel") == 99): ?>
		<a href='<?php echo url::base("billing/addItem/");?>'  class='fa fa-external-link' data-toggle='ajaxModal' style="color:green;"> Add New Item</a>			
		<?php endif; ?>
		</div>
		<div class="table-responsive bg-white billing-wrap">	
      		<?php foreach ($item as $key => $row):?>
      		<div class="col-sm-3 panel panel-default">
        		<?php  if(session::get("userLevel") == 99): ?>
      			<a href='<?php echo url::base("billing/editItem/".$row[billingItemID]);?>' data-toggle="ajaxModal" class="fa fa-fw fa-pencil pull-right editItem"></a>
        		<?php endif; ?>
      			<div onclick="billing.select(<?php echo $row[billingItemID]; ?>);" class="hotkey"><?php echo $row[billingItemHotkey]; ?></div>
      			<div onclick="billing.select(<?php echo $row[billingItemID]; ?>);" class="itemname "><?php echo $row[billingItemName]; ?></div>
      			<div onclick="billing.select(<?php echo $row[billingItemID]; ?>);" class="price btn-default">RM <?php echo $row[billingItemPrice]; ?></div>
        	</div>
        	<?php endforeach;?>		
		</div>
	</div>
	<div class='col-sm-6'>
		<form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/addTransaction/'.$itemSelect->billingItemID);?>'>
		<div style="margin-bottom:10px">
		<?php  if(session::get("userLevel") == 99): ?>
		<?php
         $itemID = $_GET['itemID'];
 		echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select($itemID);'",request::get("siteID"),"[SELECT SITE]");?>	
		<?php endif;?>
		</div>
	<section class="panel panel-default">
      <div class="panel-body">
          <div class="form-group">
            <label class="col-lg-2 control-label">Date</label>
            <div class="col-lg-5">
				<?php echo form::text("selectDate","class='input-sm input-s form-control'",date('d-m-Y H:i', strtotime($todayDate)));?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Hot Key</label>
            <div class="col-lg-5">
              <?php

       //       echo form::text("hotKey","class='form-control' readonly",$itemSelect->billingItemHotkey);
              echo form::select("itemID",$itemList,"class='input-sm form-control inline v-middle' tabindex='-1' onchange='billing.select(x);'",request::get("itemID"),"[SELECT TRANSACTION]");
              ?>

            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              <?php echo form::text("itemName","class='form-control' readonly",$itemSelect->billingItemName);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Description</label>
            <div id='desc' class="col-lg-10">
             <?php
              if ($itemSelect->billingItemDescriptionDisabled != 1){
              	echo form::text("description","class='form-control'  readonly",$itemSelect->billingItemDescription);
              } else {
				echo form::text("description","class='form-control' ",$itemSelect->billingItemDescription);              	
              }	?>
            
            </div>
            <div id='utilities' class="col-lg-10" style="display:none">
             <?php

             $utilitiesList = array(

             		 'Electricity'=>'Electricity',
             		 'Water'=>'Water',
             		 'Rental'=>'Rental'
             	);
             
            	echo form::select("utilitiesList",$utilitiesList,"class='input-sm form-control input-s inline v-middle' style='float:left'","[SELECT UTILITIES]");	
				echo form::text("description","class=' input-sm input-s form-control' ",$itemSelect->billingItemDescription);              	
              ?>
            
            </div>
            <div id='transfer' class="col-lg-10" style="display:none">
             <?php

             $transferList = array(

             		 'Premise Monthly'=>'Premise Monthly',
             		 'Purchase Requisition'=>'Purchase Requisition'
             	);
             
            	echo form::select("transferList",$transferList,"class='input-sm form-control input-s inline v-middle' style='float:left'","[SELECT]");	
			//	echo form::text("description","class=' input-sm input-s form-control' ",$itemSelect->billingItemDescription);              	
              ?>
            
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Price (RM)</label>
            <div class="col-lg-5">
              <?php
              if ($itemSelect->billingItemPriceDisabled != 1){
              	echo form::text("item","class='form-control amount'  readonly",$itemSelect->billingItemPrice);
              }  else {
				echo form::text("item","class='form-control amount' ",$itemSelect->billingItemPrice);
              } ?>              
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Unit / Hours</label>
            <div class="col-lg-5">            
              <?php
              if ($itemSelect->billingItemUnitDisabled != 1){
              	echo form::text("unit","class='form-control amount' readonly",$itemSelect->billingItemUnit);
              } else {
              	echo form::text("unit","class='form-control amount' ",$itemSelect->billingItemUnit);
              } ?>               
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Quantity</label>
            <div class="col-lg-5">            
              <?php               
              if ($itemSelect->billingItemQuantityDisabled != 1){
              echo form::text("quantity","class='form-control amount' readonly",$itemSelect->billingItemQuantity);
              } else {
              echo form::text("quantity","class='form-control amount'",$itemSelect->billingItemQuantity);
              } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Total</label>
            <div class="col-lg-5">            
              <?php               
              
              echo form::text("total","class='form-control' readonly");
              
               ?>
            </div>
          </div>         
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <button type="submit" class="btn btn-sm btn-default">Submit</button>
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
			Last 5 Transaction  
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
				<?php if(count($list) > 0):?>
			<?php 
			//	$totalBalance = $totalBalance + $previousBalance;

				foreach ($list as $key => $row):?>
				<tr>
					<td><?php echo $row[billingTransactionDate]; ?></td>
					<td><?php echo $row[billingItemName]; ?></td>
					<td><?php echo $row[billingTransactionDescription]; ?></td>
					<td><?php $itemPrice  = $row[billingTransactionTotal] / $row[billingTransactionQuantity] / $row[billingTransactionUnit];	
							  echo number_format($itemPrice, 2, '.', '');  ?></td>
					<td><?php echo $row[billingTransactionUnit]; ?></td>
					<td><?php echo $row[billingTransactionQuantity]; ?></td>
					<td><?php echo number_format($row[billingTransactionTotal], 2, '.', ''); ?></td>					
					<td><?php echo number_format($totalBalance, 2, '.', ''); 
							$totalBalance = $totalBalance - $row[billingTransactionTotal];
					?></td>
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
