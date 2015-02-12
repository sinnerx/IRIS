<style type="text/css">
	
.state-name
{
	padding:3px;
	border-bottom:1px solid #d2d2d2;
}
.state-site-name
{
	padding:3px;
	border-bottom: 1px dashed #eaeaea;
}
.state-site-name a
{
	display:none;
}
.state-site-name:hover a
{
	color:red;
	display:inline;
}

</style>
<section class='panel panel-default'>
	<div class='panel-heading'>
	<h4><?php echo $clusterName;?> : List of site</h4>
	</div>
	<div class='panel-body' id='site-add'>
	<p>
		List of existing site(s) assigned to this cluster.
		<?php if($res_site):?>
		<a href='#' onclick='cluster.getAddForm(<?php echo $clusterID;?>);' class='label label-primary'>Add more site.</a>
		<?php endif;?>
	</p>
	<div class='row'><div class='col-sm-12' style='padding-left:20px;padding-right:20px;'>
		<?php
		if($res_site):
			$stateAddedFlag	= Array();
			$no = 1;
			foreach($stateR as $stateID=>$stateName)
			{
				if(isset($res_site[$stateID]))
				{
					echo "<div class='row'><div class='state-name'>$stateName</div></div>";
					echo "<div class='row'>";
					foreach($res_site[$stateID] as $row)
					{
						$siteName	= $row['siteName'];
						$siteID		= $row['siteID'];

						echo "<div class='state-site-name col-sm-4'>$siteName <a onclick='cluster.removeSite($siteID);'  href='#' class='i i-cross2'></a></div>";
					}
					echo "</div>";


					$no++;
				}
			}
		else:?>
		No site was added for this cluster yet. <a href='#' onclick='cluster.getAddForm(<?php echo $clusterID;?>);'><u>Add?</u></a>
		
		<?php
		endif;?>
	</div>
	</div>
	</div>
</section>