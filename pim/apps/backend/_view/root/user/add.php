<h3 class="m-b-xs text-black">
<a href='info'>Add A New User</a>
</h3>
<div class='well well-sm'>
Cluster lead basically is a content editor for his assigned site. Any content edited/added would go through him first. Please complete the form below.
</div>
<?php echo flash::data();?>
<form method='post'>
<div class='row'>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='row'>
			<div class='col-sm-6'>
			<div class='form-group'>
			<label>1. Full Name</label>
			<?php echo form::text("userProfileFullName","class='form-control' placeholder=\"User's full name\"");?>
			<?php echo flash::data("userProfileFullName");?>
			</div>
			</div>
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>2. Level</label>
					<?php echo form::select("userLevel",$userLevelR,"class='form-control'",$userLevel);?>
					<?php echo flash::data("userLevel");?>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<label>2. Identification Number (IC)</label>
			<?php echo form::text("userIC","class='form-control' placeholder=\"Identification card number. Eg : 890910105117\"");?>
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
			<?php echo form::text("userEmail","class='form-control' placeholder='Email for login.'");?>
			<?php echo flash::data("userEmail");?>
		</div>
		<div class='form-group'>
			<?php $defaultPass	= model::load("user/services")->getDefaultPassword();?>
			<label>4. Password</label>
			<?php echo form::password("userPassword","class='form-control' placeholder='If you leave it empty, and we will set it to default $defaultPass.'");?>
			</div>
		</div>
		</div>
	</div>
</div>
<?php echo form::submit("Submit","class='btn btn-primary pull-right'");?>
</form>