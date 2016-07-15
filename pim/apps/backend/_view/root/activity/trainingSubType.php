<script type="text/javascript">
	
var trainingType = new function()
{
	this.deleteConfirm = function()
	{
		return confirm('This will deactivate this type, although it will still be visible for the existing training, but no longer enabled for selection.');
	}
}

</script>
<style type="text/css">
	
.inactive
{
	opacity: 0.5;
}

</style>
<h3 class='m-b-xs text-black'>
Sub Training
</h3>
<div class='well well-sm'>
Listing sub type of training. <a href='<?php echo url::base("activity/trainingSubTypeAdd");?>' class='label label-primary'>Add Sub Type of Training.</a>
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-6'>
		<section class='panel panel-default'>
			<header>
				
			</header>
			<div class='table-responsive' id='category-list'>
				<table class='table'>
					<tr>
						<th width="15px">No.</th>
						<th>Sub Type of Training</th>
						<th>Type of Training</th>
						<th>Total Training</th>
						<th width="60px"></th>
					</tr>
					<?php if($res_training_type):?>
					<?php $no = 1;?>
					<?php foreach($res_training_type as $row):?>
					<?php $inactive = $row['trainingSubTypeStatus'] == 0 ? 'class="inactive"': "";?>
						<tr <?php echo $inactive;?> >
							<td><?php echo $no;?>.</td>
							<td><?php echo $row['trainingSubTypeName'];?></td>
							<td><?php echo $row['trainingTypeName'];?></td>
							<td><?php echo $res_training[$row['trainingSubTypeID']]?count($res_training[$row['trainingSubTypeID']]):0;?></td>
							<td>
								<?php if($row['trainingSubTypeStatus'] == 1):?>
									<a href='<?php echo url::base("activity/trainingSubTypeEdit/".$row['trainingSubTypeID']);?>' class='fa fa-edit'></a>
									<a href='<?php echo url::base("activity/trainingSubTypeDelete/".$row['trainingSubTypeID']);?>' class='i i-cross2' onclick='return trainingType.deleteConfirm();'></a>
								<?php endif;?>
							</td>
						</tr>
					<?php $no++;?>
					<?php endforeach;?>
					<?php else:?>
					<tr>
						<td colspan="3" style="text-align:center;">No type of was added yet.</td>
					</tr>
					<?php endif;?>
				</table>
			</div>
		</section>
	</div>
</div>