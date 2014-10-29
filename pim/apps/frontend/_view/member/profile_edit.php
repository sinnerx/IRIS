<!-- refer http://www.ericmmartin.com/projects/simplemodal/ for docs -->
<script type="text/javascript" src='<?php echo url::asset("frontend/js/jquery.simplemodal.1.4.4.min.js");?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/css/simplemodal-basic.css");?>">
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("frontend/tools/CLEditor/jquery.cleditor.css");?>" >
<script type="text/javascript" src='<?php echo url::asset("frontend/tools/CLEditor/jquery.cleditor.min.js");?>'></script>
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
	background-color: red;
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
function selectTab(tab)
{
	var $	= jQuery;
	$(".profile-edit-form > div").hide();
	$(".tab-content-"+tab).show();
	$(".tab > a").removeClass("tab-active");
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

		this.hide = function()
		{
			$(".upload-button").hide();
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

router.get(":tab",function(reqs)
{
	jQuery(".tab-content-main, .tab-content-additional").show();
	jQuery("#userProfileIntroductional").cleditor();
	jQuery(".tab-content-main, .tab-content-additional").hide();
	selectTab(reqs.params.tab);
});

$(document).ready(function()
{
	if(window.location.hash == "")
	{
		window.location.hash = "main";
		selectTab("main");
	}
	else
	{
		selectTab(window.location.hash.split("#").join(""));
	}
});

</script>
<div style="display:none;" class='content-panduan'>
	<div class='content-panduan-container'>
	<h3>Panduan Menulis Artikel Pengenalan</h3>
	<ul>
		<li>Pastikan menulis dalam tulisan yang boleh difahami.</li>
		<li>Ia haruslah tentang diri anda, dan apa yang anda mahu pengguna lain tahu tentang anda.</li>
		<li>Tidak menyakiti pengguna lain.</li>
		<li>Sebaiknya tulis dalam tatabahasa yang betul.</li>
		<li><b>Tips</b> : Boleh ceritakan tentang diri anda seperti tempat tinggal, minat, cita-cita, pekerjaan dan sebagainya.</li>
	</ul>
	</div>
</div>
<h3 class="block-heading"><a href='./'>PROFIL AHLI</a><span class="subforum"> > KEMASKINI PROFIL</span></h3>
<form method='post'>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description"> 
		</div>
		<div class="page-sub-wrapper profile-page clearfix">
			<div class="profile-avatar">
			<?php $photoUrl	= $userProfileAvatarPhoto?model::load("image/services")->getPhotoUrl($userProfileAvatarPhoto):null;
			$backgroundUrl	= $photoUrl?";background:url('$photoUrl')":"";
			?>
				<div class="profile-avatar-upload" style="background-size:200px<?php echo $backgroundUrl;?>;" onmouseover='profile.upload.show();' onmouseout='profile.upload.hide();'>
					<a id='avatar-upload-button'>
						<div class='upload-button'>
							<input type='file' id='avatarPhoto' style="display:none;" value="Tukar Foto" name='avatarPhoto' onchange='profile.upload.go();' />
							<label for="avatarPhoto">Tukar Foto</label>
						</div>
					</a>
				</div>
			</div>
			<div class="profile-edit">
				<!-- <div class="profile-top-action">
					<a href="#" class="bttn-ornge"><i class="fa fa-user"></i> Tukar Maklumat Log Masuk</a> <a href="#" class="bttn-ornge"><i class="fa fa-book"></i> Panduan Mengisi Butiran</a> <a href="#" class="bttn-fb"> <i class="fa fa-facebook"></i> Pindah Maklumat Dari Facebook Anda</a>
				</div> -->
				<div class='tab'>
					<a class='tab-main' href='#main' style="padding-right:15px;">
					MAKLUMAT UTAMA
					</a>
					<a class='tab-additional' href='#additional'>
					MAKLUMAT TAMBAHAN
					</a>
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
							<?php for($i = 1;$i<=31;$i++) $dayR[$i] = $i;?>
							<label>Tarikh Lahir</label>
							<?php echo form::select("userProfileDOBday",$dayR,"style='display:inline;'",$DOBday,"[PILIH HARI]");?>
							<?php echo form::select("userProfileDOBmonth",model::load("helper")->monthYear("month"),"style='display:inline;'",$DOBmonth,"[PILIH BULAN]");?>
							<?php echo form::select("userProfileDOByear",model::load("helper")->monthYear("year",1925,date("Y")),"style='display:inline;'",$DOByear,"[PILIH TAHUN]");?>
						</div>
						<div class="profile-edit-row clearfix">
							<label>Tempat Lahir</label>
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
							<label>Kumpulan Kerjaya:</label>
							<?php echo form::select("userProfileOccupationGroup",model::load("helper")->occupationGroup(),null,$userProfileOccupationGroup);?>
						</div>
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
							<label>Artikel Pengenalan:</label> <a href="javascript:pim_modal.show('container','.content-panduan');" class="panduan">Panduan</a>
							<?php echo form::textarea("userProfileIntroductional",null,$userProfileIntroductional);?>
						</div>
					</div>
				</div>
				<div class="profile-edit-row clearfix">
					<input type="reset" class="reset-bttn" value="Batal" onclick='window.location.href = "<?php echo url::createByRoute('profile',Array(),true);?>";' > 
					<input type="submit" class="submit-bttn" value="Simpan">
				</div>
			</div>
		</div>
	</div>
</div>
</form>