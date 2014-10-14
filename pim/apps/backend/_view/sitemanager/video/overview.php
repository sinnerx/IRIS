<script type="text/javascript">

var album	= new function()
{
	var currID	= null;
	this.addForm	= new function()
	{
		this.show	= function()
		{
			pim.uriHash.set("add");
			pim.func.switchShow("#add-form","#video-album-wrapper");
		}

		this.cancel	= function()
		{
			pim.uriHash.set();
			pim.func.switchShow("#video-album-wrapper","#add-form");
		}

		//register add callback.
		pim.uriHash.addCallback({add:this.show});
	}

	this.showDetail	= function(id)
	{
		pim.redirect("video/albumVideos/"+id);
		return; //temporary.
	}

	this.disableAlbum	= function(siteAlbumID)
	{
		if(confirm("Disable this album. Are you sure?"))
		{
			$.ajax({type:"GET",url:pim.base_url+"video/disableAlbum/"+siteAlbumID}).done(function(res)
			{
				if(res)
				{
					$("#album"+siteAlbumID).addClass("deleted");
				}
			});
		}
	}
}



</script>

<style type="text/css">
	
.album-list div:nth-child(2)
{
	cursor: pointer;
}

.album-list .panel-heading
{
	position: relative;
}

#album-wrapper .panel
{
	padding:0px;
}
.album-list.deleted
{
	opacity: 0.3;
}

.album-list.deleted .album-list-buttons a
{
	display:none;
}
.button-delete
{
	color:red;
}

.button-delete, .button-edit
{
	font-size:16px;
	padding:3px;
}

.album-list-buttons
{
	position: absolute;
	right:0px;
	top:0px;
	display: none;
}
.album-list:hover .album-list-buttons
{
	display: block;
}

/* right panel */
#right-panel
{
	display: none;
}

</style>

<h3 class='m-b-xs text-black'>
Video Albums
</h3>
<div class='well well-sm'>
Overview of your video albums. You can <a href='javascript:album.addForm.show();'>add</a> new video album if you want.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12'>
		<div class='row-wrapper' id='video-album-wrapper'>
		<?php
		if($res_album)
		{
			$opened	= false;
			$no		= 1;
			foreach($res_album as $row)
			{
				if($no == 1)
				{
					$opened	= true;
					echo "<div class='row'>";
				}
				?>
				<div id='album<?php echo $row['videoAlbumID'];?>' class='col-sm-2 album-list<?php if($row['videoAlbumStatus'] == 0){ ?> deleted<?php } ?>'>
					<section class='panel panel-default'>
						<div class='panel-heading'>
						<?php echo $row['videoAlbumName'];?>
						<div class='album-list-buttons'>
							<a href='javascript:album.disableAlbum(<?php echo $row['videoAlbumID'];?>);' class='i i-cross2 button-delete'></a>
						</div>
						</div>
						<div onclick='album.showDetail(<?php echo $row['videoAlbumID'];?>);'>
						<?php if(!$row['videoAlbumThumbnail']){$data = model::load("video/album")->getVideoAlbumCover($row['videoAlbumID']);} ?>
						<img src='<?php if($row['videoAlbumThumbnail']){echo $row['videoAlbumThumbnail'];}else{echo model::load("video/album")->buildVideoUrl($data['videoType'],$data['videoRefID']);} ?>' width='100%' />
						</div>
					</section>
				</div>
				<?php
				if($no == 6)
				{
					echo "</div>";
					$opened	= false;
					$no = 0;
				}
				$no++;
			}

			if($opened)
			{
				echo "</div>";
			}
		}
		else
		{
			?>

		<div class='col-sm-12'>
		No album was added yet. For this site. <a href='#add' onclick='album.addForm.show()'>Add?</a>.
		</div>
		<?php
		}
		?>
		<?php echo pagination::link(); ?>
		</div>
		<!-- adding new album form -->
		<div class='row' id='add-form' style='display:none;'>
		<div class='col-sm-12'>
			<form method="post" action='<?php echo url::base("video/addVideoAlbum");?>'>
				<div class='form-group'>
					<label>Video Album Name</label>
					<?php echo form::text("videoAlbumName","class='form-control'");?>
					<?php echo flash::data("videoAlbumName");?>
				</div>
				<div class='form-group'>
					<label>Description</label>
					<?php echo form::textarea("videoAlbumDescription","class='form-control' style='height:150px;'");?>
				</div>
				<input type='submit' value='Add' class='btn btn-primary pull-right' />
				<input type='button' value='cancel' onclick='album.addForm.cancel();' class='btn btn-default pull-right' />
			</form>
		</div>
		</div>
	</div> <!-- end of left panel -->
	<div class='col-sm-6' id='right-panel'>

	</div>
</div>