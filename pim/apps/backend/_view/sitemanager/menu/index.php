<script type="text/javascript" src='<?php echo url::asset("_scale/js/sortable/jquery.sortable.js");?>'></script>
<style type="text/css">

.component-list
{
	background:#68cc9c;
	color: white;
}

</style>
<script type="text/javascript">


</script>
<h3 class='m-b-xs text-black'>
Site Menu
</h3>
<div class='well well-sm'>
Your main site menu. You can edit or add here based on existing component.
</div>
<a href='<?php echo url::base("ajax/gallery/index");?>' data-toggle='ajaxModal'>
Test</a>
<div class='row'>
	<div class='col-sm-8 col-xs-6'>
	<section class='panel panel-default'>
		<div class='panel-heading'>
		Current Menu
		</div>
		<div id='top-menu' class='list-group sortable'>
		<?php
		$addedComp	= Array();
		foreach($topMenu as $row)
		{
			$id				= $row['menuID'];
			$compName		= $component[$row['componentNo']]['componentName'];
			$addedComp[] 	= $row['componentNo'];
			?>
		<li class='list-group-item'><?php echo $row['menuName'];?>
		<span class='badge bg-success pull-right'><?php echo $compName;?>
			<a href='javascript:menu.remove(<?php echo $id;?>);' class='i i-cross2'></a>
		</span>
		</li>
		<?php
		}
		?>
		</div>
		<!-- <div class='table-responsive'>
			<table class='table'>
				<tr>
					<th>Menu Name</th>
				</tr>
				<?php if($topMenu):

				foreach($topMenu as $row){
				?>
				<tr>
					<td><?php echo $row['menuName'];?></td>
				</tr>
				<?php
				} 
				endif;?>
			</table>
		</div> -->
	</section>
	</div>
	<!-- component grid -->
	<div class='col-sm-4 col-xs-6'>
	<section class='panel panel-default'>
		<div class='panel-heading'>
		Components
		</div>
		<div id='component' class='list-group sortable'>
			<?php
			foreach($component as $row)
			{
				if(in_array($row['componentNo'],$addedComp))
					continue;
				echo "<li class='component-list list-group-item'>$row[componentName]</li>";
			}

			?>
			<!-- <table class='table sortable'>
				<tr>
					<th>Component Name</th>
				</tr>
				<?php foreach($component as $row)
				{?>
				<tr draggable='true'>
					<td><?php echo $row['componentName'];?></td>
				</tr>

				<?php 
				}?>
			</table> -->
		</div>
	</section>
	</div>
</div>
<script type="text/javascript">
	
$('#top-menu, #component').sortable({
    connectWith: '.connected'
});

</script>