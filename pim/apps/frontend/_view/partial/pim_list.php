
<?php
if(count($siteListR) > 0):
foreach($siteListR as $siteID=>$row)
{
	$slug	= $row['siteSlug'];
	$name	= ucwords($row['siteName']);
	$state	= $stateR[$row['stateID']];
	$href	= url::base("$slug");
	echo "<li><a href='$href'>$name, $state</a></li>";
}
endif;
?>