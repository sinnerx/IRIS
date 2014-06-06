<h3 class='m-b-xs text-black'>Member List</h3>
<div class='well well-sm'>
List of member registered in for site.
</div>
<section class='panel panel-default'>
	<div class='panel-heading'>

	</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='15px'>No.</th>
				<th>Name</th>
				<th>I.C.</th>
			</tr>
			<?php if(!$res_member):?>
			<tr>
				<td style="text-align:center;" colspan="3">This site has no member yet.</td>
			</tr>
			<?php else:?>
			<?php
			$no	= pagination::recordNo();
			foreach($res_member as $row)
			{?>
			<tr>
				<td><?php echo $no++;?></td>
				<td><?php echo $row['userProfileFullName'];?></td>
				<td><?php echo $row['userIC'];?></td>
			</tr>
			<?php
			}
			?>
			<?php endif;?>
		</table>
	</div>
	<div class='panel-footer'>
	<div class='row'>
		<div class="col-sm-12">
		<?php echo pagination::link();?>
		</div>
	</div>
	</div>
</section>