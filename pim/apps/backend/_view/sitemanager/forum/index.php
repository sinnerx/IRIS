<h3 class='m-b-xs text-black'>Forum Management</h3>
<div class='well well-sm'>
A module to manage your forum. You may create categories, and etc.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-6'>
		<div class='row-wrapper'>
		<h3>
			Latest topic
		</h3>
		</div>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<th>No.</th><th>Thread</th><th>Category</th><th>Time</th>
				</tr>
				<?php
					$no = 1;
					if($res_latestthreads):?>
					<?php foreach($res_latestthreads as $row):?>
					<?php
					$name		= $row['forumThreadTitle'];
					$category	= $res_category_one[$row['forumCategoryID']]['forumCategoryTitle']?:"-";
					$time		= date("g:i A, d-m-Y",strtotime($row['forumThreadCreatedDate']));
					?>
					<tr>
						<td><?php echo $no++;?>.</td>
						<td><?php echo $name;?></td>
						<td><?php echo $category;?></td>
						<td><?php echo $time;?></td>
					</tr>
					<?php endforeach;?>
				<?php else:?>
				<tr>
					<td colspan='4' style="text-align:center;">There seems to be no topic at all.</td>
				</tr>
				<?php endif;?>
			</table>
		</div>
	</div>
	<div class='col-sm-6'>
		<div class='row-wrapper'>
			<h3>
				Forum Categories
				<button class='badge bg-primary pull-right' type='button' onclick='window.location.href = pim.base_url+"forum/addCategory"'>Add Category</button>
			</h3>
		</div>
		<div class='table-responsive bg-white'>
		<table class='table'>
			<tr>
				<th width="50">No.</th><th>Category Name</th><th>Access Level</th><th width="56px"></th>
			</tr>
			<?php
			if($res_category_one):
			## loop the first level.
			$no	= 1;
			foreach($res_category_one as $row_cat):
			$row_requestdata	= $requestData[$row_cat['forumCategoryID']]?:null;

			$catName			= $row_requestdata['forumCategoryTitle']?:$row_cat['forumCategoryTitle'];
			$iconApprovalStatus	= model::load("template/icon")->status($requestData[$row_cat['forumCategoryID']]?4:$row_cat['forumCategoryApprovalStatus']);
			$iconApprovalStatus	= $row_cat['siteID'] == 0?"":$iconApprovalStatus;
			$accessLevel		= model::load("forum/category")->accessLevel($row_requestdata['forumCategoryAccess']?:$row_cat['forumCategoryAccess']);
			?>
			<tr>
				<td><?php echo $no++;?>.</td>
				<td><?php echo $catName;?></td>
				<td><?php echo $accessLevel;?></td>
				<td>
				<?php if($row_cat['siteID'] != 0):?>
				<a href='<?php echo url::base("forum/updateCategory/$row_cat[forumCategoryID]");?>' class='fa fa-edit'></a>
				<?php endif;?>
				<?php echo $iconApprovalStatus;?></td>
			</tr>

			<?php endforeach;?>
			<?php else:?>
			<tr>

				<td colspan="3" style="text-align:center;">This site appear to have to categories at all.</td>
			</tr>
			<?php endif;?>
		</table>
		</div>
	</div>
	
</div>