<div class='request-info'>
	New / Delete Activity : <u><?php echo $row['activityName'];?></u>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th width="80px">Date</th>
					<td style='border-left:1px dashed #bbdfac;'>
						<?php
						echo model::load("helper")->dateRangeLabel(Array($row['activityStartDate'],$row['activityEndDate']));?></td>
					<th width="150px">Participation</th><td style='border-left:1px dashed #bbdfac;'><?php echo model::load("activity/activity")->participationName($row['activityParticipation']);?></td>
					<th>Type of activity</th><td><?php echo model::load("activity/activity")->type($row['activityType']);?></td>
				</tr>
				<tr>
					<td colspan='2' rowspan="2" style="padding-top:0px;">
						<?php
						if($activityDate):
						echo "<table class='activityDate' style='width:100%;'>";
						echo "<tr><th rowspan='2' style='text-align:left;'>Date</th><th style='text-align:center;border-top:0px;' colspan='2'>Time</th></tr>";
						echo "<tr><th align='center'>Start</th><th>End</th></tr>";
						foreach($activityDate as $row_date)
						{
							$date	= $row_date['activityDateValue'];
							$start	= $row_date['activityDateStartTime'];
							$end	= $row_date['activityDateEndTime'];

							echo "<tr>";
							echo "<td>".date("j F Y",strtotime($date))."</td>";
							echo "<td>".date("g:i A",strtotime($start))."</td>";
							echo "<td>".date("g:i A",strtotime($end))."</td>";
							echo "</tr>";
						}
						echo "</table>";
						endif;
						?>
					</td>
					<td style='border-left:1px dashed #bbdfac;' colspan="2">
						<b>Location</b><br>
						<?php
						$address	= $row['activityAddressFlag'] == 1?"< site address >":$row['activityAddress'];
						echo $address;?>
					</td>
					<td rowspan="2" colspan="2">
						<!-- type specific detail -->
						<?php
						# event.
						if($row['activityType'] == 1):?>
						<div><b>Event Type</b></div>
						<div><?php echo model::load("activity/event")->type($row['eventType']);?></div>

						<?php else:?>
						<div><b>Type of training</b></div>
						<div><?php echo model::load("activity/training")->type($row['trainingType']);?></div>
						<?php endif;
						?>
						<?php
						if($row['activityType'] == 1):?>
						<div><b>Max pax</b></div>
						<div><?php echo $row['eventMaxPax'] == 0?"No-limit":$row['eventMaxPax'];?></div>

						<?php else:?>
						<div><b>Max pax</b></div>
						<div><?php echo $row['trainingMaxPax'] == 0?"No-limit":$row['trainingMaxPax'];?></div>
						<?php endif;
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>Description</b><br>
						<?php echo !$row['activityDescription']?"-":nl2br($row['activityDescription']);?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>