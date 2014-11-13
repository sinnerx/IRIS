
<?php
$header	= array_reverse($header);
$headers[]	= Array("fileFolderID"=>"0","fileFolderName"=>"Folder Utama");
$headers	= array_merge($headers,$header);

if(count($headers) > 0):
	foreach($headers as $row):
	$newHeaders[]	= "<a href='#$row[fileFolderID]'>$row[fileFolderName]</a>";
	endforeach;
endif;
?>
<style type="text/css">
	
.download-gallery-heading a
{
	color:#009bff;
}
</style>
<!-- <a href="#" class="upload-new-button">Muat Naik Fail Baru <i class="fa fa-plus-circle"></i></a> -->
<div class="download-gallery-heading" style="font-size:25px;">
<?php echo implode(" > ",$newHeaders);?>
</div>
<ul>
<?php if($folders):?>
<?php 
$no = 1;
foreach($folders as $row):?>
<li>
	<div class="folder-icon">
	<div id="file-count"><i class="fa fa-user"></i> <span><?php echo $no;?></span></div>
	</div>
	<div class="folder-name"><a onclick='window.location.hash = "<?php echo $row['fileFolderID'];?>"' href='javascript:void(0);'><?php echo $row['fileFolderName'];?></a></div>
	<div class="folder-info">
	<?php echo $totalFiles['folders'][$row['fileFolderID']];?> Sub-Folder,
	<?php echo $totalFiles['files'][$row['fileFolderID']];?> Fail
	<!-- 1 Sub-Folder, 2 Fail -->
	</div>
</li>
<?php $no++;?>
<?php endforeach;?>
<?php endif; ## for if folders?>
<?php if($files):?>
<?php foreach($files as $row):?>
<li>
	<div class="file-icon">
	<div class="file-icon-wrap"><img src="<?php echo url::asset("frontend/images/pdf_icons.png");?>" width="48" height="63"  alt=""/></div>
	</div>
	<div class="folder-name"><a onclick='window.location.hash = "<?php echo "$fileFolderID/$row[fileID]";?>";' href="javascript:void(0);"><?php echo $row['fileName'];?></a></div>
	<div class="folder-info"><?php echo strtoupper($row['fileType']);?></div>
</li>
<?php endforeach;?>
<?php endif;## for if($files)?>
</ul>

<?php if(count($files) + count($folders) == 0):?>
<div>
	Folder kosong.
</div>
<?php endif;?>