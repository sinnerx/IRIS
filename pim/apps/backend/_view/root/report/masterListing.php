<script type="text/javascript">
	
var report = new function()
{
	this.generate = function()
	{
		var month	= $("#month").val();
		window.location.href = pim.base_url+"report/generateMasterListing/"+month;
	}
}

</script>
<h3 class='m-b-xs text-black'>
	Master Listing
</h3>
<div class='well well-sm'>
	Generate Pi1M master listing, based on active data.
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<td width='150px'>Active Data Since</td><td>: <?php echo form::select("month",Array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12),null,5,"[Month]");?> Month(s) ago.</td>
				</tr>
				<tr>
					<td>Generate report</td><td>: <input type='button' class='btn btn-primary' onclick='report.generate();' value='GENERATE' /></td>
				</tr>
			</table>
		</div>
	</div>
</div>