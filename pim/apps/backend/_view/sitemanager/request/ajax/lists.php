<style type="text/css">
	
.bg-warning
{
	background: orange;
}

</style>
<section class='panel panel-default'>
	<div class='panel-heading'>
	Latest update request
	</div>
	<div class='panel-body'>
	<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='15px'>No.</th><th>Request</th><th style='text-align:right;'><a class='clearRequest' href='<?php echo url::base("ajax/request/clear");?>'>Clear all</a></th>
			</tr>
			<?php
			if($res_request)
			{
				$no	= pagination::recordNo();
				$statusColorR	= Array("primary","success","danger","warning");
				foreach($res_request as $row)
				{
					$type	= $requestTypeNameR[$row['siteRequestType']];
					$row['siteRequestStatus']	= $row['siteRequestCorrectionFlag'] == 1?3:$row['siteRequestStatus'];
					$status	= $requestStatusNameR[$row['siteRequestStatus']];
					$color	= $statusColorR[$row['siteRequestStatus']];
					$clearHref = url::base("ajax/request/clear/".$row['siteRequestID']);
					$clearrequestIcon = in_array($row['siteRequestStatus'],Array(1,2))?"<a href='$clearHref' class='clearRequest i i-cross2 pull-right'></a>":"";

					## if got correction, show icon to see detail.
					$correctionDetailIcon = $row['siteRequestCorrectionFlag'] == 1?"<a data-toggle='ajaxModal' href='".url::base("ajax/request/correctionDetail/".$row['siteRequestID'])."' class='fa fa-wrench pull-left'></a>":"";

					echo "<tr><td>".$no++.".</td><td>$type <span class='badge bg-$color'>$status$correctionDetailIcon</span></td><td>$clearrequestIcon</td>";
				}
			}
			else
			{
				echo "<tr><td colspan='3'>No unread request at all.</td></tr>";
			}
			?>
		</table>
	</div>
	<footer class='pagination-numlink'>
		<?php echo pagination::link();?>
	</footer>
	</div>
</section>