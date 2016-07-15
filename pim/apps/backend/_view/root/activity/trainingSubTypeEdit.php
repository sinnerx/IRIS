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
				<label style="padding-top:10px;">Type of training</label>
					<div class='row'>
						<div class='col-sm-9'>
							<?php echo form::select("trainingType",$trainingTypeR,"onchange='activity.showSubType(this.value);' class='form-control'",$row['trainingSubTypeParent']);?>
						</div>
					</div>					
				<label style="padding-top:10px;">Sub type of training</label>
				<div class='row'>
					<div class='col-sm-9'>
					<?php
					$readOnly	= $siteName?"readonly='true'":"";
					?>
					<?php echo form::text("trainingSubTypeName","class='form-control' placeholder='For example ICT training'",$row['trainingSubTypeName']);?>
					<?php echo flash::data('trainingSubTypeName');?>
					</div>
				</div>
				<label style="padding-top:10px;">Description</label>
					<div class='row'>
						<div class='col-sm-9'>
						<?php
						echo form::textarea("trainingSubTypeDescription","class='form-control' placeholder='Describe this type.'",$row['trainingSubTypeDescription']);
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