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
.btn-wrapper a
{
	position: relative;
	right: 10px;
    top: 15px;
}
.btn-wrapper
{
	padding-right: 5px;
}


</style>
<div class='row'>
	<div class='col-sm-12'>
	<?php if($comparedR):
	$type		= $comparedR[0];
	$detailR	= $comparedR[1];

	$requestID	= $comparedR[2];
	list($typeObject) = explode(".",$type);

	$approveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,1);' class='btn-approval text-success pull-right fa fa-check-square-o'></a>";
	$disapproveIcon	= "<a href='javascript:cluster.overview.updateApproval($requestID,2);' class='btn-approval text-danger pull-right i i-cross2'></a>";
	
	$urlCorrection	= url::base("ajax/request/correctionDetail/$requestID");
	$exclamationIcon= "<a data-toggle='ajaxModal' href='$urlCorrection' title='Waiting for correction' class='btn-approval text-danger pull-right fa fa-exclamation-circle'></a>";

	$approvalIcon	= $row_request['siteRequestCorrectionFlag'] != 1?"$disapproveIcon $approveIcon":$exclamationIcon;

	$totalCorrection	= $totalCorrection == 0?"":"<a href='#' title='Previously has done $totalCorrection correction' style='opacity:0.3;' class='btn-approval text-default pull-right fa fa-wrench'></a>";

	echo "<div class='btn-wrapper'>$approvalIcon $totalCorrection</div>";
	$fieldNameR	= $colNameR[$typeObject];
	## new page.
	if($type == 'page.add')
	{

	}
	## new site announcement
	else if($type == 'announcement.add')
	{
		view::render("clusterlead/request/ajax/detail_announcement.add",Array("row"=>$row_request));
	}
	## new activity : render clusterlead/request/ajax/detail_activityadd
	else if($type == "activity.add")
	{
		view::render("clusterlead/request/ajax/detail_activity.add",Array("row"=>$row_request,"activityDate"=>$activityDate));
	}
	else if($type == "article.add")
	{
		view::render("clusterlead/request/ajax/detail_article.add",Array("row"=>$row_request));
	}
	else if($type == "forum_category.add")
	{
		view::render("clusterlead/request/ajax/detail_forum_category.add",Array("row"=>$row_request));
	}
	else if($type == "video.add")
	{
		view::render("clusterlead/request/ajax/detail_video.add",Array("row"=>$row_request));
	}
	## other than new page. like page edit, site_info edit.
	else
	{
		echo "<div class='request-info'>Total changes for <u>$typeName</u> : </div>";?>
		<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th style="width:50%;">Original Value</th><th>Updated Value</th>
			</tr>
		<?php
		foreach($detailR as $key=>$valueR)
		{
			$ori_value		= $valueR[0];
			$updated_value	= $valueR[1];

			if($key == "pageText")
			{
				#$ori_value		= stripslashes($ori_value);
				#$updated_value	= stripslashes($updated_value);
			}

			$ori_value		= nl2br($ori_value);
			$updated_value	= nl2br($updated_value);

			## convert if the column is kind of datetime.
			if(in_array($key,$dateTimeListColumn))
			{
				$ori_value		= date("D, d M g:i A",strtotime($ori_value));
				$updated_value	= date("D, d M g:i A",strtotime($updated_value));
			}

			## param required column, execute if got.
			if(isset($paramRequiredColumn[$key]))
			{
				$ori_value	= $paramRequiredColumn[$key]($ori_value);
				$updated_value = $paramRequiredColumn[$key]($updated_value);
			}

			echo "<tr class='success'><td colspan='2'>$fieldNameR[$key]</td></tr>";
			echo "<tr><td>$ori_value</td><td>$updated_value</td></tr>";
		}

		echo "</table></div>";
	}
	endif;?>
	</div>
</div>