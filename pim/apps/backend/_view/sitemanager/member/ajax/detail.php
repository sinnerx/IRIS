<style type="text/css">
	.modal-dialog{
		width:800px;
	}

	#userdetail-info-main th, #userdetail-info-main td, #userdetail-info-additional th, #userdetail-info-additional td
	{
		width: 25%;
	}

	.mb-content table tr:first-child td, .mb-content table tr:first-child th
	{
		border-top:0px;
	}

	.userdetail-subtitle
	{
		padding:5px 5px 5px 5px;
		font-size:1.1em;
		text-decoration: underline;
		font-weight: bold;
	}

	#userdetail-info-main
	{
		margin-bottom: 20px;
	}

	#introductional-container img
	{
		max-width: 100%;
	}


</style>
<script type="text/javascript">

//in-case it's loaded somewhere.
function confirmation(){
			var r = confirm("Are you sure this member has paid?");
    		if (r == true) {
        		return true;
    		} else {
        		return false;
    		}
		}

</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span><span class='fa fa-user'></span> Member Information</span>
			<span>: <?php echo ucwords($user['userProfileFullName']." ".$user['userProfileLastName']); ?></span>
			<?php if($user['siteMemberStatus'] != 1):?>
			<?php if(authData('site.siteID') == $user['siteID']):?>
			<a href='?toggle=<?php echo $user['userID'];?>' onclick='return confirmation();' class='label label-primary pull-right' style='position:relative;right:10px;' >Approve Membership</a><?php endif;?>
			<?php endif;?>
			</h4>
		</div>
		<?php
		if($user['siteMemberStatus'] != 1):
		?>
			<div class='alert alert-danger' style="margin-bottom:0px;">
			This member is yet to make any payment.
			</div>
		<?php endif;?>
		<div class="modal-body" style='padding-top:5px;'><!-- 
			<div class='mb-header'>
				<a class='mb-tab active'>Incoming Activity</a> |
				<a href='<?php //echo url::base('ajax/activity/previous/1'); ?>' class='mb-tab'>Previous Activity</a>
			</div> -->
			<div class='mb-content'>
				<div class='ajxgal-new active'> <!-- through adding new photo -->
					<div class='userdetail-subtitle'>
					Main Information
					</div>
					<div class='row' id='userdetail-info-main'>
						<div class="col-sm-10">
							<div class='table-responsive'>
							<table class='table'>
								<tr>
									<th>I.C.</th>
									<td><?php echo $user['userIC']?:"-";?></td>
									<th>Email address</th>
									<td><?php echo $user['userEmail']?:"-";?></td>
								</tr>
								<tr>
									<th>Mobile No</th>
									<td><?php echo $user['userProfileMobileNo']?:"-";?></td>
									<th>Phone (home)</th>
									<td><?php echo $user['userProfilePhoneNo']?:"-";?></td>
								</tr>
								<tr>
									<th>Gender</th>
									<td><?php
									$genderR	= Array(1=>"Male",2=>"Female");
									echo $user['userProfileGender']?$genderR[$user['userProfileGender']]:"-";?></td>
									<th>Marital Status</th>
									<td><?php
									$maritalR	= Array(1=>"Single",2=>"Married",3=>"Divorced");
									echo $user['userProfileMarital']?$maritalR[$user['userProfileMarital']]:"-";?></td>
								</tr>
								<tr>
									<th>Date of Birth</th>
									<td><?php
									$age	= date("Y")-date("Y",strtotime($user['userProfileDOB']));
									echo $user['userProfileDOB']?$user['userProfileDOB']." ($age y.o.)":"-";?></td>
									<th>Place of Birth</th>
									<td><?php echo $user['userProfilePOB']?:"-";?></td>
								</tr>
								<tr>
									<th>Address</th>
									<td colspan="3"><?php echo $user['userProfileMailingAddress']?:"-";?></td>
								</tr>
							</table>
							</div>
		                </div>
		                <div class="col-sm-2">
	                    	<img style="width:100%;height:auto;box-shadow:0px 0px 5px #616161;" src="<?php
	                    	$user['userProfileAvatarPhoto'] = $user['userProfileAvatarPhoto']?:"../upload_photo.png";
	                    	echo model::load('image/services')->getPhotoUrl($user['userProfileAvatarPhoto']); ?>" width="125" height="150" />
		                </div>
					</div>
					<div class='userdetail-subtitle'>
					Additional Information
					</div>
					<div class='row' id='userdetail-info-additional'>
						<div class='col-sm-10'>
							<div class='table-responsive'>
								<table class='table'>
									<tr>
										<th>Occupation</th>
										<td><?php echo $user['userProfileOccupation']?:"-";?></td>
										<th>Group</th>
										<td><?php echo $user['userProfileOccupationGroup'] ? model::load("helper")->occupationGroup($user['userProfileOccupationGroup']) : '-';?></td>
									</tr>
									<tr>
										<th>Facebook</th>
										<td><?php echo $user['userProfileFacebook']?:"-";?></td>
										<th>Twitter</th>
										<td><?php echo $user['userProfileTwitter']?:"-";?></td>
									</tr>
									<tr>
										<th>Web Address</th>
										<td><?php echo $user['userProfileWeb']?:"-";?></td>
										<th>E-Commerce Address</th>
										<td><?php echo $user['userProfileEcommerce']?:"-";?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class='userdetail-subtitle'>
					Introduction Text
					</div>
					<div id='introductional-container' style="padding:5px;">
						<?php echo $user['userProfileIntroductional']?:"-";?>
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