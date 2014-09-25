<h3 class='m-b-xs text-black'>
	Add Forum Category
</h3>
<div class='well well-sm'>
	Add a new forum category.
</div>
<?php echo flash::data();?>
<div class='row'>
	<form method='post'>
	<div class='col-sm-5'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<td width="150px">Category Title</td><td><?php echo form::text("threadCategoryTitle","class='form-control'");?></td>
				</tr>
				<tr>
					<td>Category Description</td><td><?php echo form::textarea("threadCategoryDescription","style='height:80px;' class='form-control'");?></td>
				</tr>
			</table>
			<?php echo form::submit("Submit","class='btn btn-primary'");?>
		</div>
	</div>
	</form>

</div>