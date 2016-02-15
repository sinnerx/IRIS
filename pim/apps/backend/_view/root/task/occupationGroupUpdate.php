<style type="text/css">

.tabla tr
{
	border-bottom:1px solid #cdcdcd;
}
.tabla tr td
{
	padding:5px;
}

</style>
<?php
db::order_by("siteName");
$siteR	= db::get("site")->result();
foreach($siteR as $row) $sites[$row['siteID']] = $row['siteName'];
?>

<table style="margin-top:30px;margin-bottom:20px;">
	<tr>
		<td>Pi1M</td><td>: <?php echo form::select("site",$sites,"onchange='window.location.href = \"?site=\"+this.value;'",request::get("site"));?> <?php echo $total;?></td>
	</tr>
</table>
<form method='post'>
<table class='tabla'>
<?php
if($res):
$occuR	= model::load("helper")->occupationGroup();
	?>
<?php foreach($res as $row):?>
<tr>
	<td><?php echo $row['userProfileOccupation'];?></td><td><?php echo form::select("user$row[userID]",$occuR);?></td>
</tr>
<?php endforeach;?>
<?php else:?>




<?php endif;?>
</table>
<input type='submit' />
</form>