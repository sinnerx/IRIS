<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript">
var site = new function()
{
	var context	= this;
	this.overview = new function()
	{
		this.updateDate = function()
		{
			var month = $("#selectMonth").val();
			var year = $("#selectYear").val();
			var cluster	= $("#clusterID").val() != ""?"&cluster="+$("#clusterID").val():"";
			//var cluster	= $("#clusterID").val() != ""?"&cluster="+$("#clusterID").val():"";
			// window.location.href = pim.base_url+"kpi/kpi_overview/1/"+$("#selectMonth").val()+"/"+$("#selectYear").val();

			window.location.href = pim.base_url+'kpi/kpi_overview?month='+month+'&year='+year+'&cluster='+cluster;
		}

		
	};
}
</script>
<style type="text/css">
	
.completed
{
	color: green;
}

.incomplete
{
	color: red;
}

</style>
<head>
	<title></title>
	<style type="text/css">
	body {
		/*font: normal 14px/21px Arial, serif;*/
	}
	.example {
		float: left;
		width: 40%;
		margin: 5%;
	}
	table {
		/*font-size: 1em;*/
		/*border-collapse: collapse;*/
		margin: 0 auto
	}
	table, th, td {
		/*border: 1px solid #999;
		padding: 8px 16px;
		text-align: left;*/
	}
	
	th {
		background: #f4f4f4;
		cursor: pointer;
	}

	th:hover,
	th.sorted {
		background: #d4d4d4;
	}

	th.no-sort,
	th.no-sort:hover {
		background: #f4f4f4;
		cursor: not-allowed;
	}

	th.sorted.ascending:after {
		content: "  \2191";
	}

	th.sorted.descending:after {
		content: " \2193";
	}

	.disabled {
		opacity: 0.5;
	}


	
	label {

    	font-size: 13px;
    	font-weight: bold;
	}
	.input-s-sm {

		width: 180px;
	}

	</style>
<h3 class='m-b-xs text-black'>
	Key Perfomance Index
</h3>
<div class='well well-sm'>
	Choose month, year and cluster.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>		
		<div class="form-group" style="margin-left:10px">
			<?php echo form::select("selectMonth",model::load("helper")->monthYear("month"),"onchange='site.overview.updateDate();'",$month);?>
			
			<?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='site.overview.updateDate();'",$year);?>
			
			<?php echo form::select("clusterID",$clusterR,"class='input-sm form-control input-s-sm inline v-middle' onchange='site.overview.updateDate();'",request::get("cluster"),"[ALL CLUSTER]");?>			
		</div>					
	</div>
</div>

<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			  KPI Report for <?php echo $month; ?> / <?php echo $year; ?>
		</div>
		
		<div class="table-responsive">
			<table class='table' id="ntable" border='0'>
				<thead>
				<tr>
					<th></th> 		
					<th>Site</th> 				
					<th>Event</th>	
					<th>Entrepreneurship Class</th>	
					<th>Entrepreneurship Program</th>
					<th>Training (hours)</th>	
					<th>Active member (Login)</th>
					<th>Population</th>	
					<th>Number of Members</th>	
					<th>Operations</th>	
					<th>Pi1M Audit Score</th>	
					<th>KDB Session</th>	
					<th>KDB Pax</th>	
				</tr>
				</thead>
				<tbody>
				<?php $completionClass = function($total, $type) use($max)
				{
					if($total >= $max[$type])
						return 'class="completed"';
					else
						return 'class="incomplete"';
				};?>
				<?php $no = 1;?>
				<?php foreach($sites as $siteID => $row):?>
				<?php $report = $total[$siteID];?>
					<tr>
						<td><?php echo $no++;?>.</td>
						<td><?php echo $row['siteName'];?></td>
						<td <?php echo $completionClass($report['event'], 'event');?>>
							<?php echo $report['event'];?> / <?php echo $max['event'];?>
						</td>
						<td <?php echo $completionClass($report['entrepreneurship_class'], 'entrepreneurship_class');?>>
							<?php echo $report['entrepreneurship_class'];?> / <?php echo $max['entrepreneurship_class'];?>
						</td>
						<td <?php echo $completionClass($report['entrepreneurship_sales'], 'entrepreneurship_sales');?>>
							<?php echo $report['entrepreneurship_sales'];?> / <?php echo $max['entrepreneurship_sales'];?>
						</td>
						<td <?php echo $completionClass($report['training_hours'], 'training_hours');?>>
							<?php echo round($report['training_hours'], 2);?> / <?php echo $max['training_hours'];?>
						</td>
						<td <?php echo $completionClass($report['active_member_percentage'], 'active_member_percentage');?>>
							<?php echo number_format($report['active_member_percentage'], 2, '.', '');?>%
						</td>
						<td>
						<?php echo $report['population'];?>
							
						</td>
						<td>
						<?php echo $report['total_members'];?>
							
						</td>
						<td><?php echo '';?></td>
						<td><?php echo $report['auditScore'];?></td>
						<td><?php echo $report['kdbSession'];?> / <?php echo $max['kdb_sessions']; ?></td>
						<td><?php echo $report['kdbPax'];?> / <?php echo $max['kdb_pax']; ?></td>

					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
		<script src="<?php //echo url::asset("backend/js/sorting.js"); ?>"></script>
		<script src="<?php //echo url::asset("backend/js/jquery.tablesort.min.js"); ?>"></script>
		<script src="<?php echo url::asset("backend/js/jquery.tablesorter.min.js"); ?>"></script>
		<script type="text/javascript">

	$(function() {

		// var table = $('<table class="ex-2"></table>');
		// table.append('<thead><tr><th class="number">Number</th></tr></thead>');
		// var tbody = $('<tbody></tbody>');

		// for(var i = 0; i<6000; i++) {
		// 	tbody.append('<tr><td>' + Math.floor(Math.random() * 100) + '</td></tr>');
		// }
		// table.append(tbody);
		// $('.example.ex-2').append(table);

		//$('table').tablesort().data('tablesort');
		
		$("#ntable").tablesorter({
			headers : { 
				0 : { sorter: false },
				2 : { sorter: 'digit' },
				3 : { sorter: 'digit' },
				4 : { sorter: 'digit' },
				5 : { sorter: 'digit' },
				6 : { sorter: 'digit' },
				7 : { sorter: 'digit' }
			}
		});

		// $('thead th.number').data('sortBy', function(th, td, sorter) {
		// 	return parseInt(td.text(), 10);
		// });

		// Sorting indicator example
		// $('table.ex-2').on('tablesort:start', function(event, tablesort) {
		// 	$('table.ex-2 tbody').addClass("disabled");
		// 	$('.ex-2 th.number').removeClass("sorted").text('Sorting..');
		// });

		// $('table.ex-2').on('tablesort:complete', function(event, tablesort) {
		// 	$('table.ex-2 tbody').removeClass("disabled");
		// 	$('.ex-2 th.number').text('Number');
		// });


	});
</script>

	</div>
</div>