<!-- appended into panel-body, of #site-list -->
<style type="text/css">
	
.state-name
{
	padding:3px;
	border-bottom:1px solid #d2d2d2;
	padding-left:20px;
}

.state-site-list
{
	padding:3px;
	border-bottom: 1px dashed #eaeaea;
}

.state-site-list span
{
	display:none;
}

.state-site-list.not-exists:hover
{
	cursor: pointer;
	background: #f4fdf2;
}

.state-site-list.non-cluster
{
	text-decoration: line-through;
}

.state-site-list.site-added
{
	background: #ecfce9;
	box-shadow: 0px 0px 5px #85ec6f;
}

.state-site-list.exists span
{
	color:#6d62d2;
	display:block;
}


</style>
<div class='row'>
<div class='col-sm-12'>
	<?php
	$no	= 1;
	## loop state
	foreach($stateR as $stateID=>$stateName)
	{
		if(isset($res_site[$stateID]))
		{
			echo "<div class='row'><div class='col-sm-12 state-name'>$stateName</div></div>";

			echo "<div class='row'>";
			foreach($res_site[$stateID] as $row)
			{
				$siteID	= $row['siteID'];
				$name	= $row['siteName'];

				//if exists, and if same cluster.
				$cssExists	= !$row['clusterID']?"not-exists":($currentClusterID == $row['clusterID']?"exists":"non-cluster");
				$iconExists	= !$row['clusterID']?"":"<span class='i i-checked pull-right'></span>";


				echo "<div class='col-sm-4'><div class='state-site-list $cssExists' data-siteid='$siteID'>$name$iconExists</div></div>";
			}
			echo "</div>";
			$no++;
		}
	}

	if($no == 1)
	{
		echo "No site was presents at all.";
	}
	?>
</div>
</div>
<div class='row save-button' style='padding-top:10px;display:none;'>
	<div class='col-sm-12'>
		<input type='button' value='Save' class='btn btn-default pull-right' onclick='cluster.saveSite();' />
	</div>
</div>