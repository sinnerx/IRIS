<h3 class="m-b-xs text-black">
<a href='info'>Edit Slider</a>
</h3>
<div class='well well-sm'>
Edit selected slider
</div>
<form method='post' enctype="multipart/form-data">
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-3'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
			<label>1. Slider Name</label>
			<?php echo form::text("siteSliderName","size='40' class='form-control'",$row['siteSliderName']);?>
			<?php echo flash::data('siteSliderName');?>
			</div>
			<div class="form-group">
			<label>2. Slider Link</label>
			<?php echo form::text("siteSliderLink","class='form-control' placeholder=\"A url represents the p1m, make sure it's as clear as possible.\"",$row['siteSliderLink']);?>
			<?php echo flash::data("siteSliderLink");?>
			</div>
			<div class="form-group">
			<label>2. Status</label><br>
			<?php
			echo $row['siteSliderStatus'] == 1?"Active":"In-Active";
			?>
			</div>
		</div>
	</section>
	</div>
	<div class='col-sm-9'>
	<section class='panel panel-default'>
		<div class="panel-body">
			<div class="form-group">
			<label>3. Image</label><br>
			<img width="100%" src='<?php echo url::asset("frontend/images/slider/".$row['siteSliderImage']);?>' />

			<?php echo form::file("siteSliderImage");?>
			</div>
			<?php echo form::submit("Update slider","class='btn btn-primary pull-right'");?>
		</div>

	</section>
	</div>
</div>
</form>