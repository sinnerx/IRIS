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

	$(".i-cross2").click(function()
	{
		if(confirm("Are you sure?"))
		{
			$.ajax({type:"GET",url:$(this).attr("href")}).done(function(res)
			{
				var res = $.parseJSON(res);

				if(res[0])
				{
					window.location.href = "";
				}
				else
				{
					alert(res[1]);
				}	
			});
		}
	});
});

</script>
<h5 class='m-b-xs text-black'>
Category edit : <?php echo $row['categoryName'];?> <span href='<?php echo url::base("article/category_delete/$row[categoryID]");?>' style='cursor:pointer;' class='i i-cross2' title='Delete this category'></span>
</h5>
<div style="padding:5px;">
You can change name, or delete (but only without linked to any article)
</div>
<div class='row'>
	<form method="post" id='myform' action="<?php echo url::base("article/category_edit/$row[categoryID]");?>">
	<div class='col-sm-6'>
		<div class="input-group">
		<?php echo form::text("categoryName","class='form-control'",$row['categoryName']);?>
			<div class="input-group-btn">
				<input value='Change Name' type="submit" class="btn btn-default" />
			</div>
		</div><!-- /.input-group -->
	</div>
	</form>
</div>