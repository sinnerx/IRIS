<h3 class="m-b-xs text-black">
<a href='info'>Manager Takeover</a>
</h3>
<div class='well well-sm'>
Log into manager account
</div>
<?php echo flash::data();?>
<div class="table-responsive">
	<form method='post'>
	<table id='table-site-list' class="table table-striped b-t b-light">
		<tr>
			<th style="width: 200px;">Manager's Email</th>
			<td>: <?php echo form::text('userEmail', 'class="form-control" style="width: 250px; display: inline;"');?>
			<input type='submit' class='btn btn-primary' />
			</td>
		</tr>
	</table>
	</form>
</div>
