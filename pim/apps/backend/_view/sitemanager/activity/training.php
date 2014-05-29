<h3 class='m-b-xs text-black'>
Activity : Training
</h3>
<div class='well well-sm'>
List of training activities of your site. <a href='<?php echo url::base("activity/add#training");?>'>Add?</a>
</div>
<section class='panel panel-default'>
	<div class='panel-heading'>
		
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
				<th width='58px'></th>
			</tr>
			<?php 
			if($res_training):
			$no	= pagination::recordNo();
			foreach($res_training as $row):

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
					 <a href='#' class='fa fa-search'></a>
				</td>
			</tr>
			<?php 
			endforeach;
			else:?>


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