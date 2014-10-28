<h3 class='m-b-xs text-black'>
Training
</h3>
<div class='well well-sm'>
Listing type of training. <a href='<?php echo url::base("activity/trainingTypeAdd");?>' class='label label-primary'>Add Type of Training.</a>
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
						<th>Type of Training</th>
						<th>Total Training</th>
						<th width="24px"></th>
					</tr>
					<?php if($res_training_type):?>
					<?php $no = 1;?>
					<?php foreach($res_training_type as $row):?>
						<tr>
							<td><?php echo $no;?>.</td>
							<td><?php echo $row['trainingTypeName'];?></td>
							<td><?php echo $res_training[$row['trainingTypeID']]?count($res_training[$row['trainingTypeID']]):0;?></td>
							<td><a href='<?php echo url::base("activity/trainingTypeEdit/".$row['trainingTypeID']);?>' class='fa fa-edit'></a></td>
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