<script type="text/javascript">
$( document ).ready(function() {
   // alert(pim.url('ajax/report/quarterlyActivityGenerate/'+year+'/'+quarter));
});


var report = new function()
{
	this.generate = function()
	{
		var year	= $("#year").val();
		var quarter	= $("#quarter").val();

		// window.location.href = pim.base_url+"report/generateAllActivityReport/"+year+"/"+month;

		// pim.loader.start('#main-content-wrapper');
		if(year != '' && quarter != ''){
			this.loader.start();
			console.log(pim.url('ajax/report/quarterlyActivityGenerate/'+year+'/'+quarter));
			$.ajax({type: 'GET', url: pim.url('ajax/report/quarterlyActivityGenerate/'+year+'/'+quarter), dataType: 'json'}).done(function(result)
			{
				console.log(result);
				if(result.status == 'failed')
				{
					alert(result.message);

					report.loader.stop();
				}
				else
				{
					report.listReports().done(function()
					{
						report.loader.stop();
					});
				}
			});
		}
		else{
			alert("Please select both quarter and year respectfully.");
		}
	}

	this.generateYearly = function()
	{
		// var year	= $("#year").val();
		// var quarter	= $("#quarter").val();

		// window.location.href = pim.base_url+"report/generateAllActivityReport/"+year+"/"+month;

		// pim.loader.start('#main-content-wrapper');
		// pim.url('ajax/report/quarterlyActivityGenerate/'+year+'/'+quarter);
		window.location.href = pim.base_url+"ajax/report/yearlyReport";

		// if(year != '' && quarter != ''){
		// 	this.loader.start();
		// 	console.log(pim.url('ajax/report/quarterlyActivityGenerate/'+year+'/'+quarter));
		// 	$.ajax({type: 'GET', url: pim.url('ajax/report/quarterlyActivityGenerate/'+year+'/'+quarter), dataType: 'json'}).done(function(result)
		// 	{
		// 		console.log(result);
		// 		if(result.status == 'failed')
		// 		{
		// 			alert(result.message);

		// 			report.loader.stop();
		// 		}
		// 		else
		// 		{
		// 			report.listReports().done(function()
		// 			{
		// 				report.loader.stop();
		// 			});
		// 		}
		// 	});
		// }
		// else{
		// 	alert("Please select both quarter and year respectfully.");
		// }
	}	

	this.dateChange = function()
	{
		this.loader.start();

		this.listReports().done(function()
		{
			report.loader.stop();
		});
	};



	this.listReports = function()
	{
		var year 	= $('#year').val();
		var quarter = $('#quarter').val();

		return $.ajax({type: 'GET', url: pim.url('ajax/report/quarterlyActivityReports/'+year+'/'+quarter)}).done(function(result)
		{
			$('.generated-reports-container').html(result);

			$('#report-errors').html($("#ajax-report-errors").html());
		});
	}

	this.updateDate = function()
	{
		window.location.href = pim.base_url+"report/getallActivityReport/"+$("#year").val()+"/"+$("#month").val();
	}

	this.downloadIncrement = function(id)
	{

	}

	this.loader = new function()
	{
		this.start = function()
		{
			$('.overlay').show();
		}

		this.stop = function()
		{
			$('.overlay').hide();
		}
	}
}



</script>
<div class='overlay' style="position: absolute; width: 100%; height: 100%; background: white; z-index: 1000; opacity: 0.5; display: none;"></div>
<h3 class='m-b-xs text-black'>
	Quarterly Activities Report
</h3>
<div class='well well-sm'>
	List of generated Pi1M quarterly activity report
</div>
<div id='report-errors'>

</div>
<?php echo flash::data();?>

<div class='row'>
	<div class='col-sm-6'>
		<div class='panel generated-reports-container' style="padding: 0px;">
		</div>
	</div>
	<div class='col-sm-6'>
		<div class='panel' style="padding: 0px;">
		<div class='panel-heading' style="margin-top: 0px;">Generate reports for the selected quarter</div>
		<div class='table-responsive bg-white'>
			<table class='table'>
				<tr>
					<td>Year</td><td>: <?php echo form::select("year",model::load("helper")->monthYear("year"), 'onchange="report.dateChange();"', $year);?></td>
				</tr>
				<tr>
					<?php //var_dump( model::load("helper")->quarter(4,1)[1]); ?>
					<td width="150px">Quarter</td><td>: <?php echo form::select("quarter", model::load("helper")->quarter(1), 'onchange="report.dateChange();"', $quarter);?></td>
				</tr>			
				<tr>
					 <td>Generate report</td><td>: <input type='button' class='btn btn-primary' onclick='report.generate();' value='GENERATE' /></td> 

					 <!--<input type='button' class='btn btn-primary' onclick='report.generateYearly();' value='GENERATE YEARLY' />-->
				</tr>
			</table>
		</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	
report.listReports();

//refresh every 5 secs
setInterval(function()
{
	report.listReports();
}, 5000);

</script>