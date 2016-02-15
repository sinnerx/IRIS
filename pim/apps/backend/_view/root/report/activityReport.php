<script type="text/javascript">
	
var report = new function()
{
	this.generate = function()
	{
		var year	= $("#year").val();
		var month	= $("#month").val();
		window.location.href = pim.base_url+"report/generateAllActivityReport/"+year+"/"+month;
	}

	this.updateDate = function()
	{
		window.location.href = pim.base_url+"report/getallActivityReport/"+$("#year").val()+"/"+$("#month").val();
	}
}



</script>
<h3 class='m-b-xs text-black'>
	Monthly Activities
</h3>
<div class='well well-sm'>
	Generate Pi1M monthly activities report.
</div>
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
<?php echo flash::data();?>

<div class='row'>
	<div class='col-sm-6'>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<td width="150px">Month</td><td>: <?php echo form::select("month",model::load("helper")->monthYear("month"),'onchange="report.updateDate();"', $month);?></td>
				</tr>
				<tr>
					<td>Year</td><td>: <?php echo form::select("year",model::load("helper")->monthYear("year"),'onchange="report.updateDate();"', $year);?></td>
				</tr>
				<tr>
					 <td>Generate report</td><td>: <input type='button' class='btn btn-primary' onclick='report.generate();' value='GENERATE' /></td> 
					
				</tr>

			</table>


	




		
		</div>
	</div>
</div>