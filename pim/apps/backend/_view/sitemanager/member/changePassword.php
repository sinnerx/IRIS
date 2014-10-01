<style type="text/css">
	
.form-control
{
	width:90%;
	display: inline;
}

</style>
<h3 class='m-b-xs text-black'>Member List</h3>
<div class='well well-sm'>
You can manually change member password here.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-6'>
	<form method="post">
		<div class='panel panel-default'>
			<div class='panel-body'>
				<table class='table'>
					<tr>
						<td>I.C.</td><td>: <?php echo form::text("userIC","class='form-control'");?> <?php echo flash::data("userIC");?></td>
					</tr>
					<tr>
						<td>New Password</td><td>: <?php echo form::text("userPassword","class='form-control'");?> <?php echo flash::data("userPassword");?></td>
					</tr>
					<tr>
						<td>Confirm Password</td><td>: <?php echo form::text("userPassword_confirm","class='form-control'");?> <?php echo flash::data("userPassword_confirm");?></td>
					</tr>
				</table>
			</div>
			<div class='panel-footer'>
				<input type='submit' class='form-control btn btn-primary' />
			</div>
		</div>
	</form>
	</div>
</div>