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
			<form method="get" id="formSearch"><!-- search form -->
				<div class="col-sm-3 pull-right">
						<div class="input-group">
						<input class="input-sm form-control" name="search" placeholder="Search : by IC or by first name" value='<?php echo request::get("search");?>' type="text">
							<span class="input-group-btn">
								<button class="btn btn-sm btn-default" type="button" onclick="$('#formSearch').submit();">Go!</button>
							</span>
						</div>
				</div>
				<div class='col-sm-3 pull-right' style="text-align: right; padding-right:10px; position: relative; top: 2px;">
					<label style="font-weight:normal; font-size: 1em;">Search all pi1m sites <input style="position:relative; top: 2px;" type='checkbox' name='searchAllSites' <?php if(request::get('searchAllSites') && request::get('search')):?> checked <?php endif;?> /></label>
				</div>
			</form>
		</div>
	</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='15px'>No.</th>
				<th width="300px">First Name</th>
				<th width="199px">Last Name</th>
				<th>Email</th>
				<th>I.C.</th>
				<th style="text-align:center;">KTW Synced</th>
				<th width='200px'>Date registered</th>
				<th width='50px'>Points</th>
				<th width='95px'></th>
			</tr>
			<?php if(!$user):?>
			<tr>
				<td style="text-align:center;" colspan="3">No member was found.</td>
			</tr>
			<?php else:?>
			<?php
			$no	= pagination::recordNo();
			foreach($user as $row)
			{
				$siteID = $row['siteID'];
				$isSiteMember = $siteID == authData('site.siteID');
				$active		= $row['siteMemberStatus'] == 1?"active":"";
				$opacity	= $row['siteMemberStatus'] == 0?"style='opacity:0.5;'":"";
				$href		= ($row['siteMemberStatus'] == 1?"deactivate":"activate")."?".$row['userID'];
				$href		= "?toggle=".$row['userID'];

				$isOutsider	= $row['siteMemberOutsider'] == 1?"<span class='label label-primary'>An Outsider</span>":null;

			?>
			<tr <?php echo $opacity;?>>
				<td><?php echo $no++;?></td>
				<td><?php echo $row['userProfileFullName'];?> <?php if(!$isSiteMember):?><span class='label label-primary'><?php echo $row['siteName'];?></span><?php endif;?></td>
				<td><?php echo $row['userProfileLastName']." $isOutsider";?></td>
				<td><?php echo $row['userEmail'];?></td>
				<td><?php echo $row['userIC'];?></td>
				<td style="text-align:center;"><?php echo $row['siteMemberSynced'] == 1? '<span class="fa fa-check" style="color:#6ba631;"></span>': 'Not yet';?></td>
				<td><?php echo date("j F Y, g:i A",strtotime($row['userCreatedDate']));?></td>
				<td><a href="<?php echo url::base('member/point'); ?>/<?php echo $row['userID']; ?>/1"><?php echo $row['userPoint']; ?></a></td>
				<td>
					<center>
					<a <?php if(!$active): ?>onclick="return confirmation();" href="<?php echo $href;?>"<?php endif; ?> class="<?php echo $active;?>" >
						<i class="fa fa-check-square text-success text-active"></i>
						<i class="fa fa-square text"></i>
					</a>
	                <a href="<?php echo url::base('ajax/member/detail'); ?>?userID=<?php echo $row['userID']; ?>" data-toggle="ajaxModal">
						<i class="fa fa-user"></i>
					</a>
					<a class='fa fa-edit' href="<?php echo url::base('member/edit'); ?>/<?php echo $row['userID']; ?>" ></a>
					<a onclick='return confirm("Confirm delete?");' class="i i-cross2" href="delete/<?php echo $row['userID']; ?>"></a>
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