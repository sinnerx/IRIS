<h3 class='m-b-xs text-black'>
Activity : Training
</h3>
<div class='well well-sm'>
List of training activities of your site.
</div>
<section class='panel panel-default'>
	<div class='row'>
	<a style="position:relative;right:10px;" href='<?php echo url::base("activity/add#event");?>' class='btn btn-primary pull-right'>Add New Training</a>
	</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width="10px">No.</th>
				<th width="30%">Training Name</th>
				<th width="10%">Type</th>
				<th>Participation</th>
				<th>Max Pax</th>
				<th colspan="2" width="20%" style='text-align:center;'>Date</th>
				<th width='80px'></th>
			</tr>
			<?php 
			if($res_training):
			$requestData	= model::load("site/request")->replaceWithRequestData("activity.update",array_keys($res_training));
			$no	= pagination::recordNo();
			foreach($res_training as $row):
			$id			= $row['activityID'];
			if(isset($requestData[$id]))
			{
				$row 							= model::load("helper")->replaceArray($row,$requestData[$id]);
				$row['activityApprovalStatus']	= 4; ## just a decoy.
			}

			$status		= $row['activityApprovalStatus'];
			$startDate	= $row['activityStartDate'];
			$endDate	= $row['activityEndDate'];
			$partic		= $activity->participationName($row['activityParticipation']);
			$maxPax		= $row['trainingMaxPax'] == 0?"No limit":$row['trainingMaxPax'];

			$statusIcon	= model::load("template/icon")->status($status);
			?>
			<tr>
				<td><?php echo $no++;?>.</td>
				<td><?php echo $row['activityName'];?></td>
				<td><?php echo $training->type($row['trainingType']);?></td>
				<td><?php echo $partic;?></td>
				<td><?php echo $maxPax;?></td>
					<?php if($startDate == $endDate):?>
					<td colspan="2" style='text-align:center;'><?php echo date("d/m/Y",strtotime($startDate));?></td>
					<?php else:?>
					<td style='text-align:right;border-right:1px solid #f2f4f8;'><b>From</b> <?php echo date("d/m/Y",strtotime($startDate));?></td>
					<td><b>To</b> <?php echo date("d/m/Y",strtotime($endDate));?></td>
					<?php endif;?>
				<td>
					<?php echo $statusIcon;?>
					<?php if($status != 2):?>
					<a href='<?php echo url::base("activity/view/training/$id");?>' class='fa fa-search'></a>
					<a href='<?php echo url::base("activity/edit/$id");?>' class='fa fa-edit'></a>
					<?php endif;?>
				</td>
			</tr>
			<?php 
			endforeach;
			else:?>
			<tr>
				<td colspan="6" align="center">No event was added at all.</td>
			</tr>
			<?php endif;?>
		</table>
	</div>
	<footer class='panel-footer'>
		<div class="row">
			<div class='col-sm-12'>
			<?php echo pagination::link();?>
			</div>
		</div>
	</footer>
</section>