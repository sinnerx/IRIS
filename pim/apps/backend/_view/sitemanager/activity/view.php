<script type="text/javascript">
	
var sitealbum	= new function()
{
	this.openLibrary	= function()
	{

	}
}

</script>
<h3>
	<?php echo ucwords($typeName);?> : <?php echo $row['activityName'];?>
</h3>
<div class='well well-sm'>
	Detailed information of the selected activity
</div>
<div class='row'>
	<div class='col-sm-6'>
		<!-- <div class='row'>
			<div class='col-sm-6'>
				<div class='form-group'>
				<label>Date</label>
					<div>
					<?php echo model::load("helper")->dateRangeLabel(Array($row['activityStartDate'],$row['activityEndDate']));?>
					</div>
				</div>
			</div>
			<div class='col-sm-6'>

			</div>
		</div>
		<div class='row'>
			<div class='col-sm-6'>

			</div>
			<div class='col-sm-6'>

			</div>
		</div> -->
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th width="150px">Date</th><td><?php echo model::load("helper")->dateRangeLabel(Array($row['activityStartDate'],$row['activityEndDate']));?></td>
					<th>Participation</th><td><?php echo model::load("activity/activity")->participationName($row['activityParticipation']);?></td>
				</tr>
				<tr>
					<td colspan='2'><b>Description</b><br>
					<?php echo $row['activityDescription'];?>

					</td>
					<td colspan="2">
						<b>Location</b><br>
						<?php 
						$address	= $row['activityAddressFlag'] == 1?model::load("access/auth")->getAuthData("site","siteInfoAddress"):$row['activityAddress'];
						echo $address;?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class='col-sm-6'>
		<section class='panel panel-default'>
			<header class='panel-heading bg-light'>
				<ul class='nav nav-tabs nav-justified'>
					<li class='active'><a href='#detail' data-toggle='tab'><?php echo ucwords($typeName);?>'s Detail</a></li>
					<li><a data-toggle='tab' href='#album'>Albums (<?php echo count($res_album);?>)</a></li>
					<li><a data-toggle='tab' href='#blog'>Related Blogs</a></li>
				</ul>
			</header>
			<div class='panel-body'>
				<div class='tab-content'>
					<div class='tab-pane active' id='detail'>
					<?php if($row['activityType'] == 1):?>
					<div class='table-responsive'>
						<table class='table'>
							<tr>
								<td>Type</td><td>: <?php echo model::load("activity/event")->type($row['activityType']);?></td>
							</tr>
						</table>
					</div>
					<?php elseif($row['activityType'] == 2):?>

					<?php endif;?>
					</div>
					<div class='tab-pane' id='album'>
					<?php if(!$res_album):?>
					This activity has no album yet. Do you want to <a href='<?php echo url::base("image/album?activity=$activityID#add");?>'>add</a>?
					<?php else:?>

					<?php endif;?>
					</div>
					<div class='tab-pane' id='blog'>
					<?php if(!$res_article):?>
					There's no related blog written about this activity yet.
					<?php else:?>

					<?php endif;?>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>