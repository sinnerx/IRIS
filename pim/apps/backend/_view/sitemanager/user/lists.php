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
				<th></th>
			</tr>
			<?php if(!$user):?>
			<tr>
				<td style="text-align:center;" colspan="3">This site has no member yet.</td>
			</tr>
			<?php else:?>
			<?php
			$no	= pagination::recordNo();
			foreach($user as $row)
			{
				$active		= $row['siteMemberStatus'] == 1?"active":"";
				$opacity	= $row['siteMemberStatus'] == 0?"style='opacity:0.5;'":"";
				$href		= ($row['siteMemberStatus'] == 1?"deactivate":"activate")."?".$row['userID'];
				$href		= "?toggle=".$row['userID'];
			?>
			<tr <?php echo $opacity;?>>
				<td><?php echo $no++;?></td>
				<td><?php echo $row['userProfileFullName'];?></td>
				<td><?php echo $row['userIC'];?></td>
				<td>
					<a href="<?php echo $href;?>" class="<?php echo $active;?>" >
						<i class="fa fa-check text-success text-active"></i>
						<i class="fa fa-times text-danger text"></i>
					</a>
				</td>
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