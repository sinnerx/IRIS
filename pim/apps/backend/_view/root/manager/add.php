<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css");?>" type="text/css">
<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>
<h3 class='m-b-xs text-black'>
	Add Manager
</h3>
<div class='well well-sm'>
	Register a new manager!
</div>
<?php echo flash::data();?>
<form method='post'>
<div class='row'>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>1. Manager Name</label>
			<?php echo form::text("userProfileFullName","class='form-control' placeholder=\"Manager's full name\"");?>
			<?php echo flash::data("userProfileFullName");?>
		</div>
		<div class='form-group'>
			<label>2. Manager IC.</label>
			<?php echo form::text("userIC","class='form-control' placeholder=\"His identification card number. Eg : 890910105117\"");?>
			<?php echo flash::data("userIC");?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>3. Manager's Email Address</label>
			<?php echo form::text("userEmail","class='form-control' placeholder='Email for login and for assigning to site.'");?>
			<?php echo flash::data("userEmail");?>
		</div>
		<div class='form-group'>
			<label>4. Manager Password</label>
			<?php echo form::password("userPassword","class='form-control' placeholder='If you leave it empty, and we will set it to default 12345.'");?>
			</div>
		</div>
		</div>
	</div>
</div>
<div class='well well-sm'>
	Below is optional : Manager can fill em themselves if you don't want.
</div>
<div class='row'>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>5. Title</label>
			<?php echo form::select("userProfileTitle",Array(1=>"Mr",2=>"Mrs"),"class='form-control'");?>
		</div>
		<div class='form-group'>
			<label>6. Gender</label>
			<?php echo form::select("userProfileGender",Array(1=>"Male",2=>"Female"),"class='form-control'");?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>7. Date of birth</label>
			<?php echo form::text("userProfileDOB","data-date-format='yyyy-mm-dd' class='input-sm input-s datepicker-input form-control' placeholder='Birth date.'");?>
		</div>
		<div class='form-group'>
			<label>8. Place of birth</label>
			<?php echo form::text("userProfilePOB","class='form-control' placeholder='Place of birth'");?>
			</div>
		</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>9. Marital</label>
			<?php echo form::select("userProfileMarital",Array(1=>"Single",2=>"Married",3=>"Widow"),"class='form-control'");?>
		</div>
		<div class='form-group'>
			<label>10. Phone No.</label>
			<?php echo form::text("userProfilePhoneNo","class='form-control' placeholder='Manager phone no. Eg. 012-6966121'");?>
			</div>
		</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>11. Manager address</label>
			<?php echo form::text("userProfileMailingAddress","class='form-control' placeholder='Address'");?>
		</div>
		</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='form-group' style='text-align:center;'>
		<?php echo form::submit("Submit","class='btn btn-primary'");?>
		</div>
	</div>
</div>
</form>