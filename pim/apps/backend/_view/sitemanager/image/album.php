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

		var id		= id?id:currID;
		var currID	= id;

		//load list of photos through ajax.
		$.ajax({type:"GET",url:pim.base_url+"ajax/shared/image/albumPhotos/"+id}).done(function(res)
		{
			$("#right-panel").html(res);
		});
	}

	this.addPhotoForm	= new function()
	{
		this.show 	= function(id)
		{
			$.ajax({type:"GET",url:pim.base_url+"ajax/shared/image/albumAddPhoto/"+id}).done(function(res)
			{
				$("#right-panel").html(res);
			});
		}

		pim.uriHash.addCallback({"photoAdd":function(){album.addPhotoForm.show()}});
	}
}



</script>

<style type="text/css">
	
.album-list
{
	cursor: pointer;
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
	<div class='col-sm-6'>
		<div class='row-wrapper' id='album-wrapper'>
		<?php
		if($res_album):
		foreach($res_album as $row)
		{
			$coverimageUrl	= model::load("image/services")->getPhotoUrl($row['albumCoverImageName']);

			?>
			<div class='col-sm-4 album-list' onclick='album.showDetail(<?php echo $row['albumID'];?>);'>
				<section class='panel panel-default'>
					<div class='panel-heading'>
					<?php echo $row['albumName'];?>
					</div>
					<div>
					<img src='<?php echo $coverimageUrl;?>' width='100%' />
					</div>
				</section>
			</div>
			<?php
		}
		else:?>

		<div class='col-sm-12'>
		No album was added yet. For this site. <a href='#add' onclick='album.addForm.show()'>Add?</a>.
		</div>
		<?php
		endif;
		?>
		</div>
		<div class='row' id='add-form' style='display:none;'>
		<div class='col-sm-12'>
			<form method="post" action='<?php echo url::base("image/addAlbum");?>'>
				<div class='form-group'>
					<label>Album Name</label>
					<?php echo form::text("albumName","class='form-control'");?>
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