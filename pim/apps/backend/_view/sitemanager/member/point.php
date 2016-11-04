<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<h3 class='m-b-xs text-black'>Transaction List</h3>
<div class='well well-sm'>
List of transactions with point.
<?php //var_dump($transactions); ?>
</div>
<script type="text/javascript">
		function confirmation(){
			var r = confirm("Are you sure this member has paid?");
    		if (r == true) {
        		return true;
    		} else {
        		return false;
    		}
		}
</script>
<?php echo flash::data();?>
<section class='panel panel-default'>
	<div class='panel-heading'>
		<div class="row">
			<div class='col-sm-10'>
				<form method="get" id="formSearch" class="form-inline bs-example"><!-- search form -->
				<div class="form-group" style="margin-left:10px">
				Billing Item : <?php echo form::select("billingItemType",$itemList,"class='input-sm form-control input-s-sm inline v-middle'",request::get("billingItemType"),"[SELECT ITEM] ");?>

				</div>
				<div  class="form-group" style="margin-left:10px">
				From <?php echo form::text("selectDateStart","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateStart)));?>			
				</div>
				<div  class="form-group" style="margin-left:10px">
				to  <?php echo form::text("selectDateEnd","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($todayDateEnd)));?>			
				</div>
				<button class="btn btn-sm btn-default" type="button" onclick="$('#formSearch').submit();">Go!</button>
				</form>
			</div>
		</div>
	</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='15px'>No.</th>
				<th width="300px">Item Name</th>
				<th width="200px">Date</th>
				<th>Point</th>
			</tr>
			<?php if(!$transactions):?>
			<tr>
				<td style="text-align:center;" colspan="3">No transaction was found.</td>
			</tr>
			<?php else:?>
				<?php
				$no	= pagination::recordNo();
				foreach($transactions as $row)
				{
					
					$itemName 	= $row['billingItemName'];
					$date 		= $row['billingTransactionDate'];
					$point		= $row['billingTransactionItemPoint'];
					$opacity	= "style='opacity:0.5";

					
				?>
				<tr>
					<td><?php echo $no++;?></td>
					<td><?php echo $itemName;?></td>
					<td><?php echo $date;?></td>
					<td><?php echo $point;?></td>
				</tr>
				<?php

				}
				?>
			<?php endif;?>
		</table>
	</div>
	<div class='panel-footer'>
	<div class='row'>
		<div class="col-sm-12">
		<?php echo pagination::link();?>
		</div>
	</div>
	</div>
</section>