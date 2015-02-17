<script type="text/javascript">
	
var report = new function()
{
	this.generate = function()
	{
		var year	= $("#year").val();
		var month	= $("#month").val();
		window.location.href = pim.base_url+"report/generateAllActivityReport/"+year+"/"+month;
	}
}

</script>
<h3 class='m-b-xs text-black'>
	Monthly Activities
</h3>
<div class='well well-sm'>
	Generate Pi1M monthly activities report.
</div>

<?php echo flash::data();?>

<div class='row'>
	<div class='col-sm-6'>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<td width="150px">Month</td><td>: <?php echo form::select("month",model::load("helper")->monthYear("month"),null,date("m"));?></td>
				</tr>
				<tr>
					<td>Year</td><td>: <?php echo form::select("year",model::load("helper")->monthYear("year"),null,date("Y"));?></td>
				</tr>
				<tr>
					 <td>Generate report</td><td>: <input type='button' class='btn btn-primary' onclick='report.generate();' value='GENERATE' /></td> 
					
				</tr>

			</table>


	




		
		</div>
	</div>
</div>