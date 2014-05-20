<?php echo $what;?>
<style type="text/css">
	
body
{
	background: #eaeaea;
}

*
{
	font-family: calibri;
}

.wrapper
{
	margin: auto;
	width:40%;
	background: white;
	padding:3px;
}

.existing-test
{
	float:left;
}
.wrapper-title
{
	padding:3px;
}

.wrapper-header, .wrapper-body
{
	padding:3px;
}

#model-name
{
	width:20%;
}

#model-function
{
	width:78%;
}

pre
{
	font-size:0.9em;
}

td
{
	vertical-align: top;
}

</style>
<script type="text/javascript">
	
function change(val)
{
	var val	= !val?document.getElementById('model').value:val;
	window.location.href	= "<?php echo url::base();?>/model/"+val;
}

window.onload = function()
{
	//document.getElementById('model').focus();
}

</script>
<form method='get' onsubmit="change();return false;">
<div class='existing-test'>
<div class='wrapper-title'>
Existing testdata : controller=tests:model@testdata
</div>
<div class='wrapper-body'>
	<table>
		<?php
		$data	= controller::load("model","testdata");

		if($data):
		foreach($data as $key=>$params)
		{
			echo "<tr><td><a href='javascript:void(0);' onclick='change(\"$key\");'>$key</a></td></tr>";
		}
		else:
			echo "none";
		endif;
		?>

	</table>
</div>
</div>
<div class='wrapper'>
	<div class='wrapper-title'>Model Tester</div>
	<div class='wrapper-header'>
	<?php echo form::text("model","style='width:100%;padding:3px;' tabindex=1",$model);?>
	</div>
	<div class='wrapper-body'>
	<?php
	if($error):
	echo $error;
	else:
	?>
	<u>Result</u>
	<table>
		<tr>
			<td width="100px">Test Param</td><td>: <?php echo count($param) == 0?"null":json_encode($param);?></td>
		</tr>
		<tr>
			<td>Return Type</td><td>: <?php echo gettype($value);?></td>
		</tr>
		<?php
		if($frozen_sql):?>
		<tr>
			<td colspan="2">Frozen SQL :</td></tr>
		<tr>
			<td colspan="2"><?php echo "<pre>";print_r($frozen_sql);echo "</pre>";?></td>
		</tr>
		<?php endif;?>
		<tr>
			<td style='vertical-align:top;' colspan="2">Return Value :</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<?php
			if(is_array($value)) ## return array.
			{
				echo "<pre>";print_r($value);echo "</pre>";
			}
			else ## return string.
			{
				echo $value?:"null";
			}
			?>
			</td>
		</tr>
	</table>
<?php endif;?>
	</div>

</div>
</form>