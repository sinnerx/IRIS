<h3 class='m-b-xs text-black'>
Activity : Event
</h3>
<div class='well well-sm'>
List of event activities of your site.
</div>
<script type="text/javascript">

function buttonCheck()
{
	
		if(!confirm("Are you sure want to post this event on facebook page."))
		{
			return false;
		}
	

	return true;
}

var activity = new function($)
{
	this.delete = function(id)
	{
		if(!confirm('Delete this activity?'))
			return false;

		window.location.href = '<?php echo url::base("activity/delete/");?>'+id;
	}

	this.undelete = function(id)
	{
		if(!confirm('Cancel deletion of this activity?'))
			return false;

		window.location.href = '<?php echo url::base("activity/undelete/");?>'+id;
	}
}(jQuery);

</script>
<?php echo flash::data();?>
<section class='panel panel-default'>
		<div class='row'>
		<a style="position:relative;right:10px;" href='<?php echo url::base("activity/add#event");?>' class='btn btn-primary pull-right'>Add New Event</a>
		</div>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width="10px">No.</th>
				<th width="30%">Event Name</th>
				<th width="10%">Type</th>
				<th>Participation</th>
				<th colspan="2" width="20%" style='text-align:center;'>Date</th>
				<th width='110px'></th>
			</tr>
			<?php 
			if($res_event):
			$requestData	= model::load("site/request")->replaceWithRequestData("activity.update",array_keys($res_event));
			$no	= pagination::recordNo();
			foreach($res_event as $row):

			$activityID	= $row['activityID'];
			if(isset($requestData[$activityID]))
			{
				$row 							= model::load("helper")->replaceArray($row,$requestData[$activityID]);
				$row['activityApprovalStatus']	= 4; ## just a decoy.
			}

			$status		= $row['activityApprovalStatus'];
			$startDate	= $row['activityStartDate'];
			$endDate	= $row['activityEndDate'];
			$partic		= $activity->participationName($row['activityParticipation']);

			$statusIcon	= model::load("template/icon")->status($status);
			?>
			<tr>
				<td><?php echo $no++;?>.</td>
				<td><?php echo $row['activityName'];?></td>
				<td><?php echo model::load("activity/event")->type($row['eventType']);?></td>
				<td><?php echo $partic;?></td>
					<?php if($startDate == $endDate):?>
					<td colspan="2" style='text-align:center;'><?php echo date("d/m/Y",strtotime($startDate));?></td>
					<?php else:?>
					<td style='text-align:right;border-right:1px solid #f2f4f8;'><b>From</b> <?php echo date("d/m/Y",strtotime($startDate));?></td>
					<td><b>To</b> <?php echo date("d/m/Y",strtotime($endDate));?></td>
					<?php endif;?>
				<td><?php echo $statusIcon;?>
				<a class="fa fa-facebook-square" style="color:#44609d;" onclick ='return buttonCheck();' href="<?php echo url::base('facebook/getActivityInfo');?>?activityID=<?php echo $activityID; ?>&activityType=event"></a>
				<?php if($status != 2):?>
					<a href='<?php echo url::base("activity/view/event/$activityID");?>' class='fa fa-search'></a>
					<a href='<?php echo url::base("activity/edit/$activityID");?>' class='fa fa-edit'></a>
					<!--<a href='javascript:activity.delete(<?php echo $activityID;?>);' class='i i-cross2'></a>-->
					<?php if($status == 5):?>
						<a href='javascript:activity.undelete(<?php echo $activityID;?>);' class='i i-cross2'></a>
					<?php else:?>
						<a href='javascript:activity.delete(<?php echo $activityID;?>);' class='i i-cross2'></a>
					<?php endif;?>
				<?php endif;?>
				</td>
			</tr>
			<?php 
			endforeach;
			else:?>
			<tr>
				<td colspan="6" align="center">No event was added at all.</td>
			</tr>
			<?php endif;?>
		</table>
	</div>
	<div class='panel-footer'>
		<div class='row'>
			<div class='col-sm-12'>
			<?php echo pagination::link();?>
			</div>
		</div>
	</div>
</section>