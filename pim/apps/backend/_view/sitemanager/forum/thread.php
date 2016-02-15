<h3 class='m-b-xs text-black'>Forum Management : <?php echo $row['forumCategoryTitle'];?> / <?php echo $row['forumThreadTitle'];?></h3>
<div class='well well-sm'>
You may move this topic to another category.
</div>
<?php echo flash::data();?>
<form method='post'>
	<div class='row'>
		<div class='col-sm-5'>
		<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label>Change Category</label>
					<div class='row'>
						<div class='col-sm-12'>
							<?php echo flash::data("forumCategoryID");?>
							<?php echo form::select("forumCategoryID",$forumCategories,"class='form-control'",$row['forumCategoryID']);?>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-12'>
					<input type='submit' value='Update' class='btn btn-primary pull-right' />
					</div>
				</div>
			</div>
		</section>
		</div>
		<!-- <div class='col-sm-7'>
			<div class='table-responsive'>
				<table class='table'>
					<tr>
						<td>Topic creator</td><td>: </td>
					</tr>
					<tr>
						<td>Po</td>
					</tr>
				</table>
			</div>
		</div> -->
	</div>
</form>