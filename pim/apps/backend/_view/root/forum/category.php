<h3 class='m-b-xs text-black'>Forum General Categories</h3>
<div class='well well-sm'>
Manage general categories for forum here.
</div>
<div class='row'>
	<div class='col-sm-6'>
		<div class='row-wrapper'>
			<h3>
				General Categories
				<button class='badge bg-primary pull-right' type='button' onclick='window.location.href = pim.base_url+"forum/addCategory"'>Add Category</button>
			</h3>
		</div>
		<div class='table-responsive bg-white'>
		<table class='table'>
			<tr>
				<th width="50">No.</th><th>Category Name</th><th>Access Level</th><th width="56px"></th>
			</tr>
			<?php
			if($res_category):
			## loop the first level.
			$no	= 1;
			foreach($res_category as $row_cat):
			$catName			= $row_requestdata['forumCategoryTitle']?:$row_cat['forumCategoryTitle'];
			$iconApprovalStatus	= model::load("template/icon")->status($requestData[$row_cat['forumCategoryID']]?4:$row_cat['forumCategoryApprovalStatus']);
			$accessLevel		= model::load("forum/category")->accessLevel($row_requestdata['forumCategoryAccess']?:$row_cat['forumCategoryAccess']);
			?>
			<tr>
				<td><?php echo $no++;?>.</td>
				<td><?php echo $catName;?></td>
				<td><?php echo $accessLevel;?></td>
				<td>
					<a href='<?php echo url::base("forum/updateCategory/$row_cat[forumCategoryID]");?>' class='fa fa-edit'></a>
				</td>
			</tr>

			<?php endforeach;?>
			<?php else:?>
			<tr>

				<td colspan="3" style="text-align:center;">No general categories yet.</td>
			</tr>
			<?php endif;?>
		</table>
		</div>
	</div>
</div>