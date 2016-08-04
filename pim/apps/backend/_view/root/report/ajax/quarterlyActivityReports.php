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
<h3 class='panel panel-heading' style="margin-top: 0px; margin-bottom: 0px;">Generated Reports for <?php echo !$quarter? "": model::load("helper")->quarter(1,$quarter).' '.$year;?></h3>
<div id='ajax-report-errors' style="display: none;">
	<?php if($pending_report):?>
	<div class='alert alert-danger'>
		Processing reports for quarter <?php echo model::load("helper")->quarter(2,$pending_report['reportQuarterlyName']) .' of '.$pending_report['reportQuarterlyYear'];?>
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
			<td><?php echo $report['reportQuarterlyStatus'] == '0' ? '-' : date('d-F-Y g:i A', strtotime($report['reportQuarterlyUpdatedDate'])).' ('.$humanFilesize($report['reportQuarterlyZipSize']).')';?></td>
			<td><?php echo $report['reportQuarterlyStatusState'];?>
			<?php if($report['reportQuarterlyStatusState'] == 'completed'):?>
				<a onclick='report.downloadIncrement(<?php echo $report['reportQuarterlyID'];?>);' href='<?php echo url::asset('backend/reports/quarterly-activities/'.$report['reportQuarterlyZipName']);?>' class='fa fa-download'></a>
			<?php endif;?>
			</td>
			<td style="text-align: center;">
				<?php if($report['reportQuarterlyStatus'] == 0):?>
					<?php echo $report['reportQuarterlySiteCompleted'];?>/<?php echo $report['reportQuarterlySiteTotal'];?>
				<?php else:?>
					<?php echo $report['reportQuarterlySiteCompleted'];?>
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