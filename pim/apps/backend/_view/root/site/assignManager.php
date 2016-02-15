<h3 class="m-b-xs text-black">
Assign Manager
</h3>
<div class='well well-sm'>
Please choose one from the existing manager.
</div>
<div class='row'>
	<div class='col-sm-6'>
	<form method="post">
	<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
		<label>1. Site</label>
		<div><?php echo $row['siteName'];?>, <?php echo $state;?></div>
		</div>

		<div class='form-group'>
		<label>1. Manager</label>
		<div><?php echo form::select("userID",$userR);?></div>
		</div>

		<div class='form-group'>
		<?php echo form::submit("Assign","class='btn btn-primary pull-right'");?>
		<?php echo flash::data("userID");?>
		</div>
		</div>
	</section>
	</form>
	</div>
</div>