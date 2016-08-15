<h3 class='m-b-xs text-black'>
	Update category
</h3>
<div class='well well-sm'>
	Update forum category
</div>
<?php echo flash::data("_main",$hasRequest?"<div class='alert alert-danger'>Waiting for clusterlead approval</div>":null);?>
<div class='row'>
	<form method='post'>
	<div class='col-sm-5'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<td width="150px">Category Title</td><td><?php echo form::text("forumCategoryTitle","class='form-control'",$row['forumCategoryTitle']);?></td>
				</tr>
				<tr>
					<td>Category Description</td><td><?php echo form::textarea("forumCategoryDescription","style='height:80px;' class='form-control'",$row['forumCategoryDescription']);?></td>
				</tr>
				<tr>
					<?php
					$accessR	= model::load("forum/category")->accessLevel();
					?>
					<td>Access</td><td><?php echo form::select("forumCategoryAccess",$accessR,null,$row['forumCategoryAccess']);?>
				</tr>
			</table>
			<?php echo form::submit("Save and Approve","class='btn btn-primary'");?>
		</div>
	</div>
	</form>

</div>