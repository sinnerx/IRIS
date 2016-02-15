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
			// window.location.href = pim.base_url+"kpi/kpi_overview/1/"+$("#selectMonth").val()+"/"+$("#selectYear").val();

			window.location.href = pim.base_url+'kpi/kpi_overview?month='+month+'&year='+year;
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
<h3 class='m-b-xs text-black'>
	Key Perfomance Index
</h3>
<div class='well well-sm'>
	Choose month and year
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>		
		<div class="form-group" style="margin-left:10px">
			<?php echo form::select("selectMonth",model::load("helper")->monthYear("month"),"onchange='site.overview.updateDate();'",$month);?>
			<?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='site.overview.updateDate();'",$year);?>			
		</div>					
	</div>
</div>

<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			  KPI Report for <?php echo $month; ?> / <?php echo $year; ?>
		</div>
		
		<div class="table-responsive">
			<table class='table ' border='0'>
				<tr>
					<th></th> 		
					<th>Site</th> 				
					<th>Event</th>	
					<th>Entrepreneurship Class </th>	
					<th>Entrepreneurship Program </th>
					<th>Training (hours)</th>	
					<!-- <th>Active member (Login)</th>	 -->
				</tr>
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
						<!-- <td <?php echo $completionClass($report['active_member_percentage'], 'active_member_percentage');?>>
							<?php echo number_format($report['active_member_percentage'], 2, '.', '');?>%
						</td> -->
					</tr>
				<?php endforeach;?>
			</table>
		</div>
	</div>
</div>