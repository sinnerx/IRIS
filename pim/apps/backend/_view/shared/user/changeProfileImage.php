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
<h3 class="m-b-xs text-black">
Change Profile's Image
</h3>
<div class='well well-sm'>
You can change your profile's image here.
</div>
<?php echo flash::data();?>
<div class='row'>
<div class='col-sm-3'>
                <div class="profile-avatar">
			<?php 
                        var_dump($user['userProfileAvatarPhoto']);
                        //die;
                        
                        $userProfileAvatarPhoto = $user['userProfileAvatarPhoto'];
                        $photoUrl	= $userProfileAvatarPhoto?model::load("image/services")->getPhotoUrl($userProfileAvatarPhoto):null;
			if($photoUrl == ""){
                            $photoUrl = url::asset("frontend/images/emptyavatar.png");
                        }
                        $backgroundUrl	= $photoUrl?";background:url('$photoUrl')":"";
			?>
				<div class="profile-avatar-upload" style="width:200px;height:250px;<?php echo $backgroundUrl;?>;" onmouseover='profile.upload.show();' onmouseout='profile.upload.hide();'>
                                    
                                    <a id='avatar-upload-button'>
						<div class='upload-button'>
							<input type='file' id='avatarPhoto' style="display:none;" value="Tukar Foto" name='avatarPhoto' onchange='profile.upload.go();' />
							<label for="avatarPhoto">Tukar Foto</label>
						</div>
					</a>
				</div>
		</div>
</div>
</div>