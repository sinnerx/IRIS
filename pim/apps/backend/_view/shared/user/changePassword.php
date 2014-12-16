<h3 class="m-b-xs text-black">
Change Password
</h3>
<div class='well well-sm'>
You can change your password here.
</div>
<?php echo flash::data();?>
<div class='row'>
<div class='col-sm-5'>
	<form method='post' action="<?php echo url::base("user/changePassword");?>">
	<section class="panel panel-default">
		<?php
		?>
		<div class="panel-body">
			<label>1. New Password</label>
			<?php echo form::password("userPassword","class='form-control' placeholder='Password'");?>
			<?php echo flash::data("userPassword");?>
		</div>
		<div class="panel-body">
			<label>2. Confirm Password</label>
			<?php echo form::password("passwordConfirm","class='form-control' placeholder='Please write your password again'");?>
			<?php echo flash::data("passwordConfirm");?>
		</div>
		<div class='row'>
		<div class='col-sm-12'>
			<?php echo form::submit("Change","class='btn btn-primary pull-right'");?>
			</div>
		</div>
	</section>
	</form>
</div>
</div>