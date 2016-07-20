<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<h3 class="m-b-xs text-black">
	Edit transaction #<?php echo $transaction[0]['billingTransactionLocalID'];?>
	<a onclick='return confirm("Delete this transaction record. Are you sure? This action is irreversible.");' href='<?php echo "";?>' class='btn btn-danger pull-right'>Delete Transaction</a>
</h3>
<div class='well well-sm'>
	Edit transaction record. For now, only date is editable.
	<?php  var_dump($transaction[0]); ?>
</div>
<form method='post'>
<table class='table'>
	<tr>
		<td>Date</td>
		<td><input type='text' id='input-datetimepicker' name='transactionDate' value='<?php echo $transaction[0]["billingTransactionDate"];?>' /></td>
	</tr>
	<tr>
		<td>Quantity</td>
		<td><input type='text' id='input-datetimepicker' name='transactionDate' value='<?php echo $transaction[0]["billingTransactionDate"];?>' /></td>
	</tr>
	<tr>
		<td>Total(RM)</td>
		<td><input type='text' id='input-datetimepicker' name='transactionDate' value='<?php echo $transaction[0]["billingTransactionDate"];?>' /></td>
	</tr>		
	<tr>
		<td></td>
		<td><input type='submit' class='btn btn-primary' value='Update transaction date' /> </td>
	</tr>
</table>
</form>
<script type="text/javascript">
	
$("#input-datetimepicker").datetimepicker({
  format: 'Y-m-d H:i:s',
  onChangeDateTime:function(dp,$input){
    if(new Date($input.val()) > new Date())
    {
      alert('Please select only previous date.');
      $input.val('');
      return false;
    }
  }
});
</script>