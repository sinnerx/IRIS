<h3 class='m-b-xs text-black'>
Edit type of training
</h3>
<div class='well well-sm'>
Add a new type of training. Be warned that changing the type will also affect how it's going to looks like for the past training based on this type.
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
					<?php echo form::text("trainingTypeName","class='form-control' placeholder='For example ICT training'",$row['trainingTypeName']);?>
					<?php echo flash::data('trainingTypeName');?>
					</div>
				</div>
				<label style="padding-top:10px;">Description</label>
					<div class='row'>
						<div class='col-sm-9'>
						<?php
						echo form::textarea("trainingTypeDescription","class='form-control' placeholder='Describte this type.'",$row['trainingTypeDescription']);
						?>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-3'>
						<input type='submit' class='btn btn-primary' />
					</div>
				</div>
			</div>
		</section>
		</div>
	</div>
</form>