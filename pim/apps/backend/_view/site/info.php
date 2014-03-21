<style type="text/css">



</style>
<h3 class="m-b-xs text-black">
Site Information <a href='<?php echo url::base("site/edit");?>'><span class='fa fa-edit'></span></a>
</h3>
<div class='well well-sm'>
	Information about your managed P1M.
</div>
<!-- Site name, slug and description. -->
<div class='row'>
	<div class='col-sm-6'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<div class='row'>
					<div class='col-sm-6'>
					<label>1. P1m Name</label>
					<div class='row'>
						<div class='col-sm-12'>
						<?php echo $row_site['siteName'];?>
						</div>
					</div>
					</div>
					<div class='col-sm-6'>
					<label>3. State</label>
					<div class='row'>
						<div class='col-sm-12'>
						Selangor
						</div>
					</div>
					</div>
				</div>
			</div>
			<div class="form-group">
			<div class='row'>
				<div class='col-sm-6'>
				<label>2. P1m Slug</label>
				<div class='row'>
					<div class='col-sm-12'>
					http://p1m.gaia.my/<?php echo $row_site['siteSlug'];?>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>
	</section>
	</div>
	<div class='col-sm-6'>
		<section class="panel panel-default">
		<div class="panel-body">
		<div class="form-group">
			<label>3. Site Description</label>
			<div class="row">
			<div class='col-sm-12'>	
				<?php echo $row_site['siteInfoDescription']?$row_site['siteInfoDescription']:"No site description was added yet. Add?";?>
			</div>
			</div>
		</div>
		</div>
		</section>
	</div>
</div>
<div class='row'>
	<div class='col-sm-3'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>4. Phone No.</label>
			<div class='row'>
				<div class='col-sm-12'>
					<?php echo $row_site['siteInfoPhoneNo']?$row_site['siteInfoPhoneNo']:"No phone number added yet.";?>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<label>5. Fax No.</label>
			<div class='row'>
				<div class='col-sm-12'>
					<?php echo $row_site['siteInfoFaxNo']?$row_site['siteInfoFaxNo']:"No fax number added yet.";?>
				</div>
			</div>
		</div>
		</div>
		</section>
	</div>
	<div class='col-sm-9'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>6. Coordinates</label>
			<div class='row'>
				<div class='col-md-3'>
					Latitude : <?php echo $row_site['siteInfoLatitude']?$row_site['siteInfoLatitude']:"null";?>
				</div>
				<div class='col-md-3'>
					Longitude : <?php echo $row_site['siteInfoLatitude']?$row_site['siteInfoLatitude']:"null";?>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<label>7. Address</label>
			<div class='row'>
			<div class='col-md-12'>
			<?php echo $row_site['siteInfoAddress']?$row_site['siteInfoAddress']:"No address was added yet.";?>
			</div>
			</div>
		</div>
		</div>
		</section>
	</div>
</div>