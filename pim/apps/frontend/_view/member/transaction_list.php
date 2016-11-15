<link href="<?php echo url::asset("skmm/css/style.css?v20160106");?>" rel="stylesheet" type="text/css">
<?php //var_dump($billingTransactionItems); ?>
<h3 class="m-b-xs text-black">
<a href='info'>List of Transactions</a>
</h3>
<div class='well well-sm'>
List of all transaction.
</div>
<?php //echo flash::data();?>
<div class=''>
	<div class='col-sm-12' id='news2'>
	<section class="panel panel-default">
	<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
		<div class="col-sm-3 pull-right">
		</div>
	</div>
	<div class="table-responsive">
		<table width="100%">
		<thead>
			<tr>
				<th width="20">No.</th>
				<th>Item</th>
				<th>Date</th>
				<th>Point</th>
			</tr>
		</thead>
		<tbody>
		<?php

		if(count($billingTransactionItems) > 0):
			$no	= pagination::recordNo();
			foreach($billingTransactionItems as $item):
				// var_dump($item);
			// die;
				$name 	= $item['billingItemName'];
				$date	= $item['billingTransactionDate'];
				$point  = $item['billingTransactionItemPoint'];

				echo "<tr><td>$no.</td><td>$name</td><td>$date</td><td>$point</td></tr>";
				$no++;
			endforeach;
		else:?>
		<tr>
			<td align="center" colspan="5">
			</td>
		</tr>
		<?php
		endif;
		?>
		</tbody>
		</table>
	</div>
	<footer class='panel-footer'>
	<div class='row'>
		<div class='col-sm-12'>
			<?php echo pagination::link();?>
		</div>
	</div>
	</footer>
	</section>
	</div>
</div>