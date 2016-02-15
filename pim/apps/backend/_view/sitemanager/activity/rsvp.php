<h3 class='m-b-xs text-black'>
Activity : RSVP
</h3>
<div class='well well-sm'>
	Manage RSVP/Attendance Data <u>only</u> for the occurred activities.
</div>
<section class='panel panel-default'>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width="10px">No.</th>
				<th width='50px'>Type</th>
				<th width="40%">Activity</th>
				<th></th>
				<th colspan="2" width="20%" style='text-align:center;'>Date</th>
				<th width='20px'></th>
			</tr>
			<?php
			$no = 1;
			$typeR	= model::load("activity/activity")->type();

			if($res_occured_activity):
			foreach($res_occured_activity as $row):
			$type			= $typeR[$row['activityType']];
			$activityName	= $row['activityName'];
			$startDate		= date("d F Y",strtotime($row['activityStartDate']));
			$endDate		= date("d F Y",strtotime($row['activityEndDate']));

			$url			= url::base("ajax/activity/attendees/$row[activityID]");
				?>
			<tr>
				<td><?php echo $no++;?>.</td>				
				<td><?php echo $type;?></td>
				<td><?php echo $activityName;?></td>
				<td></td>
				<td><?php echo $startDate;?></td>
				<td><?php echo $endDate;?></td>
				<td><a href='<?php echo $url;?>' class='fa fa-list' data-toggle="ajaxModal"></a></td>
			</tr>
			<?php
			endforeach;
			else:?>
			<tr>
				<td style='text-align:center;' colspan="7">No activities happened yet.</td>
			</tr>
			<?php endif;?>
		</table>
	</div>
</section>