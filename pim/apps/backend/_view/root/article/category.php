<script type="text/javascript">
		$(document).ready(function()
		{
			setTimeout(function()
			{
				$("#mce_14").attr("data-toggle","ajaxModal").attr("onclick","pimgallery.start(this,setUrl);");
			},500);

			<?php 
			foreach($category as $cat): 
				if($cat['child']):
			?>
			$('#parent<?php echo $cat['categoryID']; ?>').click(function(){
				if($(this).is(':checked')){
					$('#child<?php echo $cat['categoryID']; ?>').slideDown();
				}else{
					$('#child<?php echo $cat['categoryID']; ?>').find('input').attr("checked", false);
					$('#child<?php echo $cat['categoryID']; ?>').slideUp();
				}
			});
			<?php 
				endif;
			endforeach; 
			?>

		})
</script>
<?php
	foreach($category as $cat):
	                	?>	
	                	<input type="checkbox" id="parent<?php echo $cat['categoryID']; ?>" name="category[]" value="<?php echo $cat['categoryID']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cat['categoryName']; ?><br/>
	                	<div style="display: none;" id="child<?php echo $cat['categoryID']; ?>">
	                	<?php
	                			if($cat['child']):
	                				foreach($cat['child'] as $c):
	                	?>
	                	<span style="padding-left: 15px;"><input type="checkbox" name="category[]" value="<?php echo $c['categoryID']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $c['categoryName']; ?></span><br/>
	                	<?php
	                				endforeach;
	                			endif;
	                	?>
	                	</div>
	                	<?php
	                		endforeach;
?>