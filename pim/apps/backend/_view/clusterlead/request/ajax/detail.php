<style type="text/css">
	
.field-title
{
	background: #f0f7f7;
}

.btn-approval
{
	font-size:1.5em;
}

.table tr td:last-child
{
	border-left:1px dashed #bbdfac;
}

.request-info
{
	padding:5px;
}

</style>
<div class='row'>
	<div class='col-sm-12'>
	<?php if($comparedR):
	$type		= $comparedR[0];
	$requestID	= $comparedR[2];
	$typeR		= Array(1=>"page",2=>"page",3=>"site");

	$approveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,1);' class='btn-approval text-success pull-right fa fa-check-square-o'></a>";
	$disapproveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,2);' class='btn-approval text-danger pull-right i i-cross2'></a>";

	echo "<div>$disapproveIcon $approveIcon</div>";
	$fieldNameR	= $colNameR[$typeR[$type]];
	## new page.
	if($type == 1)
	{

	}
	## new site announcement
	else if($type == 4)
	{?>
	<div class='request-info'>
	<?php echo $typeName;?>
	</div>
	<div class='table-responsive'>
	<table class='table'>
		<tr>
			<td width='100px'>Announcement</td><td><?php echo $row_request['announcementText'];?></td>
		</tr>
		<tr>
			<td>Validate until</td><td><?php echo date("d-F Y",strtotime($row_request['announcementExpiredDate']));?></td>
		</tr>
	</table>
	</div>
	<?php
	}
	## other than new page. like page edit, site_info edit.
	else
	{
		## get the comparation detail.
		$detailR	= $comparedR[1];

		echo "<div class='request-info'>Total changes for <u>$typeName</u> : </div>";?>
		<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th>Original Value</th><th>Updated Value</th>
			</tr>
		<?php
		foreach($detailR as $key=>$valueR)
		{
			$ori_value		= $valueR[0];
			$updated_value	= $valueR[1];

			if($key == "pageText")
			{
				$ori_value		= stripslashes($ori_value);
				$updated_value	= stripslashes($updated_value);
			}

			echo "<tr class='success'><td colspan='2'>$fieldNameR[$key]</td></tr>";
			echo "<tr><td>$ori_value</td><td>$updated_value</td></tr>";
		}

		echo "</table></div>";
	}
	endif;?>
	</div>
</div>