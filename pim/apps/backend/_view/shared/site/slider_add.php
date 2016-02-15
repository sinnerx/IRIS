<h3 class="m-b-xs text-black">
<a href='info'>Add Page Slider</a>
</h3>
<div class='well well-sm'>
Please complete the required form.
</div>
<div class='row'>
	<div class='col-sm-3'>
		<div class='panel panel-default'>
			<div class='panel-body'>
				<div class='form-group'>
					<label>1. Slider Name</label>
					<?php echo form::text("siteSliderName","class='form-control' placeholder='Name of slider'");?>
				</div>
				<div class='form-group'>
					<label>2. Choose Image</label>
					<span class="btn btn-file">
					<?php echo form::file("siteSliderImage","class='form-control'");?>
					</span>
				</div>
			</div>

		</div>
	</div>
	<div class='col-sm-6'>
		<div class='panel panel-default'>
			<div class='panel-body'>
				<div class='form-group'>
					<label>3. Redirection link</label>
					<?php echo form::text("siteSliderLink","class='form-control' placeholder='Link to be redirection after user clicks it.'");?>
				</div>
			</div>
		</div>
	</div>
</div>