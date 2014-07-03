<script type="text/javascript">
	
cluster.overview.updateApproval	= function(requestID,status,override)
{
	var statusR	= {1:"approve",2:"disapprove"};

	if(status == 2)
	{
		if(override)
		{
			if(!confirm("Are you you want to disapprove this request. All the change made on this request, will be discarded."))
			{
				return false;
			}
		}
		else
		{
			// show rejection form.
			pim.ajax.getModal(pim.base_url+"ajax/request/rejectionForm/"+requestID);return;
		}
	}
	else
	{
		if(!confirm("Approve this request?"))
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
<style type="text/css">
	
.request-info
{
	padding:15px;
}
#request-list table tr td:last-child
{
	text-align: right;
}

</style>
<section class='panel panel-default'>
	<div class='panel-heading'>
		<div class='row'>
			<div class='col-sm-9'>
			<h4>Site Requests - <?php echo ucwords($row['siteName']);?> (<?php echo count($res_requests);?>)

			</h4>
			</div>
			<div class='col-sm-3' style='text-align:right;'>
				<a style='font-size:1.5em;opacity:0.5;' href='javascript:cluster.overview.getSiteRequests(cluster.overview.currentSiteID);' class='fa fa-refresh pull-right'></a>
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
				<th>Requests Date</th>
				<th>By</th>
				<th width='100px'></th>
			</tr>
			<?php
			if($res_requests):
			$no	= 1;
			foreach($res_requests as $row)
			{
				$requestID	= $row['siteRequestID'];
				$type	= $typeR[$row['siteRequestType']];
				$date	= date("d F, g:i A",strtotime($row['siteRequestCreatedDate']));
				$date	= $row['siteRequestUpdatedDate']?date("d F, g:i A",strtotime($row['siteRequestUpdatedDate'])):$date;
				$by		= $row['userProfileFullName'];

				$previewIcon	= "<a href='javascript:cluster.overview.previewRequestDetail($requestID);'  data-toggle='tooltip' data-placement='bottom' data-original-title='Preview update detail' class='fa fa-search'></a>";
				$approveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,1);' class='fa fa-check-square-o'></a>";
				$disapproveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,2);' class='i i-cross2'></a>";

				$urlCorrection	= url::base("ajax/request/correctionDetail/".$requestID);
				$correctionText = isset($totalCorrection[$requestID])?"And has previously done ".count($totalCorrection[$requestID])." corrections.":"";
				$exclamationIcon= "<a data-toggle='ajaxModal' href='$urlCorrection' class='fa fa-exclamation-circle' style='color:red;' title='Waiting for correction. $correctionText'></a>&nbsp;&nbsp;";

				$approvalIcon	= $row['siteRequestCorrectionFlag'] != 1?"$approveIcon $disapproveIcon":$exclamationIcon;
				$correctionIcon = $correctionText != ""?"<span class='fa fa-wrench' style='opacity:0.7;' title='$correctionText'></span>":"";


				echo "<tr>";
				echo "<td>$no.</td>";
				echo "<td>$type $correctionIcon</td>";
				echo "<td>$date</td>";
				echo "<td>$by</td>";
				echo "<td>$correction $approvalIcon$previewIcon</td>";
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