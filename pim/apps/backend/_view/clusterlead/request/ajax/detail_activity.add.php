<div class='request-info'>
	New Activity : <u><?php echo $row['activityName'];?></u>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th width="80px">Date</th><td style='border-left:1px dashed #bbdfac;'><?php echo model::load("helper")->dateRangeLabel(Array($row['activityStartDate'],$row['activityEndDate']));?></td>
					<th width="150px">Participation</th><td><?php echo model::load("activity/activity")->participationName($row['activityParticipation']);?></td>
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
					<td colspan="2">
						<b>Location</b><br>
						<?php 
						$address	= $row['activityAddressFlag'] == 1?"< site address >":$row['activityAddress'];
						echo $address;?>
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