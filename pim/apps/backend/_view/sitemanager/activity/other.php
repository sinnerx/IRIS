<h3 class='m-b-xs text-black'>
Activity : Others
</h3>
<div class='well well-sm'>
List of other's activities of your site.
</div>
<section class='panel panel-default'>
		<div class='row'>
		<a style="position:relative;right:10px;" href='<?php echo url::base("activity/add#others");?>' class='btn btn-primary pull-right'>Add New Other's Activity</a>
		</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width="10px">No.</th>
				<th width="20%">Activity Name</th>
				<th width='20%'>Participation</th>
				<th>Address</th>
				<th colspan="2" width="20%" style='text-align:center;'>Date</th>
				<th width='80px'></th>
			</tr>
			<?php 
			if($res_other):
			$requestData	= model::load("site/request")->replaceWithRequestData("activity.update",array_keys($res_other));
			$no	= pagination::recordNo();
			foreach($res_other as $row):

			$activityID	= $row['activityID'];
			if(isset($requestData[$activityID]))
			{
				$row 							= model::load("helper")->replaceArray($row,$requestData[$activityID]);
				$row['activityApprovalStatus']	= 4; ## just a decoy.
			}

			$status		= $row['activityApprovalStatus'];
			$startDate	= $row['activityStartDate'];
			$endDate	= $row['activityEndDate'];
			$partic		= $activity->participationName($row['activityParticipation']);

			$address	= $row['activityAddressFlag'] == 1?authData("site.siteInfoAddress"):$row['activityAddress'];

			$statusIcon	= model::load("template/icon")->status($status);
			?>
			<tr>
				<td><?php echo $no++;?>.</td>
				<td><?php echo $row['activityName'];?></td>
				<td><?php echo $partic;?></td>
				<td><?php echo nl2br($address);?></td>
					<?php if($startDate == $endDate):?>
					<td colspan="2" style='text-align:center;'><?php echo date("d/m/Y",strtotime($startDate));?></td>
					<?php else:?>
					<td style='text-align:right;border-right:1px solid #f2f4f8;'><b>From</b> <?php echo date("d/m/Y",strtotime($startDate));?></td>
					<td><b>To</b> <?php echo date("d/m/Y",strtotime($endDate));?></td>
					<?php endif;?>
				<td><?php echo $statusIcon;?>
				<?php if($status != 2):?>
					<a href='<?php echo url::base("activity/view/other/$activityID");?>' class='fa fa-search'></a>
					<a href='<?php echo url::base("activity/edit/$activityID");?>' class='fa fa-edit'></a>
				<?php endif;?>
				</td>
			</tr>
			<?php 
			endforeach;
			else:?>
			<tr>
				<td colspan="6" align="center">No other's activities was added at all.</td>
			</tr>
			<?php endif;?>
		</table>
	</div>
	<div class='panel-footer'>
		<div class='row'>
			<div class='col-sm-12'>
			<?php echo pagination::link();?>
			</div>
		</div>
	</div>
</section>