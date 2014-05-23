<?php
echo flash::data("_main");?>

<form method='post'>
<table>
	<tr>
		<td>First field</td><td>: <?php echo form::text("username");?> <?php echo flash::data("username");?></td>
	</tr>
	<tr>
		<td>my Email</td><td>: <?php echo form::text("myEmail");?> <?php echo flash::data("myEmail");?></td>
	</tr>
	<tr>
		<td>Second Email</td><td>: <?php echo form::text("secondEmail");?> <?php echo flash::data("secondEmail");?></td>
	</tr>
</table>
<?php echo form::submit();?>
</form>