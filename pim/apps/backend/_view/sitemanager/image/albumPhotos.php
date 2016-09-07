<script type="text/javascript">
var album	= new function()
{
	this.albumID	= <?php echo $row['siteAlbumID'];?>;
	this.deletedList	= [];
	this.coverphotoEditing = false;
	this.currPhotoID		= false;

	this.addPhoto	= function()
	{
		pim.uriHash.set("addPhoto");
		pim.func.switchShow("#photo-add-form",".error-nophoto");
	}

	this.addPhotoCancel = function()
	{
		pim.uriHash.set();
		pim.func.switchShow(".error-nophoto","#photo-add-form");
	}

	this.editDescription = function()
	{
		// $("#albumDescription").attr("contenteditable",true).addClass("editing").focus();
		// $("#albumDescription").parent().find(".fa.fa-edit").hide();

		// append text to textarea and show
		var editor	= $("#albumDescription_editor");
		editor.show();

		$("#albumDescription, #albumDescription_editbutton").hide();

		// $("#albumDescription").blur(function()
		// {
		// 	$(this).attr("contenteditable",false).removeClass("editing");
		// 	$(this).parent().find(".fa.fa-edit").show();
		// });
	}

	this.editDescription_submit = function()
	{

		var desc = $("#albumDescription_editor textarea").val();
		var data = {albumDescription:desc};
		$.ajax({type:"POST",data:data,url:pim.base_url+"ajax/gallery/changeDescription/"+this.albumID}).done(function(desc)
		{
			if(desc)
			{
				var desc = $.parseJSON(desc);

				//update both span, and textarea.
				$("#albumDescription_editor textarea").val(desc[0]);
				$("#albumDescription").html(desc[1]);
				album.cancelEditDescription();
			}
		});
	}

	this.cancelEditDescription = function()
	{
		$("#albumDescription_editor").hide();
		$("#albumDescription, #albumDescription_editbutton").show();
	}

	this.updateCoverPhoto = function()
	{
		if(!$(".album-photo")[0])
		{
			return alert("Please upload at least one photo first");
		}

		if(this.coverphotoEditing)
		{
			return false;
		}

		this.coverphotoEditing = true;
		//assign class
		$(".album-photo").addClass("assigning-coverphoto");

		$("#info-box").addClass("bg-primary");
		$("#info-box").data("temporary",$("#info-box").html()); //save current info in data-temporary first.
		$("#info-box").html("You may choose the cover photo from any of the photos below : <span onclick='album.updateCoverPhoto_reset();' class='pull-right' style='cursor:pointer;'>[ Cancel X ]</span>");

		$(".assigning-coverphoto").click(function()
		{
			var ppath = $(this).data("photopath");

			if($(this).hasClass("deleted-photo"))
			{
				return alert("This photo has been deleted.");
			}

			if(confirm("Use this as cover photo. Are you sure?"))
			{			
				album.updateCoverPhoto_submit(ppath);
			}

		});
	}

	this.updateCoverPhoto_submit = function(path)
	{
		var data = {"photoName":path};
		$.ajax({type:"POST",url:pim.base_url+"ajax/gallery/changeCoverPhoto/"+this.albumID,data:data}).done(function(res)
		{
			// change source of current photo.
			if(res)
			{
				$("#theCoverPhoto").attr("src",res);
				album.updateCoverPhoto_reset();
			}
		});
	}

	this.updateCoverPhoto_reset = function()
	{
		$(".assigning-coverphoto").unbind("click");
		$(".album-photo").removeClass("assigning-coverphoto");
		$("#info-box").removeClass("bg-primary");
		$("#info-box").html($("#info-box").data("temporary"));
		this.coverphotoEditing = false;
	}

	this.deletePhoto	= function(photoID)
	{
		// set up
		if(!pim.func.inArray(photoID,this.deletedList))
		{
			if(confirm("Delete this photo. Are you sure?"))
			{
				$("#photo"+photoID).addClass("deleted-photo").addClass("pending");
				$.ajax({type:"GET",url:pim.base_url+"ajax/gallery/deletePhoto/"+photoID}).done(function(res)
				{
					if(res)
					{
						album.deletedList.push(photoID);
						$("#photoID"+photoID).removeClass("pending");
					}
				});	
				// add class.
			}
		}
	}

	/* Photo detailing and editing. */
	this.showPhotoDetail = function(sitePhotoID)
	{
		if(this.coverphotoEditing)
		{
			return false;
		}

		var e = $("#photo"+sitePhotoID);
		var desc = e.data("description") == ""?"< This photo has no description >":e.data("description");
		var img  = e.find("img").data("srcbig");
		var sitePhotoID = e.data("sitephotoid");

		// set highlight.
		$(".album-photo .panel-body").removeClass("active");
		e.find(".panel-body").addClass("active");
		
		album.currPhotoID = sitePhotoID; // set current clicked photo id.

		//hide cover photo.
		$("#album-info-wrapper").slideUp(function()
			{
				$("#photo-detail").slideDown();
				$("#photo-image img").attr("src",img);
				$("#photo-description span").html(desc);
				$("#photoDescription_editor textarea").html($("#photo"+sitePhotoID).data("descriptionclean"));
				$("#photo-description a").click(function()
				{
					album.editPhotoDescription(sitePhotoID);
				})
			});
	}

	this.showPhotoDetail_cancel = function()
	{
		album.currPhotoID = false;
		$("#photo-detail").slideUp(function()
			{
				$("#album-info-wrapper").slideDown();
				$(".album-photo .panel-body").removeClass("active");
			});
	}

	this.editPhotoDescription = function(sitePhotoID)
	{
		$("#photo-description a,#photo-description span").hide();
		$("#photoDescription_editor").show();

	}

	this.editPhotoDescription_submit = function()
	{

		var data	= {photoDescription:$("#photoDescription_editor textarea").val()};
		$.ajax({type:"POST",data:data,url:pim.base_url+"ajax/gallery/changePhotoDescription/"+this.currPhotoID}).done(function(res)
		{
			if(res)
			{
				var res = $.parseJSON(res);
				$("#photo-description textarea").html(res[0]);
				$("#photo-description span").html(res[1]);
				$("#photo"+album.currPhotoID).data("description",res[1]);

				// reset.
				album.editPhotoDescription_cancel();
			}
		});
	}

	this.editPhotoDescription_cancel = function()
	{
		$("#photoDescription_editor").hide();
		$("#photo-description a,#photo-description span").show();
	}

	pim.uriHash.addCallback({addPhoto:this.addPhoto});
}

</script>
<style type="text/css">
	
.panel
{
	padding:0px;
}

.delete-button, .undelete-button
{
	color:red;
	font-size: 18px;
}

.photo-panel
{
	position: absolute;
	right:0px;top:0px;
	background: white;
}

.detail-button
{
	font-size:15px;
}

.undelete-button
{
	color:blue;
}

.deleted-photo img
{
	opacity: 0.3;
}

.deleted-photo .undelete-button
{
	display:inline-block;
}

.deleted.photo.pending img
{
	opacity: 0.7;
}

.deleted-photo .delete-button
{
	display: none;
}

.delete-button:hover
{
	color:blue;
}

#photo-list .panel
{
	position: relative;
}

/* desc */
.album-description
{
	border-top:1px solid #dfe4ee;
	border-bottom:1px solid #dfe4ee;
	padding:10px;
	margin-top:10px;
}

.album-cover-photo-title
{
	font-size: 15px;
	position: absolute;
	background: white;
	padding:4px;
	box-shadow: 3px 3px 5px -2px #5f5f5f;
}

#albumDescription.editing
{
	padding:5px 10px 5px 10px;
	display: block;
}

#albumDescription_editor
{
	display: none;
}
#albumDescription_editor textarea
{
	width: 100%;
	height:100px;
}
.btn.btn-default
{
	background: #aaaaaa !important;
}

/* cover photo */
.album-photo.assigning-coverphoto
{
	opacity: 0.3;
	cursor: pointer;
}
.album-photo.assigning-coverphoto .delete-button, .album-photo.assigning-coverphoto .detail-button
{
	display: none;
}
.album-photo.assigning-coverphoto:hover
{
	opacity: 1;
}
.coverPhoto_updatetext
{
	display: none;
}
.bg-primary
{
	float:none;
}

/* photo detail */
#photo-detail
{/*
	background: #434650;*/
	width:50%;
	min-height: 100%;
	right:0px;
	position: absolute;
	z-index: 100;
	top:0px;
	padding-bottom: 50px;
	padding-top:50px;
	display: none;
	/*box-shadow: inset 10px 0px 10px -5px #000000;*/
}

#photo-image
{
	text-align: center;
}
#photo-image-wrapper
{
	width:80%;
	margin:auto;
	background: white;
	padding:1%;
	box-shadow: 0px 5px 10px #141518;
}
#photo-image img
{
	width:100%;
}
#photo-description
{
	position: relative;
	width:80%;
	background: white;
	margin: auto;
	margin-top:10px;
	padding:10px;
	border:1px solid #b4b0bd;
	box-shadow: inset 0px 0px 5px #bec8dc;
}

.album-photo img
{
	cursor: pointer;
}

.album-photo .panel-body.active, .album-photo:hover .panel-body
{
	background: #e72e2e;
}

/* photo description editor */
#photoDescription_editor
{
	display: none;
}
#photoDescription_editor textarea
{
	width: 100%;
	height:100px;
}
</style>

<script src='<?php echo url::asset("_scale/js/upload/jquery.1.11.3.min.js");?>'></script>
<script src='<?php echo url::asset("_scale/js/upload/tmpl.min.js");?>'></script>
<!-- blueimp Gallery script -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.blueimp-gallery.min.js");?>'></script>
<script type="text/javascript" src='<?php echo url::asset("_scale/js/upload/vendor/jquery.ui.widget.js");?>'></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script type="text/javascript" src='<?php echo url::asset("_scale/js/upload/jquery.iframe-transport.js");?>'></script>

<!-- The basic File Upload plugin -->
<script type="text/javascript" src='<?php echo url::asset("_scale/js/upload/jquery.fileupload.js");?>'></script>

<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src='<?php echo url::asset("_scale/js/upload/load-image.all.min.js");?>'></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src='<?php echo url::asset("_scale/js/upload/canvas-to-blob.min.js");?>'></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!-- <script src='<?php //echo url::asset("_scale/js/upload/bootstrap.3.2.0.min.js");?>'></script> -->


<!-- The File Upload processing plugin -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.fileupload-process.js");?>'></script>
<!-- The File Upload image preview & resize plugin -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.fileupload-image.js");?>'></script>
<!-- The File Upload audio preview plugin -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.fileupload-audio.js");?>'></script>
<!-- The File Upload video preview plugin -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.fileupload-video.js");?>'></script>
<!-- The File Upload validation plugin -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.fileupload-validate.js");?>'></script>
<!-- The File Upload user interface plugin -->
<script src='<?php echo url::asset("_scale/js/upload/jquery.fileupload-ui.js");?>' ></script>
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<script src='<?php echo url::asset("_scale/js/upload/main.js");?>'></script>
<link rel="stylesheet" href='<?php echo url::asset("_scale/css/upload/jquery.fileupload.css"); ?>'>
<link rel="stylesheet" href='<?php echo url::asset("_scale/css/upload/jquery.fileupload-ui.css"); ?>'>

<h3 class='m-b-xs text-black'>
	<a href='<?php echo url::base("image/album");?>'>Album</a> : <?php echo $row['albumName'];?>
</h3>
<div>
	Added at, <?php echo date("j F Y, g:i A",strtotime($row['albumCreatedDate']));?>
</div>
<div class='row'>
	<div class='col-sm-6'>
	<div id='photo-add-form' style='display:none;'>
		<div class='well well-sm'>
		Please upload only jpg, jpeg, png or bmp. Maximum size limit is <?php echo $maxSize/1000000;?>mb. Wrong upload will not be toleranted and results to a ban.
		</div>
		<?php echo flash::data();?>
		<div class='row'>

<div class='col-sm-12'>
	
<form id="fileupload"  method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-12">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-primary fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="photoName" id="photoName" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-primary cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <input type='button' value='Cancel' onclick='javascript:album.addPhotoCancel();' class='btn btn-default' style="float:right"/>
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
</div>



		</div>
	</div>
	<div id='photo-list' style='padding-top:10px;'>
		<?php if(!$res_photo):?>
		<div class='well well-sm error-nophoto'>
		This album has no photo yet. <a href='javascript:album.addPhoto();'>Add?</a>
		</div>
		<?php else:?>
		<div class='well well-sm' id='info-box'>
		List of all the photo for ths album. Or you can <a href='javascript:album.addPhoto();'>add</a> more if you want.
		</div>
		
		<div class='row'>
		<!-- List of photo start here -->
		<?php
		foreach($res_photo as $row_photo):
			$imageUrl	= model::load("image/services")->getPhotoUrl($row_photo['photoName']);
			$row_photo['photoDescription']	= htmlentities($row_photo['photoDescription']);

			## now use image resizer.
			$imageUrl	= model::load("api/image")->buildPhotoUrl($row_photo['photoName'],"small");
			$imageBigUrl= model::load("api/image")->buildPhotoUrl($row_photo['photoName'],"big");
			?>
			<div id='photo<?php echo $row_photo['sitePhotoID'];?>' data-sitephotoid='<?php echo $row_photo['sitePhotoID'];?>' data-description="<?php echo nl2br($row_photo['photoDescription']);?>" data-descriptionclean="<?php echo $row_photo['photoDescription'];?>" data-photopath='<?php echo $row_photo['photoName'];?>' class='col-sm-3 album-photo' style='height:150px;margin-bottom:25px;'>
			<section class='panel panel-default'>
				<div class='photo-panel'>
					<a href='javascript:album.deletePhoto(<?php echo $row_photo['sitePhotoID'];?>);' class='i i-cross2 delete-button'></a>
				</div>
			<div class='panel-body' onclick='album.showPhotoDetail(<?php echo $row_photo['sitePhotoID'];?>);' style='padding:3px;'>
				<img style='width:100%;' data-srcbig='<?php echo $imageBigUrl;?>' src='<?php echo $imageUrl;?>' />
			</div>
			</section>
			</div>
		<?php endforeach;?>
		</div>
		<!-- list of photo end -->
		<?php endif;?>
	</div>
	</div>
	<!-- right panel -->
	<div class='col-sm-6'>
		<div class='row'>
			<div class='col-sm-12' id='album-info-wrapper' style="padding:10px;background:white;">
			<div class='album-cover-photo-title'>Album Cover Photo <a href='javascript:album.updateCoverPhoto();' class='fa fa-edit' title='Change cover photo'></a></div>
			<!-- triggered album.updateCoverPhoto(); -->
			<div class='coverPhoto_updatetext'>
			Please select from the list of photo.
			</div>
			<!-- /trigger ends -->
			<img style='width:100%;' id='theCoverPhoto' src='<?php echo model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);?>' />
			<div class='album-description'>
				<span id='albumDescription'><?php echo nl2br($row['albumDescription']);?></span>
				<div id='albumDescription_editor'>
				<textarea><?php echo $row['albumDescription'];?></textarea>
				<div style="text-align:right;">
					<input type='submit' class='btn btn-default' onclick='album.cancelEditDescription();' value='Cancel' /> <input type='submit' class='btn btn-primary' onclick='album.editDescription_submit();' value='Update Description' />
				</div>
				</div> 
				<a href='javascript:album.editDescription();' title='Edit description' id='albumDescription_editbutton' class='fa fa-edit'></a></div>
			</div>
		</div>
	</div>
</div>
<div id='photo-detail'>
	<div id='photo-image'>
		<div id='photo-image-wrapper'>
		<div style="padding:5px;padding-bottom:10px;text-align:left;">
			< <a href='javascript:album.showPhotoDetail_cancel();'>Back</a>
		</div>
		<img />

		</div>
	</div>
	<div id='photo-description'>
		<span></span>
		<a class='fa fa-edit' href='#' style='position:absolute;bottom:5px;right:5px;'></a>
		<div id='photoDescription_editor'>
			<textarea></textarea>
			<div style="text-align:right;">
				<input type='submit' class='btn btn-default' onclick='album.editPhotoDescription_cancel();' value='Cancel' /> <input type='submit' class='btn btn-primary' onclick='album.editPhotoDescription_submit();' value='Update Description' />
			</div>
		</div> 
	</div>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% 
	for (var i=0, file; file=o.files[i]; i++) { 
%}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <textarea name="photoDescription[]" id="photoDescription" class="form-control" placeholder="Enter description here."></textarea>
            
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{%
 for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
    </tr>
{% } %}
</script>
<script>
$(function () {
    'use strict';
    //var pimurl = pim.base_url +"image/albumPhotos/" + <?php echo $row['siteAlbumID']?>;
    var pimurl = pim.base_url +"image/multipleUploadAlbumPhotos/" + <?php echo $row['siteAlbumID']?>;
    //alert(pimurl);
    var fileCount = 0, fails = 0, successes = 0;
    var _totalCountOfFilesToUpload = -1;

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
       // url: 'server/php/'
        //url: "http://localhost/digitalgaia/iris/dashboard/image/albumPhotos/8#addPhoto"
        url: pimurl,
        maxFileSize: 1999000,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i        
        // url: "/image/albumPhotos/8#addPhoto"
    }).bind('fileuploaddone', function (e, data) {
        if (_totalCountOfFilesToUpload < 0) {
             _totalCountOfFilesToUpload = data.getNumberOfFiles();
        }
        fileCount++;
        successes++;
        if (fileCount === _totalCountOfFilesToUpload) {
            console.log('all done, successes: ' + successes + ', fails: ' + fails);
            // refresh page
            location.reload();
        }
    }).bind('fileuploadfail', function(e, data) {
        fileCount++;
        fails++;
        if (fileCount === _totalCountOfFilesToUpload) {
            console.log('all done, successes: ' + successes + ', fails: ' + fails);
            // refresh page
            //location.reload();
    }
    });

    $('#fileupload').bind('fileuploadsubmit', function (e, data) {
        var inputs = data.context.find(':input');
        if (inputs.filter(function () {
               // return !this.value && $(this).prop('required');
            }).first().focus().length) {
            data.context.find('button').prop('disabled', false);
            return false;
        }
        data.formData = inputs.serializeArray();
    });    
});
</script>