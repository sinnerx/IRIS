<script type="text/javascript" src='<?php echo url::asset("frontend/js/grapnel.js");?>'></script>
<script type="text/javascript">

var router	= new Grapnel;

var filemanager	= new function()
{
	this.currFolder	= 0;
	this.openFolder = function(folders)
	{
		this.currFolder	= folders;
		$("#fileFolderID").val(this.currFolder); // at add new file form.
		var folders	= folders?folders:"";
		var url = pim.base_url+"ajax/file/openFolder/"+folders;
		$.ajax({type:"GET",url:url}).done(function(result)
		{
			$("#folder-container").html(result);
		});
	}

	this.newFolder = function()
	{
		var folderID	= this.currFolder;
		var data	= {
			fileFolderParentID:folderID,
			fileFolderName:$("#fileFolderName").val(),
			fileFolderPrivacy:$("#fileFolderPrivacy").val()
		};

		$.ajax({type:"POST",data:data,url:pim.base_url+"ajax/file/newFolder/"+folderID}).done(function(res)
		{
			var res	= $.parseJSON(res);

			if(res['error'])
			{
				return alert(res['error']);
			}

			$("#col-new-file #panel-new-file, #col-new-file #panel-new-folder").hide();
			console.log("success");
			window.location.hash = filemanager.currFolder;
		});
	}

	this.deleteFile = function(type,id)
	{
		if(!confirm("Delete "+type+" '"+$("#"+type+"-"+id).attr('data-fileName')+"'. Are you sure.?"))
		{
			return false;
		}
		var url	= pim.base_url+"ajax/file/deleteFile/"+type+"/"+id;
		$.ajax({type:"GET",url:url}).done(function(result)
		{
			filemanager.openFolder(filemanager.currFolder);
		});
	}

	this.downloadFile = function(fileID)
	{
		if(!confirm("Download this file?"))
		{
			return false;
		}
		window.location.href = pim.base_url+"ajax/file/download/"+fileID;
	}

	this.newFile = function(folders)
	{
		/*$.ajax({type:"POST",url:pim.base_url+"ajax/file/addFile/"+folders}).done(function()
		{

		});*/
	}

}();

$(document).ready(function()
{
	router.add(":id/add/:type",function(req)
	{
		$("#col-new-file").show();
		$("#col-new-file #panel-new-file, #col-new-file #panel-new-folder").hide();
		switch(req.params.type)
		{
			case "file":
			$("#panel-new-file").show();
			break;
			case "folder":
			$("#panel-new-folder").show();
			break;
		}
	});
	router.add("*",function(req)
	{
		var folder	= req.params[0];
		folder 		= !folder?0:folder.split("/")[0];
		filemanager.openFolder(folder);
	});
})

</script>
<style type="text/css">
	
#col-new-file
{
	display: none;
}

</style>
<h3 class='m-b-xs text-black'>File Manager</h3>
<div class='well well-sm'>
Manage and share you site files.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-8'>
		<!-- Ajax loaded container. -->
		<div class='panel panel-default' style="position:relative;">
			<div id='folder-container'></div>
		</div>
	</div>
	<div class='col-sm-4' id='col-new-file'>
		<section class="panel panel-default" id='panel-new-folder'>
			<header class="panel-heading font-bold">Add New Folder</header>
			<div class="panel-body">
				<form role="form">
					<div class="form-group">
					<label>Name</label>
						<?php echo form::text("fileFolderName","class='form-control'");?>
					</div>
					<div class="form-group">
					<label>Privacy</label>
						<?php echo form::select("fileFolderPrivacy",Array(1=>"Open for all",2=>"Only for site member",3=>"Only me"),"class='form-control'",1);?>
					</div>
					<button type="button" class="btn btn-sm btn-default" onclick='filemanager.newFolder();'>Submit</button>
					<button type="button" class="btn btn-sm btn-default">Cancel</button>
				</form>
			</div>
		</section>

		<section class="panel panel-default" id='panel-new-file'>
			<header class="panel-heading font-bold">Add New File</header>
			<div class="panel-body">
				<form role="form" method='post' enctype="multipart/form-data">
					<input type='hidden' name='fileFolderID' id='fileFolderID' />
					<div class="form-group">
					<label>File</label>
						<?php echo form::file("fileUpload");?>
						<?php echo flash::data("fileUpload");?>
					</div>
					<div class="form-group">
					<label>Privacy</label>
						<?php echo form::select("filePrivacy",Array(1=>"Open for all",2=>"Only for site member",3=>"Only me"),"class='form-control'",1);?>
						<?php echo flash::data("filePrivacy");?>
					</div>
					<div class="form-group">
					<label>Description</label>
						<?php echo form::textarea("fileDescription","class='form-control'");?>
						<?php echo flash::data("fileDescription");?>
					</div>
					<button type="submit" class="btn btn-sm btn-default">Submit</button>
					<button type="button" class="btn btn-sm btn-default">Cancel</button>
				</form>
			</div>
		</section>
	</div>
	<!-- <div class='col-sm-4'>
		<div class='panel panel-default' id='panel-add-folder'>
			<div class='panel-header'>
			Add Folder
			</div>
			<div class='panel-body'>
				<table class='table'>
					<tr>
						<td>Folder name</td><td><?php echo form::text("fileFolderName","class='form-control'");?></td>
					</tr>
				</table>
			</div>
		</div>
		<div class='panel panel-default' id='panel-add-file'>
			<div class='panel-header'>
			Add File
			</div>
			<div class='panel-body table table-responsive'>
				<table class='table'>
					<tr>
						<td>Upload</td><td><?php echo form::text("fileFolderName","class='form-control'");?></td>
					</tr>
				</table>
			</div>
		</div>
	</div> -->
</div>