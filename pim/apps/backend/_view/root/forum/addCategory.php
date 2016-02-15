<h3 class='m-b-xs text-black'>
	Add General Category
</h3>
<div class='well well-sm'>
	Add a new general category for forum.
</div>
<?php echo flash::data();?>
<div class='row'>
	<form method='post'>
	<div class='col-sm-5'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<td width="150px">Category Title
					<?php echo flash::data("forumCategoryTitle");?>
					</td><td><?php echo form::text("forumCategoryTitle","class='form-control'");?>
					</td>
				</tr>
				<tr>
					<td>Category Description</td><td><?php echo form::textarea("forumCategoryDescription","style='height:80px;' class='form-control'");?></td>
				</tr>
				<tr>
					<td>Access Level
					<?php echo flash::data("forumCategoryAccess");?>
					</td><td><?php echo form::select("forumCategoryAccess",model::load("forum/category")->accessLevel());?></td>
				</tr>
			</table>
			<?php echo form::submit("Submit","class='btn btn-primary'");?>
		</div>
	</div>
	</form>

</div>