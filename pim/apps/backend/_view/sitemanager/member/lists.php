<h3 class='m-b-xs text-black'>Member List</h3>
<div class='well well-sm'>
List of member registered in for site.
</div>
<script type="text/javascript">
		function confirmation(){
			var r = confirm("Are you sure this member has paid?");
    		if (r == true) {
        		return true;
    		} else {
        		return false;
    		}
		}
</script>
<?php echo flash::data();?>
<section class='panel panel-default'>
	<div class='panel-heading'>
		<div class="row">
			<div class="col-sm-3 pull-right">
				<form method="get" id="formSearch"><!-- search form -->
					<div class="input-group">
					<input class="input-sm form-control" name="search" placeholder="Search : by IC or by name" value='<?php echo request::get("search");?>' type="text">
						<span class="input-group-btn">
							<button class="btn btn-sm btn-default" type="button" onclick="$('#formSearch').submit();">Go!</button>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='15px'>No.</th>
				<th width="300px">Name</th>
				<th>Email</th>
				<th>I.C.</th>
				<th width='200px'>Date registered</th>
				<th width='65px'></th>
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

				$isOutsider	= $row['siteMemberOutsider'] == 1?"<span class='label label-primary'>An Outsider</span>":null;

			?>
			<tr <?php echo $opacity;?>>
				<td><?php echo $no++;?></td>
				<td><?php echo $row['userProfileFullName']." ".$row['userProfileLastName']." $isOutsider";?></td>
				<td><?php echo $row['userEmail'];?></td>
				<td><?php echo $row['userIC'];?></td>
				<td><?php echo date("j F Y, g:i A",strtotime($row['userCreatedDate']));?></td>
				<td>
					<center>
					<a <?php if(!$active): ?>onclick="return confirmation();" href="<?php echo $href;?>"<?php endif; ?> class="<?php echo $active;?>" >
						<i class="fa fa-check-square text-success text-active"></i>
						<i class="fa fa-square text"></i>
					</a>
	                <a href="<?php echo url::base('ajax/member/detail'); ?>?userID=<?php echo $row['userID']; ?>" data-toggle="ajaxModal">
						<i class="fa fa-external-link"></i>
					</a>
					</center>
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