<style type="text/css">
	
select
{
	opacity: 0.8;
	padding:5px;
}

.tab
{
	margin-bottom:20px;
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
	color:#0074c1;
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
	background-color: red;
}

#avatar-upload-button
{
	position: absolute;
	bottom:0px;
	display: block;
	height:53px;
	width: 100%;
}
#avatar-upload-button .upload-button
{
	padding:10px;
	background: #009bff;
	color:white;
	display: none;
}

</style>
<script type="text/javascript" src='<?php echo url::base("assets/backend/js/pim.js");?>'></script>
<script type="text/javascript">
var pim	= new pim({base_url:"<?php echo url::base('{site-slug}');?>"});
function selectTab(tab)
{
	var $	= jQuery;
	$(".profile-edit-form > div").hide();
	$(".tab-content-"+tab).show();
	$(".tab > span").removeClass("tab-active");
	$(".tab-"+tab).addClass("tab-active");
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

		this.go	= function()
		{
			pim.upload.execute("#avatarPhoto",pim.base_url+"profile/uploadAvatar");
		}

		this.updateAvatar	= function(url)
		{
			$(".profile-avatar-upload").css("background","url('"+url+"')");
			$(".upload-button").hide();
		}

	}
};

$(document).ready(function()
{
	selectTab("main");
});

</script>
<h3 class="block-heading">PROFIL AHLI<span class="subforum"> > KEMASKINI PROFIL</span></h3>
<form method='post'>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description"> 
		</div>
		<div class="page-sub-wrapper profile-page clearfix">
			<div class="profile-avatar">
			<?php $photoUrl	= model::load("image/services")->getPhotoUrl($userProfileAvatarPhoto);

			?>
				<div class="profile-avatar-upload" style="background-size:200px;background:url('<?php echo $photoUrl;?>');background-color:black;">
					<a href='javascript:profile.upload.show();' id='avatar-upload-button'>
						<div class='upload-button'>
							<input type='file' id='avatarPhoto' name='avatarPhoto' onchange='profile.upload.go();' />
						</div>
					</a>
				</div>
			</div>
			<div class="profile-edit">
				<!-- <div class="profile-top-action">
					<a href="#" class="bttn-ornge"><i class="fa fa-user"></i> Tukar Maklumat Log Masuk</a> <a href="#" class="bttn-ornge"><i class="fa fa-book"></i> Panduan Mengisi Butiran</a> <a href="#" class="bttn-fb"> <i class="fa fa-facebook"></i> Pindah Maklumat Dari Facebook Anda</a>
				</div> -->
				<div class='tab'>
					<span class='tab-main' onclick='selectTab("main");'>
					Maklumat Utama
					</span> | 
					<span class='tab-additional' onclick='selectTab("additional");'>
					Maklumat Tambahan
					</span>
				</div>
				<?php echo flash::data();?>
				<div class="profile-edit-form" style='margin-top:10px;'>
					<div class='tab-content-main'>
						<div class="profile-edit-row clearfix">
							<label>Nama Penuh:</label>
							<div class="clmn-2">
								<?php echo form::text("userProfileFullName","placeholder='Nama'",$userProfileFullName);?>
								<?php echo flash::data("userProfileFullName");?>
							</div>
							<div class="clmn-2">
								<?php echo form::text("userProfileLastName","placeholder='Nama Ayah'",$userProfileLastName);?>
								<?php echo flash::data("userProfileLastName");?>
							</div>
						</div>
						<div class="profile-edit-row clearfix">
							<label>Alamat Email:</label>
							<!-- <i class="fa fa-envelope"></i> -->
							<?php echo form::text("userEmail","placeholder='Email'",$userEmail);?>
							<?php echo flash::data("userEmail");?>
						</div>

						<div class="profile-edit-row clearfix">
							<div class="clmn-2">
								<label>No. Telefon Rumah:</label>
								<?php echo form::text("userProfilePhoneNo","placeholder='No Telefon Rumah'",$userProfilePhoneNo);?>
							</div>
							<div class="clmn-2">
								<label>No. Telefon H/P </label>
								<?php echo form::text("userProfileMobileNo","placeholder='No Telefon H/P'",$userProfileMobileNo);?>
							</div>
						</div>

						<div class="profile-edit-row clearfix">
							<div class="clmn-2">
								<label>Jantina </label>
								<?php echo form::select("userProfileGender",Array(1=>"Lelaki",2=>"Perempuan"),null,$userProfileGender,"[SILA PILIH]");?>
							</div>
							<div class="clmn-2">
								<label>Perkahwinan </label>
								<?php echo form::select("userProfileMarital",Array(1=>"Bujang",2=>"Berkahwin",3=>"Bercerai"),null,$userProfileMarital,"[SILA PILIH]");?>
							</div>
						</div>
						<div class="profile-edit-row clearfix">
							<label>Tempat Lahir :</label>
							<?php echo form::text("userProfilePOB","placeholder='Tempat Lahir'",$userProfilePOB);?>
						</div>
						<div class="profile-edit-row clearfix">
							<label>Alamat Bersurat</label>
							<?php echo form::textarea("userProfileMailingAddress",null,$userProfileMailingAddress);?>
						</div>
					</div>
					<div class='tab-content-additional'>
						<!-- Start of additional info -->
						<div class="profile-edit-row clearfix">
							<label>Kerjaya / Pekerjaan:</label>
							<?php echo form::text("userProfileOccupation",null,$userProfileOccupation);?>
						</div>
						<div class="profile-edit-row clearfix">
						<label>Alamat Profile Facebook:</label>
							<i class="fa fa-facebook"></i>
							<?php echo form::text("userProfileFacebook","class='name icn-lft'",$userProfileFacebook);?>
						</div>
						<div class="profile-edit-row clearfix">
						<label>Alamat Profile Twitter:</label>
							<i class="fa fa-twitter"></i>
							<?php echo form::text("userProfileTwitter","class='name icn-lft'",$userProfileTwitter);?>
						</div>
						<div class="profile-edit-row clearfix">
						<label>Alamat Laman Web:</label>
							<i class="fa fa-link"></i>
							<?php echo form::text("userProfileWeb","class='name icn-lft'",$userProfileWeb);?>
						</div>
						<div class="profile-edit-row clearfix">
						<label>Pautan Laman e-commerce:</label>
							<i class="fa fa-shopping-cart"></i>
							<?php echo form::text("userProfileEcommerce","class='name icn-lft'",$userProfileEcommerce);?>
						</div>
						<div class="profile-edit-row clearfix">
							<label>Artikel Pengenalan:</label> <a href="#" class="panduan">Panduan</a>
							<?php echo form::textarea("userProfileIntroduction",null,$userProfileIntroduction);?>
						</div>
					</div>
				</div>
				<div class="profile-edit-row clearfix">
					<input type="reset" class="reset-bttn" value="Batal"> 
					<input type="submit" class="submit-bttn" value="Simpan">
				</div>
			</div>
		</div>
	</div>
</div>
</form>