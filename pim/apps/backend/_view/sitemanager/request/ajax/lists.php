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
				<th width='15px'>No.</th><th>Request</th><th style=''>Date</th><th style='text-align:right;'><a class='clearRequest' href='<?php echo url::base("ajax/request/clear");?>'>Clear all</a></th>
			</tr>
			<?php
			if($res_request)
			{
				$no	= pagination::recordNo();
				$statusColorR	= Array("primary","success","danger","warning");
				foreach($res_request as $row)
				{
					//var_dump($row);
					//die;
					$type	= $requestTypeNameR[$row['siteRequestType']];
					list($object) = explode('.', $row['siteRequestType']);
					$row['siteRequestStatus']	= $row['siteRequestCorrectionFlag'] == 1?3:$row['siteRequestStatus'];
					$status	= $requestStatusNameR[$row['siteRequestStatus']];
					$color	= $statusColorR[$row['siteRequestStatus']];
					$clearHref = url::base("ajax/request/clear/".$row['siteRequestID']);
					$createdDate = $row['siteRequestCreatedDate'];
					$clearrequestIcon = in_array($row['siteRequestStatus'],Array(1,2))?"<a href='$clearHref' class='clearRequest i i-cross2 pull-right'></a>":"";

					## if got correction, show icon to see detail.
					$correctionDetailIcon = $row['siteRequestCorrectionFlag'] == 1?"<a class='fa fa-wrench pull-left'></a>":"";

					$correctionIcon		= $row['siteRequestCorrectionFlag'] == 1?"<span class='fa fa-wrench pull-left'></span>":"";
					$correctionLabel	= $row['siteRequestCorrectionFlag'] == 1?"<a href='".url::base("ajax/request/correctionDetail/".$row['siteRequestID'])."' data-toggle='ajaxModal' class='badge bg-$color'>$correctionIcon$status</a>":"<span class='badge bg-$color'>$status</span>";
					$linkToSubject = $row['siteRequestCorrectionFlag'] != 1 ? '<a class="fa fa-link" href="'.model::load('site/request')->getObjectUrl($row['siteRequestRefID'], $object).'" target="_blank"></a>' : '';

					echo "<tr><td>".$no++.".</td><td>$type $correctionLabel $linkToSubject </td><td style=''>$createdDate</td><td>$clearrequestIcon</td>";
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