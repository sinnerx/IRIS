<h3 class="m-b-xs text-black">
<a href='info'>Member Upgrade</a>
</h3>
<div class='well well-sm'>
Upgrade Calent member to manager, or clusterlead.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-6'>
		<section class='panel panel-default'>
		<p>Search by his IC first.</p>
		<?php if(request::get('ic') && !$row):?>
		<div class='alert alert-danger'>Member with this IC not found</div>
		<?php endif;?>
		<form>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<td>IC</td><td><input type='text' name='ic' class='form-control' value='<?php echo request::get('ic');?>' /></td>
				</tr>
				<?php if(isset($row) && $row):?>
				<input type='hidden' name='userID' value='<?php echo $row["userID"];?>' />
				<tr>
					<td>Name</td><td>: <?php echo $row['userProfileFullName'];?></td>
				</tr>
				<tr>
					<td>Email</td><td>: <?php echo $row['userEmail'];?></td>
				</tr>
				<tr>
					<td>Upgrade to</td><td>: <?php echo form::select('userLevel', array(
						2 => 'Site Manager',
						3 => 'Clusterlead'
					), 'class="form-control" style="width: 50%; display: inline;"');?>

					<input type='submit' class='btn btn-success' value='Upgrade'  />
					</td>
				</tr>
				<?php endif;?>
			</table>
			<div><input type='submit' class='btn btn-primary' value='Search' /></div>
		</div>
		</section>
		</form>
	</div>
</div>