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
				$statusColorR	= Array("primary","success","danger");
				foreach($res_request as $row)
				{
					$type	= $requestTypeNameR[$row['siteRequestType']];
					$status	= $requestStatusNameR[$row['siteRequestStatus']];
					$color	= $statusColorR[$row['siteRequestStatus']];
					$clearHref = url::base("ajax/request/clear/".$row['siteRequestID']);
					$clearrequestIcon = in_array($row['siteRequestStatus'],Array(1,2))?"<a href='$clearHref' class='clearRequest i i-cross2 pull-right'></a>":"";

					echo "<tr><td>".$no++.".</td><td>$type <span class='badge bg-$color'>$status</span></td><td>$clearrequestIcon</td>";
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