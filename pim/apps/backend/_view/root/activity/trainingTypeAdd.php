<h3 class='m-b-xs text-black'>
Add Type of Training.
</h3>
<div class='well well-sm'>
Add a new type of training.
</div>
<?php echo flash::data();?>
<form method='post'>
	<div class='row'>
		<div class='col-sm-6'>
		<section class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
				<label>Type of training</label>
				<div class='row'>
					<div class='col-sm-9'>
					<?php
					$readOnly	= $siteName?"readonly='true'":"";
					?>
					<?php echo form::text("trainingTypeName","class='form-control' placeholder='For example ICT training'");?>
					<?php echo flash::data('trainingTypeName');?>
					</div>
					<div class='col-sm-3'>
					<input type='submit' class='btn btn-primary' />
					</div>
				</div>
				</div>
			</div>
		</section>
		</div>
	</div>
</form>