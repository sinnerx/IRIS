<script type="text/javascript">
	
var newsletter = new function()
{
	this.siteID = null;

	this.connect = function(siteID)
	{
		this.siteID = siteID;
		$('.site-list').removeClass('selected');
		$("#mailchimpLists").show();
		$('.site'+siteID).addClass('selected');
	};

	this.disconnect = function(siteID)
	{
		if(!confirm('Are you sure?'))
			return false;
		
		window.location.href = '?disconnect='+siteID;
	}

	this.select = function(mailChimpListID)
	{
		var siteID = this.siteID;
		var url = pim.base_url+"ajax/newsletter/setListID/"+siteID+"/"+mailChimpListID;

		if(!confirm('Are you sure?'))
		{
			return false;
		}

		$.ajax({type:"GET", url:url}).done(function(txt)
		{
			newsletter.update(siteID, mailChimpListID);

		});
	};

	this.update = function(siteID, mailChimpListID)
	{
		$(".site"+siteID).removeClass("selected");
		$("#mailChimpLists").hide();
		$(".mailchimp"+mailChimpListID).remove();
		$(".site"+siteID+" #connectionStatus").html(mailChimpListID);

		$(".site"+siteID+" input").removeClass('btn-primary')
		.addClass('btn-danger')
		.attr('onclick', 'newsletter.disconnect('+siteID+');')
		.val('Disconnect');
	};
};



</script>
<style type="text/css">
	
#table-site-list tr.selected
{
	background: #dae4f8;
}

</style>
<h3 class='m-b-xs text-black'>
Mailchimp Newsletter Intergration
</h3>
<div class='well well-sm'>
List of site integrated with mailchimp.
</div>
<div class="row">
	<div class='col-sm-6'>
		<div class="table-responsive">
		<table id='table-site-list' class="table table-striped b-t b-light">
			<thead>
				<tr>
					<th width="20">No.</th>
					<th>Site Name</th>
					<th>Mailchimp List ID</th>
					<td></td>
				</tr>
				<?php if($res_site):?>
				<?php 
				$no = 1;
				$usedList = array();
				foreach($res_site as $row_site):?>
				<?php
				$row_newsletter = isset($res_newsletter[$row_site['siteID']]) ? $res_newsletter[$row_site['siteID']] : false;
				$connected = $row_newsletter && $row_newsletter['mailChimpListID'] != '' ? true : false;

				if($connected)
					$usedList[] = $row_newsletter['mailChimpListID'];
				?>
				<tr class='site-list site<?php echo $row_site['siteID'];?>'>
					<td><?php echo $no++;?>.</td>
					<td><?php echo $row_site['siteName'];?></td>
					<td id='connectionStatus'><?php echo $connected ? $row_newsletter['mailChimpListID'] : 'Not-connected';?></td>
					<td>
						<?php if(!$connected):?>
						<input type='button' onclick='newsletter.connect(<?php echo $row_site['siteID'];?>);' class='btn btn-primary' value='Connect' style="padding:3px;font-size:13px;" />
						<?php else:?>
						<input type='button' onclick='newsletter.disconnect(<?php echo $row_site['siteID'];?>);' class='btn btn-danger' value='Disconnect' style="padding:3px;font-size:13px;" />
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
				<?php else:?>
				<?php endif;?>
			</thead>
			<tbody>
			</tbody>
		</table>
		</div>
	</div>
	<div class='col-sm-6' style="display:none;" id='mailchimpLists'>
		<h3>Mailchimp List</h3>
		<div class="table-responsive">
		<table id='table-site-list' class="table table-striped b-t b-light">
			<thead>
				<tr>
					<th>Mailchimp List ID</th>
					<th>Name</th>
					<th width="60px">Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if(count($mailChimpList) > 0):?>
			<?php
			$no = 1;
			foreach($mailChimpList as $id=>$row):?>
			<?php
			$status = in_array($id, $usedList) ? "used" : "unused";
			?>
			<tr class='mailchimp<?php echo $id;?>'>
				<td><?php echo $id;?></td>
				<td><?php echo $row['name'];?></td>
				<td><?php echo $status;?>
				<?php if($status == "used"):?>
				<?php endif;?>
				</td>
				<td style="width:100px;">
					<?php if($status == "unused"):?>
					<a href='javascript:newsletter.select("<?php echo $id;?>");'>Select</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
			<?php else:?>

			<?php endif;?>
			</tbody>
		</table>
	</div>
</div>