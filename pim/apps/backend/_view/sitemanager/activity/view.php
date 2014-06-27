<script type="text/javascript">
	
var sitealbum	= new function()
{
	this.openLibrary	= function()
	{

	}
}

</script>
<style type="text/css">

.album-list
{
	margin-bottom: 5px;
}
.album-list.col-sm-4
{
	position: relative;
}
.album-name
{
}
.activityDate
{
	width: 100%;
}
.activityDate th
{
	text-align: center;
}
.activityDate tr td:nth-child(2), .activityDate tr td:nth-child(3)
{
	text-align: center;
}
.activityDate tr td
{
	border-bottom: 1px solid #cdcdcd;
}

</style>
<h3>
	<?php echo ucwords($typeName);?> : <?php echo $row['activityName'];?>
</h3>
<div class='well well-sm'>
	Detailed information of the selected activity
</div>
<div class='row'>
	<div class='col-sm-6'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th width="150px">Date</th><td><?php echo model::load("helper")->dateRangeLabel(Array($row['activityStartDate'],$row['activityEndDate']));?></td>
					<th>Participation</th><td><?php echo model::load("activity/activity")->participationName($row['activityParticipation']);?></td>
				</tr>
				<tr>
					<td colspan='2' rowspan="2">
						<div><b>Date obligation</b> : <?php echo model::load("activity/activity")->dateObligation($row['activityAllDateAttendance']);?></div>
						<?php
						if($activityDate):
						echo "<table class='activityDate'>";
						echo "<tr><th rowspan='2' style='text-align:left;'>Date</th><th style='text-align:center;' colspan='2'>Time</th></tr>";
						echo "<tr><th align='center'>Start</th><th>End</th></tr>";
						foreach($activityDate as $row_date)
						{
							$date	= $row_date['activityDateValue'];
							$start	= $row_date['activityDateStartTime'];
							$end	= $row_date['activityDateEndTime'];

							echo "<tr>";
							echo "<td>".date("j F Y",strtotime($date))."</td>";
							echo "<td>".date("g:i A",strtotime($start))."</td>";
							echo "<td>".date("g:i A",strtotime($end))."</td>";
							echo "</tr>";
						}
						echo "</table>";
						endif;
						?>
					</td>
					<td colspan="2">
						<b>Location</b><br>
						<?php 
						$address	= $row['activityAddressFlag'] == 1?model::load("access/auth")->getAuthData("site","siteInfoAddress"):$row['activityAddress'];
						echo $address;?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>Description</b><br>
						<?php echo nl2br($row['activityDescription']);?>
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
					<li><a data-toggle='tab' href='#blog'>Related Blogs (<?php echo count($res_article);?>)</a></li>
				</ul>
			</header>
			<div class='panel-body'>
				<div class='tab-content'>
					<div class='tab-pane active' id='detail'>
					<?php if($row['activityType'] == 1):?>
					<div class='table-responsive'>
						<table class='table'>
							<tr>
								<td>Type</td><td>: <?php echo model::load("activity/event")->type($row['eventType']);?></td>
							</tr>
						</table>
					</div>
					<?php elseif($row['activityType'] == 2):?>
					<div class="table-responsive">
						<table class='table'>
							<tr>
								<td>Type</td><td>: <?php echo model::load("activity/training")->type($row['trainingType']);?></td>
							</tr>
							<tr>
								<td width="100px">Max Pax</td><td>: <?php echo $row['trainingMaxPax'];?></td>
							</tr>
						</table>
					</div>
					<div class='row'>
					<div class='col-sm-12'>
						<h5>Current Participants :</h5>
						<div class='table-responsive'>
							<?php if($res_participant):?>
							<p>
								List of participants currently joined through the site.
							</p>
							<table class='table'>
								<tr>
									<th style="width:15px;">No.</th><th>Name</th><th>I.C.</th><th>Date</th><th width="25px"></th>
								</tr>
								<?php 
								$no = 1;
								foreach($res_participant as $row)
								{?>
								<tr>
									<td><?php echo $no++;?></td>
									<td><?php echo $row['userProfileFullName'];?></td>
									<td><?php echo $row['userIC'];?></td>
									<td><?php echo date("j M Y",strtotime($row['activityUserCreatedDate']));?></td>
									<td><a href='<?php echo url::base("ajax/member/detail?userID=".$row['userID']);?>' data-toggle='ajaxModal' class='fa fa-search'></a></td>
								</tr>
								<?php }?>
							</table>
							<?php else:?>
							No participants yet.
							<?php endif;?>
						</div>
					</div>
					</div>
					<?php endif;?>
					</div>
					<div class='tab-pane' id='album'>
					<?php if(!$res_album):?>
					This activity has no album yet. Do you want to <a href='<?php echo url::base("image/album?activity=$activityID#add");?>'>add</a>?
					<?php else:?>
					<p>List of related album added for this <?php echo $typeName;?>. <a href='<?php echo url::base("image/album?activity=$activityID#add");?>'>Do you want to add more?</a></p>
					<?php
					foreach($res_album as $row):?>
					<div class='row album-list'>
						<div class='col-sm-4'>
							<a href='<?php echo url::base("image/albumPhotos/".$row['siteAlbumID']);?>'>
							<img style='width:100%;' src="<?php echo $imageServices->getPhotoUrl($row['albumCoverImageName']);?>" />
							</a>
						</div>
						<div class='col-sm-8'>
							<div class='table-responsive'>
								<table class='table'>
									<tr>
										<td colspan="2"><?php echo $row['albumName'];?></td>
										<td colspan='2' style="text-align:right;"><?php echo dateRangeViewer($row['albumCreatedDate']);?></td>
									</tr>
									<tr>
										<td colspan="4" style='text-align:right;'>By <?php echo $row['userProfileFullName'];?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php 
					endforeach;
					endif;?>
					</div>
					<div class='tab-pane' id='blog'>
					<?php if(!$res_article):?>
					There's no related blog written about this activity yet. Or you may <a target='_blank' href='<?php echo url::base("site/addArticle?reportfor=".$activityID);?>'>write a new report article</a> or <a href='#'>choose from a list just as a reference</a> for this activity.
					<?php else:?>
					<p>List of blog article related to this activity.</p>
					<div class='table-responsive'>
						<table style="width:100%;" class='table'>
						<tr>
							<th>Article Name</th>
							<th>Related As</th>
							<th>Date Published</th>
							<th></th>
						</tr>
						<?php foreach($res_article as $row):
						$articleName	= $row['articleName'];
						$publishedDate	= $row['articlePublishedDate'];
						$type			= $row['activityArticleType'];
						$status			= $row['articleStatus'];
						$typeR			= Array(1=>"Report",2=>"Reference");

						$statustitle	= Array(
										"Currently pending for approval."
												);
						?>
						<tr>
							<td><?php echo ucwords($articleName);?></td>
							<td><?php echo $typeR[$type];?></td>
							<td><?php echo date("j M Y",strtotime($publishedDate));?></td>
							<td><?php echo model::load("template/icon")->status($status,$statustitle[$status]);?></td>
						</tr>
						<?php endforeach;?>
						</table>
					</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>