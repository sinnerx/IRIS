<script type="text/javascript">
	
$(document).ready(function()
{
	pim.ajax.formify("#myform",null,function(txt)
	{
		if(txt)
		{
			window.location.href = "";
		}
	});
});

</script>
<h5 class='m-b-xs text-black'>
Add category
</h5>
<div style="padding:5px;">
Please fill the category name.
</div>
<div class='row'>
	<form method="post" id='myform' action='<?php echo url::base("article/category_add");?>'>
	<div class='col-sm-6'>
		<div class="input-group">
		<?php echo form::text("categoryName","class='form-control'");?>
			<div class="input-group-btn">
				<input value='Add Category' type="submit" class="btn btn-default" />
			</div>
		</div><!-- /.input-group -->
	</div>
	</form>
</div>