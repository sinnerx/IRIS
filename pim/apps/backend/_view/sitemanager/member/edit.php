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
			<label>1. Full Name</label>
			<?php echo form::text("userProfileFullName","class='form-control' placeholder=\"Full name\"",$row['userProfileFullName']);?>
			<?php echo flash::data("userProfileFullName");?>
			</div>

			<div class='col-sm-6'>
			<label>Father's Name</label>
			<?php echo form::text("userProfileLastName","class='form-control' placeholder=\"Father's name\"",$row['userProfileLastName']);?>
			<?php echo flash::data("userProfileLastName");?>
			</div>
			</div>

			<!--<div class='row'>
			<div class='col-sm-6'>-->
			<!--<label>1. User Full Name</label>
			<?php echo form::text("userProfileFullName","class='form-control' placeholder=\"User's full name\"",$row['userProfileFullName']);?>
			<?php echo flash::data("userProfileFullName");?>-->
			<!--</div>

			<div class='col-sm-6'>
			<label>2. User Level</label>
			<?php echo form::select("userLevel",$userLevelR,"disabled class='form-control'",$row['userLevel']);?>
			</div>
			</div>-->
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
			<!--<?php echo form::text("userEmail","class='form-control' placeholder='Email for login.'",$row['userEmail']);?>
			<?php echo flash::data("userEmail");?>-->
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
		<div class='form-group'>
			<label>7. Marital Status</label>
			<?php echo form::select("userProfileMarital",Array(1=>"Single",2=>"Married",3=>"Widow"),"class='form-control'",$row['userProfileMarital']);?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>8. Date of birth</label>
			<?php echo form::text("userProfileDOB","data-date-format='yyyy-mm-dd' class='input-sm input-s datepicker-input form-control' placeholder='Birth date.'",$row['userProfileDOB']);?>
		</div>
		<div class='form-group'>
			<label>9. Place of birth</label>
			<?php echo form::text("userProfilePOB","class='form-control' placeholder='Place of birth'",$row['userProfilePOB']);?>
		</div>
		<div class='form-group'>
			<label>10. Mobile Phone No.</label>
			<?php echo form::text("userProfileMobileNo","class='form-control' placeholder='Phone no. Eg. 012-6966121'",$row['userProfileMobileNo']);?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>11. Home Phone No.</label>
			<?php echo form::text("userProfilePhoneNo","class='form-control' placeholder='Phone no. Eg. 03-12345678'",$row['userProfilePhoneNo']);?>
			</div>
		</div>
		<div class='form-group'>
			<label>12. Home Address</label>
			<!--<?php echo form::text("userProfileMobileNo","class='form-control' placeholder='Phone no. Eg. 012-6966121'",$row['userProfileMobileNo']);?>-->
			<?php echo form::textarea("userProfileMailingAddress","style='height:100px;' class='form-control'",$row['userProfileMailingAddress']);?>
		</div>
		</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>Additional</label>
		</div>
		<div class='form-group'>
			<label>1. Career Group</label>
			<?php echo form::select("userProfileOccupationGroup",model::load("helper")->occupationGroup(),"class='form-control'",$add['userProfileOccupationGroup']);?>
		</div>
		<div class='form-group'>
			<label>2. Job</label>
			<?php echo form::text("userProfileOccupation","class='form-control' placeholder=\"Job\"",$add['userProfileOccupation']);?>
		</div>
		<div class='form-group'>
			<label>3. Introduction</label>
			<?php echo form::textarea("userProfileIntroductional","style='height:195px;' class='form-control'",$add['userProfileIntroductional']);?>
		</div>
		</div>
		</div>
	</div>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>Social Media</label>
		</div>
		<div class='form-group'>
			<label>1. Facebook</label>
			<?php echo form::text("userProfileFacebook","class='form-control' placeholder='Facebook URL.'",$add['userProfileFacebook']);?>
		</div>
		<div class='form-group'>
			<label>2. Twitter</label>
			<?php echo form::text("userProfileTwitter","class='form-control' placeholder='Twitter URL.'",$add['userProfileTwitter']);?>
		</div>
		<div class='form-group'>
			<label>3. Web</label>
			<?php echo form::text("userProfileWeb","class='form-control' placeholder='Website URL.'",$add['userProfileWeb']);?>
		</div>
		<div class='form-group'>
			<label>4. E-Commerce</label>
			<?php echo form::text("userProfileEcommerce","class='form-control' placeholder='E-commerce site URL.'",$add['userProfileEcommerce']);?>
		</div>
		<div class='form-group'>
			<label>5. Youtube</label>
			<?php echo form::text("userProfileVideo","class='form-control' placeholder='Youtube video URL.'",$add['userProfileVideo']);?>
		</div>
		</div>
		</div>
	</div>
</div>

<!--<div class='row'>
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
</div>-->
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
-->
<div class='row'>
	<div class='col-sm-12'>

		<div class='form-group' style='text-align:center;'>
			<?php echo form::submit("Submit","class='btn btn-primary'");?>
		</div>

	</div>
</div> 
</form>