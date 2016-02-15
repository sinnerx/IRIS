<script type="text/javascript">
	
var report = new function()
{
	this.generate = function()
	{
		var year	= $("#year").val();
		var month	= $("#month").val();
		window.location.href = pim.base_url+"report/activityReport/"+year+"/"+month;
	}
	this.updateDate = function()
	{
		window.location.href = pim.base_url+"report/activityReport/"+$("#year").val()+"/"+$("#month").val();
	}
}

</script>
<h3 class='m-b-xs text-black'>
	Monthly Activities
</h3>
<div class='well well-sm'>
	Generate Pi1M monthly activities report.
</div>
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
					 <td></td><td>: <input type='button' class='btn btn-primary' onclick='report.generate();' value='SUBMIT' /></td> 
					<!-- <td></td><td>: <input type='button' class='btn btn-primary'  value='SUBMIT' /></td> -->
				</tr>

	</table>


		<table class='table'>
			<tr>
				<th width='15px'>No.</th>				
				<th>File Name</th>
				<th>Download</th>
			</tr>


		<?php 	$i = 1;
				
				$siteName = authData ('site.siteName');




			foreach($data['list'] as $no=>$report)
			{

								


				$dt = new DateTime($report['activityStartDate']);
				$date = $dt->format('dmY');
				$articleName =	$report['articleName'];
				$articleID = $report['articleID'];
			
				 $fileName = $siteName." - ".$date." - ".$articleName;


		?>	
			
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $fileName; ?></td>
				<td><a class="btn btn-download" style="margin-left:30px" 
					href="<?php echo url::base('report/generateActivityReport');?>?articleID=<?php echo $articleID; ?>" role="button"><i class="fa fa-download"></a></td>
					
			</tr>
			
	<?php 	}	?>

		</table>





		
		</div>
	</div>
</div>