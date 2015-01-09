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
<input type='hidden' id='currentFileFolderID' value='<?php echo $fileFolderID;?>' />
<div class='panel-header'>
	<a href='#' style="font-size:1em;">/ Root</a>
	<?php
	$nextPrivacyMap	= Array(1=>2,2=>3,3=>1);
	if($header):
		echo " > ";
		$level = 1;
		foreach($header as $row):

		$headers[]	= "<a href='#$row[fileFolderID]'>$row[fileFolderName]</a>";

		$level++;
		endforeach;
		echo implode(" > ",array_reverse($headers));
		echo form::hidden('currentLevel', null, $level);
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
		<th width="76px;"><input type='checkbox' class='pull-right files_main_checkbox' style="position:relative;right:5px;" onclick='filemanager.checkall();' /></th>
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
			<input type='checkbox' class='files_selection' value='folder_<?php echo $row[fileFolderID];?>' />
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
			<span class='fa fa-file-o'></span> <?php echo $row['fileName'];?> (<?php echo $totalDownloads[$row[fileID]];?> download)
		</td>
		<td style='text-align:center;'><?php echo $row['fileType'];?></td>
		<td>
			<a href='javascript:filemanager.downloadFile(<?php echo $row[fileID];?>);' class='fa fa-download'></a>
			<a href='javascript:filemanager.deleteFile("file",<?php echo $row[fileID];?>);' class='i i-cross2'></a>
			<a id='privacy-file-icon<?php echo $row[fileID];?>' href='javascript:filemanager.updatePrivacy("file",<?php echo $row[fileID];?>,<?php echo $nextPrivacyMap[$row['filePrivacy']];?>);'><?php echo $privacyIcon;?></a>
			<input type='checkbox' class='files_selection' value='file_<?php echo $row[fileID];?>' />
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
		<?php echo form::select("privacy",Array(1=>"Open for all",2=>"Only site member",3=>"Only me"),"onchange='filemanager.actionOnSelected(\"set-privacy\",true);' class='form-control pull-right' style='width:150px;display:none;'",null,"[Privacy]");?>
			<?php echo form::select("actionAll",Array("delete"=>"Delete Selected","set-privacy"=>"Set Privacy"),"onchange='filemanager.actionOnSelected(this.value);' class='form-control pull-right' style='width:200px;'",null,"[On Selection]");?>
		</td>
	</tr>
	<?php endif;?>
	</table>
</div>
