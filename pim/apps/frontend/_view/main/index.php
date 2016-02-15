<?php
$photoName	= !$photoName?null:$photoName;
?>
<h3 class="block-heading"><?php echo $pageName;?>, <span>di <?php echo $siteName;?></span></h3>
<div class="block-content clearfix">
	<div class="block-image">
		<div class="story-thumbs"><img src="<?php echo $photoName;?>" width="253" height="166"  alt=""/></div>
		<div class="read-more-story"><a href="<?php echo $pageSlug;?>">Baca Lagi</a></div>
	</div>
	<div class="block-story">
	<?php echo $pageText;?>
</div>
</div>