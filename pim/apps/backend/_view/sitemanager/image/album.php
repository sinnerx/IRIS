<script type="text/javascript">

var album	= new function()
{
	var currID	= null;
	this.addForm	= new function()
	{
		this.show	= function()
		{
			pim.uriHash.set("add");
			pim.func.switchShow("#add-form","#album-wrapper");
		}

		this.cancel	= function()
		{
			pim.uriHash.set();
			pim.func.switchShow("#album-wrapper","#add-form");
		}

		//register add callback.
		pim.uriHash.addCallback({add:this.show});
	}

	this.showDetail	= function(id)
	{
		pim.redirect("image/albumPhotos/"+id);
		return; //temporary.

		/*var id		= id?id:currID;
		var currID	= id;

		//load list of photos through ajax.
		$.ajax({type:"GET",url:pim.base_url+"ajax/shared/image/albumPhotos/"+id}).done(function(res)
		{
			$("#right-panel").html(res);
		});*/
	}

	this.importToActivity = function (siteAlbumID, activityID){
		$.ajax({type:"GET",url:pim.base_url+"ajax/gallery/importToActivity/"+ siteAlbumID + "/" + activityID}).done(function(res)
			{
				if(res)
				{
					history.back();
				}
		});		
	}

	/*this.addPhotoForm	= new function()
	{
		this.show 	= function(id)
		{
			$.ajax({type:"GET",url:pim.base_url+"ajax/shared/image/albumAddPhoto/"+id}).done(function(res)
			{
				$("#right-panel").html(res);
			});
		}

		pim.uriHash.addCallback({"photoAdd":function(){album.addPhotoForm.show()}});
	}*/

	this.deleteAlbum	= function(siteAlbumID)
	{
		if(confirm("Delete this album. Are you sure?"))
		{
			$.ajax({type:"GET",url:pim.base_url+"ajax/gallery/deleteAlbum/"+siteAlbumID}).done(function(res)
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
Albums
</h3>
<div class='well well-sm'>
Overview of your site albums. You can <a href='javascript:album.addForm.show();'>add</a> new album if you want.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12'>
		<div class='row-wrapper' id='album-wrapper'>
		<?php
		if($res_album)
		{
			$opened	= false;
			$no		= 1;
			foreach($res_album as $row)
			{
				$coverimageUrl	= model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);
				$coverimageUrl	= model::load("api/image")->buildPhotoUrl($row['albumCoverImageName'],"small");

				if($no == 1)
				{
					$opened	= true;
					echo "<div class='row'>";
				}
				?>
				<div id='album<?php echo $row['siteAlbumID'];?>' class='col-sm-2 album-list'>
					<section class='panel panel-default'>
						<div class='panel-heading'>
						<?php echo $row['albumName'];?>
							<div class='album-list-buttons'>
							<?php
							// separate 2017-01-01 with 00:00:00
							$separateTimestamp = explode(' ', $row['albumCreatedDate']);

							// separate 2017-01-01 by '-'
							$monthAndDay = explode('-', $separateTimestamp[0]);

							//combine => 01-01 00:00:00
							$thisDate = $monthAndDay[1].'-'.$monthAndDay[2].' '.$separateTimestamp[1];

							// if 01-01 00:00:00 no delete
							if ($thisDate != '01-01 00:00:00') {
								// if name is 'Exterior Pi1M' or 'Interior Pi1M'
								if ($row['albumName'] != 'Exterior Pi1M' || $row['albumName'] != 'Interior Pi1M') {

							?>
							<a href='javascript:album.deleteAlbum(<?php echo $row['siteAlbumID'];?>);' class='i i-cross2 button-delete'></a>
							<?php } } ?>
							</div>
						</div>
						<?php 
							$import = $_GET['import'];
							if($import == 1){ 
							//echo $_GET['import'];

						?>
							<div onclick='album.importToActivity(<?php echo $row['siteAlbumID'];?>, <?php echo $activityID;?>);'>
						<?php } else {?>
							<div onclick='album.showDetail(<?php echo $row['siteAlbumID'];?>);'>
						<?php } ?>
						<img src='<?php echo $coverimageUrl;?>' width='100%' />
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
		</div>
		<!-- adding new album form -->
		<div class='row' id='add-form' style='display:none;'>
		<div class='col-sm-12'>
			<form method="post" action='<?php echo url::base("image/addAlbum");?>'>
			<?php if($activityID):?>
			<input type='hidden' name='activityID' value='<?php echo $activityID;?>' />
			<?php endif;?>
				<div class='form-group'>
					<label>Album Name <?php echo $activityName?"($activityName)":"";?></label>
					<?php echo form::text("albumName","class='form-control'",$activityName);?>
					<?php echo flash::data("albumName");?>
				</div>
				<div class='form-group'>
					<label>Description</label>
					<?php echo form::textarea("albumDescription","class='form-control' style='height:150px;'");?>
					<?php echo flash::data("albumDescription");?>
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