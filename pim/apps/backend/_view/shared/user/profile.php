<script type="text/javascript" src='<?php echo url::asset("frontend/js/grapnel.js");?>'></script>
<script type="text/javascript">
	
var pim_modal = new function($)
{
	this.show = function(type,content)
	{
		switch(type)
		{
			case "container":
			content = $(content).html();
			break;
		}

		$.modal(content,{
			position:[100]
		});
	}
}(jQuery);

</script>
<style type="text/css">
	
select
{
	opacity: 0.8;
	padding:5px;
}

.tab
{
	margin-bottom:20px;
	font-size:1.1em;
}
.tab > span
{
	cursor: pointer;
	padding:5px;
	letter-spacing: 1px;
}

.tab .tab-active
{
	font-weight: bold;
	color:#414141;
	font-size:1.2em;
	text-shadow: 0px 0px 10px #a3a3a3 !important;
}

.tab a
{
	color:#009bff;
}
.tab a:hover
{
	text-shadow:0px 0px 10px #bfbfbf;
}

.tab-content-main, .tab-content-additional
{
	display: none;
}
.profile-edit-row input
{
	color: inherit;
}

/*message*/
.msgbox
{
    padding:10px;
    color: white;
}
.msgbox.success
{
    background:#0081d7;
    border:1px dashed #005a97;
}
.msgbox.error
{
    background: #c91d1d;
    border:1px solid #712626;
    box-shadow: 0px 0px 10px #731111;
}

.label.label-danger
{
    background: #ec4848;
    color:white;
    display: inline-block;
    padding:4px;
    float:right;
    margin-right:30px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px #731111;
}
.profile-avatar-upload
{
	position: relative;
	background-size:200px !important;
	background-repeat: no-repeat !important;
	background-color: grey;
}

#avatar-upload-button
{
	position: absolute;
	top:10px;
	display: block;
	height:53px;
	width: 100%;
}
#avatar-upload-button .upload-button
{
	background: #009bff;
	color:white;
	display: none;
}

#avatar-upload-button label
{
	padding:5px;
	display: block;
	cursor: pointer;
}

#avatar-upload-button label:hover
{
	background: #0062a1;
}

/* Modal box styles */
.simplemodal-container
{
	background: white;
	border:0px;
}

#simplemodal-container a.modalCloseImg {
	background:url(<?php echo url::asset('frontend/images/simplemodal-x.png');?>) no-repeat; /* adjust url as required */
	width:25px;
	height:29px;
	display:inline;
	z-index:3200;
	position:absolute;
	top:-15px;
	right:-18px;
	cursor:pointer;
}

.content-panduan-container ul
{
	padding:0px;
	padding-left:15px;
}

.content-panduan-container ul li
{
	padding:5px;
	border-bottom: 1px solid #eaeaea;
}

</style>
<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<script type="text/javascript">
var pim	= new pim({base_url:"<?php echo url::base('{site-slug}');?>"});
var router = new Grapnel();
function selectTab(tab,das)
{
	var $	= jQuery;
	$(".profile-edit-form > div").hide();
	$(".tab-content-"+tab).show();
	$(".tab > a").removeClass("tab-active");
	$(".tab-"+tab).addClass("tab-active");
	
	if(!$(".cleditorGroup")[0])
	{
		if($(".tab-additional").hasClass("tab-active"))
		{
			jQuery("#userProfileIntroductional").cleditor();
		}
	}
}



var profile	= new function()
{
	var $	= jQuery;
	this.upload	= new function()
	{
		this.show = function()
		{
			$(".upload-button").show();
		}

		this.hide = function()
		{
			$(".upload-button").hide();
		}

		this.go	= function()
		{
			//pim.upload.execute("#avatarPhoto",pim.base_url+"profile/uploadAvatar");
                        pim.upload.execute("#avatarPhoto","profileUploadAvatar");//amir
		}

		this.updateAvatar	= function(url)
                
		{
                    console.log('URL: '+url);
			$(".profile-avatar-upload").css("background","url('"+url+"')");
			$(".upload-button").hide();
		}

	}
};

$(document).ready(function()
{
	if(window.location.hash == "")
	{
		window.location.hash = "main";
	}
	else
	{
		selectTab(window.location.hash.split("#").join(""));
	}
});

</script>
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css");?>" type="text/css">
<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>
<h3 class="m-b-xs text-black">
My Profile
</h3>
<div class='well well-sm'>
All about your profile. You can edit all through here.
</div>
<?php echo flash::data();?>
<form method='post'>
<div class='row'>
	<div class='col-sm-5'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<div class='row'>
			<div class='col-sm-6'>
			<label>1. User Full Name</label>
			<?php echo form::text("userProfileFullName","class='form-control' placeholder=\"Your full name\"",$row['userProfileFullName']);?>
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
	<div class='col-sm-4'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>3. Email Address</label>
			<?php echo form::text("userEmail","class='form-control' disabled placeholder='Email for login.'",$row['userEmail']);?>
			<?php echo flash::data("userEmail");?>
		</div>
		</div>
		</div>
	</div>
    <div class='col-sm-3'>
<!--        <div class='panel panel-default'>
		<div class='panel-body'>-->
                    <div class="profile-avatar">
                            <?php 
                            //var_dump($user['userProfileAvatarPhoto']);
                            //die;

                            $userProfileAvatarPhoto = $user['userProfileAvatarPhoto'];
                            $photoUrl	= $userProfileAvatarPhoto?model::load("image/services")->getPhotoUrl($userProfileAvatarPhoto):null;
                            if($photoUrl == ""){
                                $photoUrl = url::asset("frontend/images/emptyavatar.png");
                            }
                            $backgroundUrl	= $photoUrl?";background:url('$photoUrl')":"";
                            ?>
                                    <div class="profile-avatar-upload" style="width:200px;height:230px;<?php echo $backgroundUrl;?>;" onmouseover='profile.upload.show();' onmouseout='profile.upload.hide();'>

                                        <a id='avatar-upload-button'>
                                                    <div class='upload-button'>
                                                            <input type='file' id='avatarPhoto' style="display:none;" value="Tukar Foto" name='avatarPhoto' onchange='profile.upload.go();' />
                                                            <label for="avatarPhoto">Tukar Foto</label>
                                                    </div>
                                            </a>
                                    </div>
                    </div>
<!--		</div>
	</div>-->
        
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
<div class='row'>
	<div class='col-sm-12'>
		<div class='form-group' style='text-align:center;'>
		<?php echo form::submit("Submit","class='btn btn-primary'");?>
		</div>
	</div>
</div>
</form>