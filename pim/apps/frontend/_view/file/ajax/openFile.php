<!-- <h3 class="block-heading heading-folder-file">Galeri Muat Turun <span class="subforum"> > Folder > Fail</span> </h3>
<div class="gallery-folder-name">Folder : Panduan Media Sosial</div> -->
<!-- <div class="block-content clearfix"> -->
	<!-- <div class="page-content"> -->
<style type="text/css">
.download-gallery-heading a
{
	color:#009bff;
}
</style>
<div class="download-gallery-heading" style="font-size:25px;">
<?php
$header	= array_reverse($header);
$headers[]	= Array("fileFolderID"=>"0","fileFolderName"=>"Folder Utama");
$headers	= array_merge($headers,$header);

if(count($headers) > 0):
	foreach($headers as $row):
	$newHeaders[]	= "<a href='#$row[fileFolderID]'>$row[fileFolderName]</a>";
	endforeach;
endif;
echo implode(" > ",$newHeaders);

// $image	= "images/panduan_perbankan.jpg";



?>
</div>
<div class="page-description"> 
</div>
<!-- <div class="page-sub-wrapper forum-page"> -->
<div class="download-file clearfix">
<div class="download-file-image">
	<?php if(!in_array($fileExt, array("jpg", "jpeg", "png", "bmp"))):?>
	<div class="xfile-icon xfile-icon-pi1m" data-type="<?php echo $fileExt;?>" style="position:relative;top:-10px;"></div>
	<?php else:?>
	<img src="<?php echo $image_url;?>" width="500" height="751"  alt=""/>
	<?php endif;?>

	<a href="<?php echo url::base("{site-slug}/ajax/file/downloadFile/$fileID");?>" class="upload-new-button">Muat Turun</a>
</div>
<div class="download-file-content">
<div class="download-file-header">
	<div class="download-file-title"><?php echo $fileName;?></div>
</div>
<div class="download-file-details">
<?php echo $fileDescription;?>
<!-- Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.<br><br>Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio. -->
</div>
<div class="download-file-info"><i class="fa fa-user"></i> Dimuat Naik Oleh:<a href="#"> <?php echo $userProfileFullName;?></a>, Pada <a href="#"> <?php echo model::load("helper")->frontendDate($fileCreatedDate);?></a>.</div>
</div>
</div>

<div id="comment-container">
	<?php controller::load("comment","getComments/".$fileID."/file");?>
</div>
<?php if(session::get("userID")){controller::load("comment","getForm");} ?>

	<!-- </div> -->
	<!-- </div> -->
<!-- </div> -->