<?php

$humanFilesize = function($bytes, $decimals = 2)
{
  $sz = strtolower('BKMGTP');
  $factor = floor((strlen($bytes) - 1) / 3);
  $symbol = @$sz[$factor];
  $symbol = $symbol == 'b' ? 'b' : $symbol.'b';
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .' '. $symbol;
};

?>
<h3 class='panel panel-heading' style="margin-top: 0px; margin-bottom: 0px;">Generated Reports for <?php echo date('F', strtotime('2016-'.$month.'-01')).' '.$year;?></h3>
<div id='ajax-report-errors' style="display: none;">
	<?php if($pending_report):?>
	<div class='alert alert-danger'>
		Processing reports for month <?php echo $pending_report['reportMonthlyMonth'].'/'.$pending_report['reportMonthlyYear'];?>
	</div>
	<?php endif;?>
	<?php if($totalApprovalPendingReport > 0):?>
	<div class='alert alert-danger'>
	<?php echo $totalApprovalPendingReport;?> reports are pending approval and not included in this report generation.<br>
	Please notify all Custer Lead to verify & approve all blog posts related to Event Report
	</div>
	<?php endif;?>
	<?php if($totalNonrecentReport > 0):?>
	<div class='alert alert-danger'>
	<?php echo $totalNonrecentReport;?> reports are not recent, and are pending for approval.
	</div>
	<?php endif;?>
</div>
<div class='table-responsive bg-white'>
	<table class='table'>
		<tr>
			<th>Date Generated</th>
			<th>Status</th>
			<th style="text-align: center;">Total</th>
		</tr>
		<?php foreach($reports as $report):?>
		<tr>
			<td><?php echo $report['reportMonthlyStatus'] == '0' ? '-' : date('d-F-Y g:i A', strtotime($report['reportMonthlyUpdatedDate'])).' ('.$humanFilesize($report['reportMonthlyZipSize']).')';?></td>
			<td><?php echo $report['reportMonthlyStatusState'];?>
			<?php if($report['reportMonthlyStatusState'] == 'completed'):?>
				<a onclick='report.downloadIncrement(<?php echo $report['reportMonthlyID'];?>);' href='<?php echo url::asset('backend/reports/monthly-activities/'.$report['reportMonthlyZipName']);?>' class='fa fa-download'></a>
			<?php endif;?>
			</td>
			<td style="text-align: center;">
				<?php if($report['reportMonthlyStatus'] == 0):?>
					<?php echo $report['reportMonthlyArticleCompleted'];?>/<?php echo $report['reportMonthlyArticleTotal'];?>
				<?php else:?>
					<?php echo $report['reportMonthlyArticleCompleted'];?>
				<?php endif;?>
			</td>
		</tr>
		<?php endforeach;?>
		<?php if(count($reports) == 0):?>
		<tr>
			<td colspan="3" style="text-align: center;">There's currently no report generated yet</td>
		</tr>
		<?php endif;?>
	</table>
</div>
<script type="text/javascript">

</script>