

.main-form-submit input
{
	font-size:20px;
	background: #6abcd7;
	color:white;
	border:0px;
}
.main-form-submit
{
	text-align: right;
}

.body-main-wrapper
{
	height:100%;
	overflow: auto;
}

.main-table-form
{
	width:90%;
}
.main-table-form tr td
{
	width:49%;
	padding-bottom: 20px;
}


.main-table-form tr td div:nth-child(1)
{
	font-weight: bold;
}
.main-table-form tr td div:nth-child(2)
{
	opacity: 0.7;
}

.main-table-form tr td input, .main-table-form tr td textarea
{
	border:1px solid #acbad2;
}

<table class='main-table-form' style='display:none;'>
		<tr>
			<td>
				<div>1. P1m Name?</div>
				<div></div>
				<div><?php echo form::text("siteName","size='40' onkeyup='prepareSlug();'");?> <?php echo form::select("stateID",Array(),"","","[CHOOSE STATE]");?></div>
			</td>
			<td>
				<div>2. P1m Slug</div>
				<div><?php echo flash::data("siteSlug","A url represents the p1m, make sure its as clear as possible.");?></div>
				<div>http://p1m.gaia.my/ <?php echo form::text("siteSlug","style='width:250px;'");?></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>3. Manager</div>
				<div><?php echo flash::data("manager","The p1m manager's email for this p1m.");?></div>
				<div><?php echo form::text("manager");?></div>
			</td>
		</tr>
	</table>
	
	<table class='main-table-form' style='display:none;'>
		<tr>
			<td>
				<div>3. Phone No.</div>
				<div>P1m phone number.</div>
				<div><?php echo form::text("sitePhoneNo");?></div>
			</td>
			<td>
				<div>4. Fax No.</div>
				<div>P1m Fax number.</div>
				<div><?php echo form::text("siteFaxNo");?></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>5. Address</div>
				<div>Real address to the p1m.</div>
				<div><?php echo form::textarea("siteAddress","style='width:500px;height:80px;'");?></div>
			</td>
			<td>
				<div>6. Description</div>
				<div>Description of the p1m OR you can leave it to the manager.</div>
				<div><?php echo form::textarea("siteDescription","style='width:500px;height:80px;'");?></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>7. Coordinates</div>
				<div>Coordinate of the p1m</div>
				<div>Latitude : <?php echo form::text("siteLatitude");?>, Longitude : <?php echo form::text("siteLongitude");?></div>
			</td>
			<td>
				<div class='main-form-submit'>
					<?php echo form::submit("Add new site!");?>
				</div>
			</td>
		</tr>
	</table>