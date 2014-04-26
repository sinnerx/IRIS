<script type="text/javascript">
	
cluster.overview.updateApproval	= function(requestID,status)
{
	var statusR	= {1:"approve",2:"disapprove"};

	if(status == 2)
	{
		//only ask him on disapproval, for confirmation.
		if(!confirm("Are you you want to disapprove this request. All the change made on this request, won't be updated."))
		{
			return false;
		}
	}

	$.ajax({type:"GET",url:base_url+"ajax/request/"+statusR[status]+"/"+requestID}).done(function()
	{
		//refresh the site.
		cluster.overview.getSiteRequests(cluster.overview.currentSiteID);
		cluster.overview.deductTotal(cluster.overview.currentSiteID);
	});
}

cluster.overview.previewRequestDetail = function(requestID)
{
	p1mloader.start("#site-detail");
	$.ajax({type:"GET",url:base_url+"ajax/request/detail/"+requestID}).done(function(txt)
	{
		//close #request-list. (in ajax/request/lists)
		$("#request-list").slideUp();
		$("#request-detail").html(txt).slideDown();

		//minimize
		cluster.overview.minimize();
	});
}

</script>
<section class='panel panel-default'>
	<div class='panel-heading'>
		<div class='row'>
			<div class='col-sm-9'>
			<h4 style="line-height:5px;">Change Requests - <?php echo ucwords($row['siteName']);?> (<?php echo count($res_requests);?>)</h4>
			</div>
		</div>
	</div>
	<div id='request-list'>
	<?php if($res_requests):?>
		<div style='padding:5px;'>List of site update requests. The content won't be updated until it's approved.</div>
	<?php endif;?>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='5%'>No.</th>
				<th>Request Type</th>
				<th>Date</th>
				<th width='80px'></th>
			</tr>
			<?php
			if($res_requests):
			$no	= 1;
			foreach($res_requests as $row)
			{
				$requestID	= $row['siteRequestID'];
				$type	= $typeR[$row['siteRequestType']];
				$date	= date("d F, g:i A",strtotime($row['siteRequestCreatedDate']));

				$previewIcon	= "<a href='javascript:cluster.overview.previewRequestDetail($requestID);'  data-toggle='tooltip' data-placement='bottom' data-original-title='Preview update detail' class='fa fa-search'></a>";
				$approveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,1);' class='fa fa-check-square-o'></a>";
				$disapproveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,2);' class='i i-cross2'></a>";

				echo "<tr>";
				echo "<td>$no.</td>";
				echo "<td>$type</td>";
				echo "<td>$date</td>";
				echo "<td>$approveIcon $disapproveIcon $previewIcon</td>";
				echo "</tr>";

				$no++;
			}
			else:
			echo "<tr><td colspan='4' align='center'>This site has no more change request.</td></tr>";
			endif;

			?>
		</table>
	</div>
	</div> <!-- end of request-list-->
	<div id='request-detail' style='display:none;'>

	</div>
</section>