<style type="text/css">
  
</style>
<title><?php echo $title;?></title>
<h3 class='block-heading'><?php echo $title;?></h3>
  <div class="block-content clearfix">
  <div class="page-content">
    <div class="page-sub-wrapper single-page clearfix">
      <div class="content-page" style='min-height:400px;'>
      <?php if($pageImageUrl):?>
      <img id='pageImage' src='<?php echo $pageImageUrl;?>' />
      <?php endif;?>
      <?php echo stripslashes($row_page['pageText']);?>
      </div>
    </div>
  </div>
  </div>
<script type="text/javascript">
//if image loaded. check if width more than 50% maximize it. else. keep it.
$("#pageImage").load(function()
{
	console.log('img loaded.');
	var width = jQuery(this).outerWidth();

	if(width > 320)
	{
		jQuery(this).attr("width","660px");
	}
});

</script>