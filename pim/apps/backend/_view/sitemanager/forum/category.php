<h3 class='m-b-xs text-black'>Forum Management : <?php echo $row_category['forumCategoryTitle'];?></h3>
<div class='well well-sm'>
List of topics created within this category.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-6'>
		<div class='row-wrapper'>
		<h3>
			Topics
		</h3>
		</div>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<th width="10px">No.</th><th>Topic Name</th><th width="100px">Created at</th><th width="24px"></th>
				</tr>
				<?php if($res_threads):?>
				<?php $no = pagination::recordNo();?>
				<?php foreach($res_threads as $row):?>
				<tr>
					<td><?php echo $no;?>.</td>
					<td><?php echo $row['forumThreadTitle'];?></td>
					<td><?php echo dateRangeViewer($row['forumThreadCreatedDate']);?></td>
					<td><a href='<?php echo url::base("forum/thread/$row[forumThreadID]");?>' class='fa fa-gear'></a></td>
				</tr>
				<?php $no++;?>
				<?php endforeach;?>
				<?php else:?>
				<tr>
					<td colspan='4' style="text-align:center;">No record of thread yet.</td>
				</tr>
				<?php endif;?>
			</table>
		</div>
		<div class="pagination-numlink" style="background:white;height:50px;padding-right:10px;padding-top:10px;">
			<?php echo pagination::link();?>
		</div>
	</div>
</div>