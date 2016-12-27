<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>
<script type="text/javascript">
	
var report = new function()
{
	this.generate = function()
	{

		if($("#startDate").val() != '' && $('#endDate').val() != '')
		{

			window.location.href = pim.base_url+'report/generateMasterListing?start='+$("#startDate").val()+'&end='+$("#endDate").val();
		}
		else
		{
			var month	= $("#month").val();
			var year	= $("#year").val();
			
			window.location.href = pim.base_url+"report/generateMasterListing/"+month+"/"+year;
		}
	}
}

</script>
<h3 class='m-b-xs text-black'>
	Master Listing
</h3>
<div class='well well-sm'>
	Generate master listing, based on active data.
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<td width='150px'>Active Data By End Of</td><td>: 
					<?php echo form::select("month",model::load("helper")->monthYear("month"),null,date("m"),"[Month]");?>
					<?php echo form::select("year",model::load("helper")->monthYear("year"),null,date("Y"));?> for 1 year.
					</td>
				</tr>
				<tr>
					<td>Or by date range</td>
					<td>:
						<input name="startDate" id="startDate" type="text" data-date-format="yyyy-mm-dd" class="datepicker-input"> until
						<input name="endDate" id="endDate" type="text" data-date-format="yyyy-mm-dd" class="datepicker-input">
					</td>
				</tr>
				<!-- <tr>
					<td>For</td>
					<td>: 
					<?php echo form::select("totalMonth",Array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12),null,12);?> Months
					</td>
				</tr> -->
				<tr>
					<td>Generate report</td><td>: <input type='button' class='btn btn-primary' onclick='report.generate();' value='GENERATE' /></td>
				</tr>
			</table>
		</div>
	</div>
</div>