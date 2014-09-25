<script type="text/javascript">
	
var album	= new function()
{
	this.albumID	= <?php echo $row['videoAlbumID'];?>;
	this.deletedList	= [];
	this.covervideoEditing = false;
	this.currVideoID		= false;

	this.addVideo	= function()
	{
		pim.uriHash.set("addVideo");
		pim.func.switchShow("#video-add-form",".error-novideo");
	}

	this.addVideoCancel = function()
	{
		pim.uriHash.set();
		pim.func.switchShow(".error-novideo","#video-add-form");
	}

	this.editDescription = function()
	{
		// append text to textarea and show
		var editor	= $("#albumDescription_editor");
		editor.show();

		$("#albumDescription, #albumDescription_editbutton").hide();
	}

	this.editDescription_submit = function()
	{
		var desc = $("#albumDescription_editor #videoAlbumDescription").val();
		var name = $("#albumDescription_editor #videoAlbumName").val();
		name = name[0].toUpperCase() + name.substr(1);
		
		var data = {videoAlbumDescription:desc,videoAlbumName:name};
		$.ajax({type:"POST",data:data,url:pim.base_url+"video/updateAlbum/"+this.albumID}).done(function(desc)
		{
			if(desc)
			{
				var desc = $.parseJSON(desc);

				//update both span, and textarea.
				$("#albumDescription_editor textarea").val(desc[0]);
				$("#albumDescription_editor #videoAlbumName").val(desc[1]);
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

	this.updateCoverVideo = function()
	{
		if(!$(".album-video")[0])
		{
			return alert("Please upload at least one video first");
		}

		if(this.covervideoEditing)
		{
			return false;
		}

		this.covervideoEditing = true;
		//assign class
		$(".album-video").addClass("assigning-covervideo");

		$("#info-box").addClass("bg-primary");
		$("#info-box").data("temporary",$("#info-box").html()); //save current info in data-temporary first.
		$("#info-box").html("You may choose the cover video from any of the videos below : <span onclick='album.updateCoverVideo_reset();' class='pull-right' style='cursor:pointer;'>[ Cancel X ]</span>");

		$(".assigning-covervideo").click(function()
		{
			var ppath = $(this).data("videopath");

			if($(this).hasClass("deleted-video"))
			{
				return alert("This video has been deleted.");
			}

			if(confirm("Use this as cover video. Are you sure?"))
			{			
				album.updateCoverVideo_submit(ppath);
			}

		});
	}

	this.updateCoverVideo_submit = function(path)
	{
		var data = {"videoName":path};
		$.ajax({type:"POST",url:pim.base_url+"ajax/gallery/changeCoverVideo/"+this.albumID,data:data}).done(function(res)
		{
			// change source of current video.
			if(res)
			{
				$("#theCoverVideo").attr("src",res);
				album.updateCoverVideo_reset();
			}
		});
	}

	this.updateCoverVideo_reset = function()
	{
		$(".assigning-covervideo").unbind("click");
		$(".album-video").removeClass("assigning-covervideo");
		$("#info-box").removeClass("bg-primary");
		$("#info-box").html($("#info-box").data("temporary"));
		this.covervideoEditing = false;
	}

	this.deleteVideo	= function(videoID)
	{
		// set up
		if(!pim.func.inArray(videoID,this.deletedList))
		{
			if(confirm("Delete this video. Are you sure?"))
			{
				$("#video"+videoID).addClass("deleted-video").addClass("pending");
				$.ajax({type:"GET",url:pim.base_url+"video/deleteVideo/"+videoID}).done(function(res)
				{
					if(res)
					{
						var res = $.parseJSON(res);
						album.deletedList.push(videoID);
						$("#videoID"+videoID).removeClass("pending");
						$("#theCoverVideo").attr("src","http://img.youtube.com/vi/"+res[0]+"/0.jpg");
					}
				});	
				// add class.
			}
		}
	}

	/* video detailing and editing. */
	this.showVideoDetail = function(siteVideoID)
	{
		if(this.covervideoEditing)
		{
			return false;
		}

		var e = $("#video"+siteVideoID);
		var desc = e.data("description") == ""?"< This video has no description >":e.data("description");
		var vid  = e.find("img").data("video");
		var siteVideoID = e.data("sitevideoid");

		// set highlight.
		$(".album-video .panel-body").removeClass("active");
		e.find(".panel-body").addClass("active");
		
		album.currVideoID = siteVideoID; // set current clicked video id.

		//hide cover video.
		$("#album-info-wrapper").slideUp(function()
			{
				$("#video-detail").slideDown();
				$("#video-image-wrapper embed").remove();
				$("#video-image-wrapper").append('<embed src="'+vid+'" style="width:100%;height:250px;" scale="aspect" controller="true">');
				$("#video-description span").html(desc);
				$("#videoDescription_editor textarea").html($("#video"+siteVideoID).data("descriptionclean"));
				$("#videoDescription_editor #videoRefID").attr('value',$("#video"+siteVideoID).data("refid"));
				$("#video-description a").click(function()
				{
					album.editVideoDescription(siteVideoID);
				})
			});
	}

	this.showVideoDetail_cancel = function()
	{
		album.currVideoID = false;
		$("#video-detail").slideUp(function()
			{
				$("#album-info-wrapper").slideDown();
				$(".album-video .panel-body").removeClass("active");
				$("#video-image-wrapper embed").remove();
			});
	}

	this.editVideoDescription = function(siteVideoID)
	{
		$("#video-description a,#video-description span").hide();
		$("#videoDescription_editor").show();

	}

	this.editVideoDescription_submit = function()
	{
		var type = $("#videoDescription_editor select").val();
		var refid = $("#videoDescription_editor input").val();
		var desc = $("#videoDescription_editor textarea").val();
		var data	= {videoName:desc,videoType:type,videoRefID:refid};
		$.ajax({type:"POST",data:data,url:pim.base_url+"video/updateVideo/"+this.currVideoID}).done(function(res)
		{
			if(res)
			{
				var res = $.parseJSON(res);
				$("#video-description textarea").html(res[0]);
				$("#video-description input").html(res[1]);
				$("#video-description select").val(res[2]);
				$("#video-image-wrapper embed").remove();
				$("#video-image-wrapper").append('<embed src="http://www.youtube.com/embed/'+res[1]+'?autoplay=1" style="width:100%;height:250px;" scale="aspect" controller="true">');
				$("#imgCOver"+res[3]).attr("data-srcbig","http://img.youtube.com/vi/"+res[1]+"/0.jpg");
				$("#imgCOver"+res[3]).attr("data-video","http://www.youtube.com/embed/"+res[1]+"?autoplay=1");
				$("#imgCOver"+res[3]).attr("src","http://img.youtube.com/vi/"+res[1]+"/0.jpg");
				$("#video-description span").html(res[0]);
				$("#video"+album.currVideoID).data("description",res[0]);

				// reset.
				album.editVideoDescription_cancel();
			}
		});
	}

	this.editVideoDescription_cancel = function()
	{
		$("#videoDescription_editor").hide();
		$("#video-description a,#video-description span").show();
	}

	pim.uriHash.addCallback({addVideo:this.addVideo});
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

.video-panel
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

.deleted-video img
{
	opacity: 0.3;
}

.deleted-video .undelete-button
{
	display:inline-block;
}

.deleted.video.pending img
{
	opacity: 0.7;
}

.deleted-video .delete-button
{
	display: none;
}

.delete-button:hover
{
	color:blue;
}

#video-list .panel
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

.album-cover-video-title
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

/* cover video */
.album-video.assigning-covervideo
{
	opacity: 0.3;
	cursor: pointer;
}
.album-video.assigning-covervideo .delete-button, .album-video.assigning-covervideo .detail-button
{
	display: none;
}
.album-video.assigning-covervideo:hover
{
	opacity: 1;
}
.coverVideo_updatetext
{
	display: none;
}
.bg-primary
{
	float:none;
}

/* video detail */
#video-detail
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

#video-image
{
	text-align: center;
}
#video-image-wrapper
{
	width:80%;
	margin:auto;
	background: white;
	padding:1%;
	box-shadow: 0px 5px 10px #141518;
}
#video-image img
{
	width:100%;
}
#video-description
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

.album-video img
{
	cursor: pointer;
}

.album-video .panel-body.active, .album-video:hover .panel-body
{
	background: #e72e2e;
}

/* video description editor */
#videoDescription_editor
{
	display: none;
}
#videoDescription_editor textarea
{
	width: 100%;
	height:100px;
}
</style>
<h3 class='m-b-xs text-black'>
	<a href='<?php echo url::base("image/album");?>'>Video Album</a> : <?php echo $row['albumName'];?>
</h3>
<div>
	Added at, <?php echo date("j F Y, g:i A",strtotime($row['videoAlbumCreatedDate']));?>
</div>
<div class='row'>
	<div class='col-sm-6'>
	<div id='video-add-form' style='display:none;'>
		<div class='well well-sm'>
		Please key in reference ID of the URL video you desire to insert. 
		For example: <b>http://www.youtube.com/watch?v=hwAOsnMqbcY</b> by only taking out <b>hwAOsnMqbcY</b> to <b>Video URL ID</b> field.
		</div>
		<?php echo flash::data();?>
		<div class='row'>
		<form method='post' enctype="multipart/form-data">
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>Select Video Type</label>
					<div class="col-sm-10">              
                        <div class="btn-group m-r">
							<a data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
		                        <span class="dropdown-label">Youtube!&nbsp;</span> 
		                        <span class="caret"></span>
		                    </a>
		                    <ul class="dropdown-menu dropdown-select">
		                        <li><a href="#"><input type="radio" name="videoType" value="1" checked="">Youtube!</a></li>
		                    </ul>
							<?php echo flash::data("videoType");?>
	                    </div>
                    </div>
				</div><br/><br/>

				<div class='form-group'>
					<label>Video URL ID</label>
					http://www.youtube.com/watch?v= <?php echo form::text("videoRefID","class='form-control'");?>
					<?php echo flash::data("videoRefID");?>
				</div>

				<div class='form-group'>
					<label>Video Description</label>
					<?php echo form::textarea("videoName","class='form-control'");?>
					<?php echo flash::data("videoName");?>
				</div>

				<input type='submit' class='btn btn-primary' />
				<input type='button' value='Cancel' onclick='javascript:album.addVideoCancel();' class='btn btn-default' />
			</div>
		</form>
		</div>
	</div>
	<div id='video-list' style='padding-top:10px;'>
		<?php if(!$res_video):?>
		<div class='well well-sm error-novideo'>
		This album has no video yet. <a href='javascript:album.addVideo();'>Add?</a>
		</div>
		<?php else:?>
		<div class='well well-sm' id='info-box'>
		List of all the video for ths album. Or you can <a href='javascript:album.addVideo();'>add</a> more if you want.
		</div>
		
		<div class='row'>
		<!-- List of video start here -->
		<?php
		foreach($res_video as $row_video):
		?>
			<div id='video<?php echo $row_video['videoID'];?>' data-sitevideoid='<?php echo $row_video['videoID'];?>' data-description="<?php echo $row_video['videoName'];?>" data-descriptionclean="<?php echo $row_video['videoName'];?>" data-refID="<?php echo $row_video['videoRefID'] ?>" data-videopath='<?php echo model::load("video/album")->buildVideoUrl($row_video['videoType'],$row_video['videoRefID']); ?>' class='col-sm-3 album-video' style='height:150px;margin-bottom:25px;<?php if($row_video['videoApprovalStatus'] == 2){ ?>opacity:0.4;<?php } ?>'>
			<section class='panel panel-default'>
			<style type="text/css">
			.pending{
				color:grey;
				float:left;
				position:fixed;
			}
			.pending:hover{
				color:grey;
			}
			.approved{
				color:green;
				float:left;
				position:fixed;
			}
			.approved:hover{
				color:green;
			}
			.disapproved{
				color:red;
				float:left;
				position:fixed;
			}
			.disapproved:hover{
				color:red;
			}
			</style>
					<a class='fa fa-stop <?php if($row_video['videoApprovalStatus'] == 0){ ?>pending<?php }else if($row_video['videoApprovalStatus'] == 1){ ?>approved<?php }else if($row_video['videoApprovalStatus'] == 2){ ?>disapproved<?php } ?>'></a>
				<div class='video-panel'>
					<a <?php if($row_video['videoApprovalStatus'] != 2){ ?>href='javascript:album.deleteVideo(<?php echo $row_video['videoID'];?>);'<?php } ?> class='i i-cross2 delete-button'></a>
				</div>
			<div class='panel-body' onclick='album.showVideoDetail(<?php echo $row_video['videoID'];?>);' style='padding:3px;'>
				<img style='width:100%;' id="imgCOver<?php echo $row_video['videoID'];?>" data-srcbig='<?php echo model::load("video/album")->buildVideoUrl($row_video['videoType'],$row_video['videoRefID']); ?>' data-video="<?php echo model::load("video/album")->buildEmbedVideoUrl($row_video['videoType'],$row_video['videoRefID']); ?>" src='<?php echo model::load("video/album")->buildVideoUrl($row_video['videoType'],$row_video['videoRefID']); ?>' />
			</div>
			</section>
			</div>
		<?php endforeach;?>
		</div>
		<!-- list of video end -->
		<?php endif;?>
	</div>
	</div>
	<!-- right panel -->
	<div class='col-sm-6'>
		<div class='row'>
			<div class='col-sm-12' id='album-info-wrapper' style="padding:10px;background:white;">
			<div class='album-cover-video-title'>Album Cover Video <a href='javascript:album.updateCoverVideo();' class='fa fa-edit' title='Change cover video'></a></div>
			<!-- triggered album.updateCoverVideo(); -->
			<div class='coverVideo_updatetext'>
			Please select from the list of video.
			</div>
			<!-- /trigger ends -->
			<img style='width:100%;' id='theCoverVideo' src='<?php echo model::load("video/album")->buildVideoUrl($res_video[0]['videoType'],$res_video[0]['videoRefID']); ?>' />
			<div class='album-description'>
				<span id='albumDescription'><?php echo $row['videoAlbumName'];?></span>
				<div id='albumDescription_editor'>
					<label>Album Name</label>
            	<input type="text" style="width: 100%;margin: 0 0 5px 0;" name="videoAlbumName" id="videoAlbumName" value="<?php echo $row['videoAlbumName'];?>" /><br/>
					<label>Album Description</label>
				<textarea name="videoAlbumDescription" id="videoAlbumDescription"><?php echo $row['videoAlbumDescription'];?></textarea>
				<div style="text-align:right;">
					<input type='submit' class='btn btn-default' onclick='album.cancelEditDescription();' value='Cancel' /> <input type='submit' class='btn btn-primary' onclick='album.editDescription_submit();' value='Update Description' />
				</div>
				</div> 
				<a href='javascript:album.editDescription();' title='Edit description' id='albumDescription_editbutton' class='fa fa-edit'></a></div>
			</div>
		</div>
	</div>
</div>
<div id='video-detail'>
	<div id='video-image'>
		<div id='video-image-wrapper'>
		<div style="padding:5px;padding-bottom:10px;text-align:left;">
			<a href='javascript:album.showVideoDetail_cancel();'>Back</a>
		</div>
		

		</div>
	</div>
	<div id='video-description'>
		<span></span>
		<a class='fa fa-edit' href='#' style='position:absolute;bottom:5px;right:5px;'></a>
		<div id='videoDescription_editor'>
					<label>Video Type</label><br/>
			<select style="width: 100%;margin: 0 0 5px 0;" name="videoType" id="videoType">
				<option value="1">Youtube !</option>
			</select><br/>
					<label>Video Ref ID</label>
            <input type="text" style="width: 100%;margin: 0 0 5px 0;" name="videoRefID" id="videoRefID" /><br/>
					<label>Video Description</label>
			<textarea name="videoName" id="videoName"></textarea>
			<div style="text-align:right;">
				<input type='submit' class='btn btn-default' onclick='album.editVideoDescription_cancel();' value='Cancel' /> <input type='submit' class='btn btn-primary' onclick='album.editVideoDescription_submit();' value='Update Name' />
			</div>
		</div> 
	</div>
</div>