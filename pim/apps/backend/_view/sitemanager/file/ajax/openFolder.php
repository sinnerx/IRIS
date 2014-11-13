<style type="text/css">
	
.panel-header
{
	font-size: 1em;
}

.file-list
{
	border-bottom:1px solid #d3d3d3;
	padding:5px;
}

.folder-table td
{
	padding:5px;
	border-bottom:1px solid #d3d3d3;
}
.folder-table tr td:last-child
{
	text-align: right;
}
.folder-table
{
	width: 100%;
}

.folder-table input[type=checkbox]
{
	position: relative;
	top:2px;
}


</style>
<div style="position:absolute;top:0px;right:0px;padding:15px;">
	<div class="input-group-btn" style="display:inline;">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Add Files <span class="caret"></span></button>
		<ul class="dropdown-menu pull-right">
		<li><a href="#<?php echo $fileFolderID;?>/add/folder">Folder</a></li>
		<li><a href="#<?php echo $fileFolderID;?>/add/file">File</a></li>
		</ul>
	</div>
</div>
<div class='panel-header'>
	<a href='#' style="font-size:1em;">/ Root</a>
	<?php
	$nextPrivacyMap	= Array(1=>2,2=>3,3=>1);
	if($header):
		echo " > ";
		foreach($header as $row):

		$headers[]	= "<a href='#$row[fileFolderID]'>$row[fileFolderName]</a>";

		endforeach;
		echo implode(" > ",array_reverse($headers));
	endif;
	?>
</div>
<div class='panel-body'>
	<table class='folder-table'>
	<?php if(count($files) + count($folders) != 0):?>
	<?php
	$templateIcon	= model::load("template/icon");?>
	<tr>
		<th>Name</th>
		<th style="text-align:center;">Type</th>
		<th width="76px;"></th>
	</tr>
	<?php endif;?>
	<?php foreach($folders as $row):?>
	<?php
	## privacy.
	$privacyIcon	= $templateIcon->privacy($row['fileFolderPrivacy']);

	?>
	<tr id='folder-<?php echo $row[fileFolderID];?>' data-fileName='<?php echo $row['fileFolderName'];?>'>
		<td>
			<span class='fa fa-folder' style='color:#ead05e;'></span> 
			<a href='#<?php echo $row['fileFolderID'];?>'><?php echo $row['fileFolderName'];?></a>
		</td>
		<td style='text-align:center;'>File Folders</td>
		<td>
			<a href='javascript:filemanager.deleteFile("folder",<?php echo $row[fileFolderID];?>);' class='i i-cross2'></a>
			<a id='privacy-folder-icon<?php echo $row[fileFolderID];?>' href='javascript:filemanager.updatePrivacy("folder",<?php echo $row[fileFolderID];?>);'><?php echo $privacyIcon;?></a>
			<input type='checkbox' />
		</td>
	</tr>
	<?php endforeach; ?>

	<?php foreach($files as $row):?>
	<?php
	## privacy.
	$privacyIcon	= $templateIcon->privacy($row['filePrivacy']);
	?>
	<tr id='file-<?php echo $row[fileID];?>' data-fileName='<?php echo $row['fileName'];?>'>
		<td>
			<span class='fa fa-file-o'></span> <?php echo $row['fileName'];?>
		</td>
		<td style='text-align:center;'><?php echo $row['fileType'];?></td>
		<td>
			<a href='javascript:filemanager.downloadFile(<?php echo $row[fileID];?>);' class='fa fa-download'></a>
			<a href='javascript:filemanager.deleteFile("file",<?php echo $row[fileID];?>);' class='i i-cross2'></a>
			<a id='privacy-file-icon<?php echo $row[fileID];?>' href='javascript:filemanager.updatePrivacy("file",<?php echo $row[fileID];?>,<?php echo $nextPrivacyMap[$row['filePrivacy']];?>);'><?php echo $privacyIcon;?></a>
			<input type='checkbox' />
		</td>
	</tr>
	<?php endforeach;?>
	<?php if(count($files) + count($folders) == 0):?>
	<tr>
		<td colspan="3" style='text-align:center;'>Empty Folders</td>
	</tr>
	<?php else:?>
	<tr>
		<td colspan="3">
			<?php echo form::select("actionAll",Array("delete"=>"Delete Selected","Privacy"=>"Set Privacy"),"class='form-control pull-right' style='width:200px;'",null,"[On Selection]");?>
		</td>
	</tr>
	<?php endif;?>
	</table>
</div>
