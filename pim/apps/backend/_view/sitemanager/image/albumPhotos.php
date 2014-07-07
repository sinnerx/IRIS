<script type="text/javascript">
	
var album	= new function()
{
	this.deletedList	= [];
	
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

	this.deletePhoto	= function(photoID)
	{
		// set up
		if(!pim.func.inArray(photoID,this.deletedList))
		{
			if(confirm("Are you sure?"))
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
		}/*
		else
		{
			$.ajax({type:"GET",url:pim.base_url+"ajax/gallery/undeletePhoto/"+photoID}).done(function(res)
			{
				if(res)
				{
					album.deletedList	= pim.func.arrayRemoveElement(album.deletedList,photoID);
					$("#photo"+photoID).removeClass("deleted-photo");
				}
			});
		}*/
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
	position: absolute;
	right:5px;
	top:5px;
	font-size:25px;
	color:red;
}

.undelete-button
{
	color:blue;
	font-size:20px;
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

#photo-deleted-list img
{
	width:100%;
}

</style>
<h3 class='m-b-xs text-black'>
	<a href='<?php echo url::base("image/album");?>'>Album</a> : <?php echo $row['albumName'];?>
</h3>

<div class='row'>
	<div class='col-sm-6'>
	<div id='photo-add-form' style='display:none;'>
		<div class='well well-sm'>
		Please upload only jpg, jpeg, png or bmp. Wrong upload will not be toleranted and results to a ban.
		</div>
		<?php echo flash::data();?>
		<div class='row'>
		<form method='post' enctype="multipart/form-data">
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>Select Photo</label>
					<?php echo form::file("photoName");?>
				</div>

				<div class='form-group'>
					<label>Description (optional)</label>
					<?php echo form::textarea("photoDescription","class='form-control'");?>
				</div>
				<input type='submit' class='btn btn-primary' />
				<input type='button' value='Cancel' onclick='javascript:album.addPhotoCancel();' class='btn btn-default' />
			</div>
		</form>
		</div>
	</div>
	<div id='photo-list' style='padding-top:10px;'>
		<?php if(!$res_photo):?>
		<div class='well well-sm error-nophoto'>
		This album has no photo yet. <a href='javascript:album.addPhoto();'>Add?</a>
		</div>
		<?php else:?>
		<div class='well well-sm'>
		List of all the photo for ths album. Or you can <a href='javascript:album.addPhoto();'>add</a> more if you want.
		</div>
		<div id='photo-deleted-list' class="row">
		</div>
		<div class='row'>
		<?php
		foreach($res_photo as $row_photo):
			$imageUrl	= model::load("image/services")->getPhotoUrl($row_photo['photoName']);
			?>
			<div id='photo<?php echo $row_photo['sitePhotoID'];?>' class='col-sm-3' style='height:150px;margin-bottom:25px;'>
			<section class='panel panel-default'>
			<a href='javascript:album.deletePhoto(<?php echo $row_photo['sitePhotoID'];?>);' class='i i-cross2 delete-button'></a>
			<!-- <a href='javascript:album.deletePhoto(<?php echo $row_photo['sitePhotoID'];?>);' class='i i-undo undelete-button'></a> -->
			<div class='panel-body' style='padding:3px;'>
				<img style='width:100%;' src='<?php echo $imageUrl;?>' />
			</div>
			</section>
			</div>
		<?php endforeach;?>
		</div>
		<?php endif;?>
	</div>
	</div>
	<!-- right panel -->
	<div class='col-sm-6'>
		<div class='row-wrapper'>
			<section class='panel panel-default'>
			<div class='table-responsive'>
				<table class='table'>
					<tr>
						<td>Cover Photo</td>
					</tr>
					<tr>
						<td data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $row['albumDescription'];?>">
							<img style='width:100%;' src='<?php echo model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);?>' />
						</td>
					</tr>
				</table>
			</div>
			</section>
		</div>
	</div>
</div>