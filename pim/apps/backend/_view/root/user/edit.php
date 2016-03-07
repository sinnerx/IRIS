<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css");?>" type="text/css">
<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/qrcode/jquery.qrcode-0.12.0.min.js");?>"></script>
<script>
$(document).ready(function(){
	$("#ic-qr").qrcode({
    "size": 100,
    "color": "#3a3",
    "text": "<?php echo $row['userIC']; ?>"
});
});
</script>
<h3 class="m-b-xs text-black">
<a href='info'>Edit User</a>
</h3>
<div class='well well-sm'>
Edit existing user information.
</div>
<?php echo flash::data();?>
<form method='post'>
<div class='row'>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<div class='row'>
			<div class='col-sm-6'>
			<label>1. User Full Name</label>
			<?php echo form::text("userProfileFullName","class='form-control' placeholder=\"User's full name\"",$row['userProfileFullName']);?>
			<?php echo flash::data("userProfileFullName");?>
			</div>

			<div class='col-sm-6'>
			<label>2. User Level</label>
			<?php echo form::select("userLevel",$userLevelR,"disabled class='form-control'",$row['userLevel']);?>
			</div>
			</div>
		</div>
		<div class='form-group'>
			<label>2. User IC.</label>
			<?php echo form::text("userIC","class='form-control' placeholder=\"His identification card number. Eg : 890910105117\"",$row['userIC']);?>
			<?php echo flash::data("userIC");?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>3. Email Address</label>
			<?php echo form::text("userEmail","class='form-control' placeholder='Email for login.'",$row['userEmail']);?>
			<?php echo flash::data("userEmail");?>
		</div>
		<div class='form-group'>
			<label>4. Password</label>
			<?php echo form::password("userPassword","class='form-control'");?>
			</div>
		</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>5. Title</label>
			<?php echo form::select("userProfileTitle",Array(1=>"Mr",2=>"Mrs"),"class='form-control'",$row['userProfileTitle']);?>
		</div>
		<div class='form-group'>
			<label>6. Gender</label>
			<?php echo form::select("userProfileGender",Array(1=>"Male",2=>"Female"),"class='form-control'",$row['userProfileGender']);?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>7. Date of birth</label>
			<?php echo form::text("userProfileDOB","data-date-format='yyyy-mm-dd' class='input-sm input-s datepicker-input form-control' placeholder='Birth date.'",$row['userProfileDOB']);?>
		</div>
		<div class='form-group'>
			<label>8. Place of birth</label>
			<?php echo form::text("userProfilePOB","class='form-control' placeholder='Place of birth'",$row['userProfilePOB']);?>
			</div>
		</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>9. Marital</label>
			<?php echo form::select("userProfileMarital",Array(1=>"Single",2=>"Married",3=>"Widow"),"class='form-control'",$row['userProfileMarital']);?>
		</div>
		<div class='form-group'>
			<label>10. Phone No.</label>
			<?php echo form::text("userProfilePhoneNo","class='form-control' placeholder='Manager phone no. Eg. 012-6966121'",$row['userProfilePhoneNo']);?>
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
			<?php echo form::text("userProfileMailingAddress","class='form-control' placeholder='Address'",$row['userProfileMailingAddress']);?>
		</div>
		</div>
		</div>
	</div>
</div>
<!-- <div class='row'>
	<div class='col-sm-12'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<label>12. Member QR Code</label><p>	
		<div class='form-group'  id="ic-qr">
				
		
		</div>
		</div>
		</div>		
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>

		<div class='form-group' style='text-align:center;'>
			<?php //echo form::submit("Submit","class='btn btn-primary'");?>
		</div>

	</div>
</div> -->
</form>