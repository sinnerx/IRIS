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
			window.location.href = pim.base_url+"kpi/kpi_overview/1/"+$("#selectMonth").val()+"/"+$("#selectYear").val();
		}

		
	};
}
</script>

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
			<?php echo form::select("selectMonth",model::load("helper")->monthYear("month"),"onchange='site.overview.updateDate();'",$selectMonth);?>
			<?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='site.overview.updateDate();'",$selectYear);?>			
		</div>					
	</div>
</div>

<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			  KPI Report for <?php echo $selectMonth; ?> / <?php echo $selectYear; ?>
		</div>
		
		<div class="table-responsive">
			<table class='table ' border='0'>

				<tr>
					<th></th> 		
					<th>Site</th> 				
					<th>Event</th>	
					<th>Entrepreneurship Class </th>	
					<th>Entrepreneurship Program </th>
					<th>Training </th>	
					<th>Active member (Login) </th>	
				</tr>			

			<?php
					$no	= pagination::recordNo();
			 foreach ($kpiAll as $key => $row) { ?>			
				<tr>
					<td><?php echo $no++; ?></td> 						
					<td><?php echo $row['sitename'] ?></td> 
					<td>
						<?php if ($row['event']['done'] == '1') {  ?>
								<font color="green"><?php echo $row['event']['totalEvent']; ?> / <?php echo  $row['event']['maxEvent']; ?></font>  
								<?php } else { ?>
								<font color="red"><?php echo $row['event']['totalEvent']; ?> / <?php echo  $row['event']['maxEvent']; ?></font> 
						<?php }	?>
					</td>					
					<td>
						<?php if ($row['entClass']['done'] == '1') {   ?> 
								<font color="green"><?php echo $row['entClass']['totalEvent']; ?> / <?php echo  $row['entClass']['maxClass'];  ?></font>  
								<?php } else { ?>
								<font color="red"> <?php echo $row['entClass']['totalEvent']; ?> / <?php echo  $row['entClass']['maxClass']; ?></font> 
						<?php }	?>
					</td> 
					<td>
						<?php if ($row['entProgram']['done'] == '1') {    ?>
								<font color="green"><?php echo $row['entProgram']['total']; ?> / <?php echo  $row['entProgram']['maxSale'];  ?></font>  
								<?php } else { ?>
								<font color="red"> <?php echo $row['entProgram']['total']; ?> / <?php echo  $row['entProgram']['maxSale']; ?></font> 
						<?php }	?>
					</td> 			
					<td>
						<?php if ($row['training']['done'] == '1') {     ?>
								<font color="green"><?php echo ($row['training']['hour']); ?> / <?php echo  $row['training']['maxHour'];  ?></font>  
								<?php } else { ?>
								<font color="red"><?php echo ($row['training']['hour']); ?> / <?php echo  $row['training']['maxHour']; ?></font> 
						<?php }	?>
					</td>
					<td>
						<?php if ($row['login']['done'] == '1') {    ?>
							 	<font color="green"><?php echo $row['login']['target'];  ?></font>  
								<?php } else { ?>
								<font color="red"> <?php echo $row['login']['target'];  ?></font> 
						<?php }	?>
					</td> 			
				</tr>
			<?php } ?>

			</table>			
		</div>
	</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>		
		<?php echo pagination::link();?>
		</div>			
	</div>
</div>