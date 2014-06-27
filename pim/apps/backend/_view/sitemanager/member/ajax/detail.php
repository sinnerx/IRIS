<style type="text/css">
	.modal-dialog{
		width:800px;
	}
</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title">☮
			<span style='font-size:11px;'> Member profile detail.</span>
			<span style='margin-right:20px;' class="pull-right">Fullname : <?php echo $user['userProfileFullName']." ".$user['userProfileLastName']; ?></span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'><!-- 
			<div class='mb-header'>
				<a class='mb-tab active'>Incoming Activity</a> |
				<a href='<?php //echo url::base('ajax/activity/previous/1'); ?>' class='mb-tab'>Previous Activity</a>
			</div> -->
			<div class='mb-content'>
				<div class='ajxgal-new active'> <!-- through adding new photo -->
					<div class='row'>
								<div class="col-sm-2">
				                    <header class="panel-heading font-bold">
				                    	<img src="<?php echo model::load('image/services')->getPhotoUrl($user['userProfileAvatarPhoto']); ?>" width="125" height="150" />
				                    </header>
				                </div>
								<div class="col-sm-5">
				                    <div class="panel-body">
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Email address : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userEmail']){echo $user['userEmail'];}else{echo '-';} ?>
				                        </div>
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Mobile No : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userProfileMobileNo']){echo $user['userProfileMobileNo'];}else{echo '-';} ?>
				                        </div>
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Gender : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userProfileGender']){echo $user['userProfileGender'];}else{echo '-';} ?>
				                        </div>
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Date of Birth : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userProfileDOB']){echo $user['userProfileDOB'];}else{echo '-';} ?>
				                        </div>
				                    </div>
				                </div>
								<div class="col-sm-5">
				                    <div class="panel-body">
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Place of Birth : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userProfilePOB']){echo $user['userProfilePOB'];}else{echo '-';} ?>
				                        </div>
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Marital Status : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userProfileMarital']){echo $user['userProfileMarital'];}else{echo '-';} ?>
				                        </div>
				                        <div class="form-group">
				                          <label style="font-size:0.9em;">Address : </label>
				                          &nbsp;&nbsp;&nbsp;<?php if($user['userProfileMailingAddress']){echo $user['userProfileMailingAddress'];}else{echo '-';} ?>
				                        </div>
				                    </div>
				                </div>
					</div>
				</div>
				<div class='ajxgal-photos'> <!-- no album photo -->
				</div>
				<div class='ajxgal-albums'> <!-- By albums -->
				</div>
			</div>
		</div>
	</div>
</div>