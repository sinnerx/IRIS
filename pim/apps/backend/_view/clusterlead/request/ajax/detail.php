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

</style>
<div class='row'>
	<div class='col-sm-12'>
	<?php if($comparedR):
	$type	= $comparedR[0];
	$typeR	= Array(1=>"page",2=>"page",3=>"site");

	$fieldNameR	= $colNameR[$typeR[$type]];
	## new page.
	if($type == 1)
	{

	}
	## other than new page. like page edit, site_info edit.
	else
	{
		## get the comparation detail.
		$detailR	= $comparedR[1];
		$requestID	= $comparedR[2];

		$approveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,1);' class='btn-approval text-success pull-right fa fa-check-square-o'></a>";
		$disapproveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,2);' class='btn-approval text-danger pull-right i i-cross2'></a>";

		echo "<div style='padding:5px;'>Total changes for <u>$typeName</u> : $disapproveIcon $approveIcon</div>";?>
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