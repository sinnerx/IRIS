<link href="<?php echo url::asset("skmm/css/style.css?v20160106");?>" rel="stylesheet" type="text/css">
<style type="text/css">
.pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
}
.pagination > li {
  display: inline;
}
.pagination > li > a,
.pagination > li > span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #428bca;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
}


.pagination > li:first-child > a,
.pagination > li:first-child > span {
  margin-left: 0;

}

.pagination > li:last-child > a,
.pagination > li:last-child > span {

}
.pagination > li > a:hover,
.pagination > li > span:hover,
.pagination > li > a:focus,
.pagination > li > span:focus {
  color: #2a6496;
  background-color: #eee;
  border-color: #ddd;
}
.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus {
  z-index: 2;
  color: #fff;
  cursor: default;
  background-color: #428bca;
  border-color: #428bca;
}
.pagination > .disabled > span,
.pagination > .disabled > span:hover,
.pagination > .disabled > span:focus,
.pagination > .disabled > a,
.pagination > .disabled > a:hover,
.pagination > .disabled > a:focus {
  color: #999;
  cursor: not-allowed;
  background-color: #fff;
  border-color: #ddd;
}


.pagination-lg > li > a,
.pagination-lg > li > span {
  padding: 3px 16px;
  font-size: 12px;
}
.pagination-lg > li:first-child > a,
.pagination-lg > li:first-child > span {

}
.pagination-lg > li:last-child > a,
.pagination-lg > li:last-child > span {

}
.pagination-sm > li > a,
.pagination-sm > li > span {
  padding: 5px 10px;
  font-size: 12px;
}


.pagination-sm > li:first-child > a,
.pagination-sm > li:first-child > span {

}


.pagination-sm > li:last-child > a,
.pagination-sm > li:last-child > span {

}
</style>
<?php //var_dump($billingTransactionItems); ?>
<!-- <h3 class="m-b-xs text-black">
<a href='info'>List of Transactions</a>
</h3> -->
<div class='well well-sm'>
<p>
List of all transaction.
</p>
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