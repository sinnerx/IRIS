<h3 class="m-b-xs text-black">
<a href='info'>Manager</a>
</h3>
<div class='well well-sm'>
List of manager registered in this system.
</div>
<div class='row'>
<div class='col-sm-12' id='site-list'>
<section class="panel panel-default">
<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
	<div class="col-sm-3 pull-right">
	<div class="input-group">
	  <input type="text" class="input-sm form-control" placeholder="Search">
	  <span class="input-group-btn">
	    <button class="btn btn-sm btn-default" type="button">Go!</button>
	  </span>
	</div>
	</div>
	<div class='col-sm-3 pull-left'>
	<button type='button' class='class="btn btn-sm btn-bg btn-default pull-left'><a href='<?php echo url::base("manager/add");?>'>Add +</a></button>
	</div>
</div>
<div class="table-responsive">
	<table id='table-site-list' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th>Manager</th>
			<th class='site-col'>Email</th>
			<th class='site-col'>Assigned Site</th>
			<th width="24px"></th>
		</tr>
	</thead>
	<tbody>
			<?php if($res_manager):
			$no	= pagination::recordNo();
			foreach($res_manager as $row):
			$managerName	= $row['userProfileFullName'];
			$managerEmail	= $row['userEmail'];
			$state			= $stateR[$row['stateID']];
			$site			= ucwords($row['siteName']).", ".$state;
			?>
			<tr>
				<td><?php echo $no++;?>.</td>
				<td><?php echo ucwords($managerName);?></td>
				<td><?php echo $managerEmail;?></td>
				<td><?php echo $site;?></td>
				<td><a href='<?php echo url::base("manager/edit/".$row['userID']."");?>' class='fa fa-edit'></a></td>
			</tr>
			<?php 
			endforeach;
			else:
			echo "<tr><td align='center' colspan='5'>No manager has been registered yet.</td></tr>";
			endif;
			?>
	</tbody>
	</table>
</div>
<footer class='panel-footer'>
<div class='row'>
	<div class='col-sm-12'>
		<?php echo pagination::link();?>
	</div>
</div>
</footer>
</section>
</div>
</div>