<style type="text/css">
	
.site-information label
{
	display: block;
	padding-left:5px;
	font-weight: bold;
}

.site-information .row .col-sm-12 div
{
	padding-left:5px;
	padding-bottom: 5px;
	border-bottom: 1px dashed #bcc5c0;
}

</style>
<section class='panel panel-default'>
	<div class='panel-heading'>
	<h4 style="line-height:5px;">Site Information - <?php echo $row['siteName'];?></h4>
	<small style='opacity:0.5;'>http://p1m.gaia.my/<?php echo strtolower($row['siteSlug']);?></small>
	</div>
	<div class='panel-body'>
		<div class='row'>
			<div class='col-sm-4 site-information' style="border-right:1px dashed #dddddd;">
				<div class='row'>
				<div class='col-sm-12'>
					<label>Manager</label>
					<div><?php echo ucfirst($row['userProfileFullName']);?></div>
				</div>
				</div>

				<div class='row'>
				<div class='col-sm-12'>
					<label>Phone No.</label>
					<div><?php echo $row['siteInfoPhone'];?></div>
				</div>
				</div>

				<div class='row'>
				<div class='col-sm-12'>
					<label>Fax No.</label>
					<div><?php echo $row['siteInfoFax'];?></div>
				</div>
				</div>

				<div class='row'>
				<div class='col-sm-12'>
					<label>Address.</label>
					<div><?php echo $row['siteInfoAddress'];?></div>
				</div>
				</div>
			</div>
			<div class='col-sm-8' >
			<div class='row'>
				<div class='col-sm-12'>
					<div style='padding:5px;'>
					<div style='font-weight:bold;'>Description</div>
					<?php echo $row['siteInfoDescription'];?>
					</div>
				</div>
			</div>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-4 site-information'>
				
			</div>
		</div>
	</div>
</section>