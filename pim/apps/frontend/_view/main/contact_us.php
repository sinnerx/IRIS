<style type="text/css">
	
.page-info
{
	font-size:18px;
}

.page-info label
{
	font-weight: bold;
	margin-top:10px;
	display: block;
}

</style>
<h3 class='block-heading'>Hubungi Kami</h3>

<div class="block-content clearfix">
	<div class="page-content page-info">
	<div>
	Maklumat kami untuk dihubungi :
	</div>
		<label>Emel Pengurus</label>
		<div><?php echo $managerEmail?$managerEmail:"-";?></div>
		<label>No. Telefon</label>
		<div><?php echo $row['siteInfoPhone'];?></div>
		<label>No. Fax</label>
		<div><?php echo $row['siteInfoFax']?$row['siteInfoFax']:"-";?></div>
		<label>Address</label>
		<div><?php echo $row['siteInfoAddress']?$row['siteInfoAddress']:"-";?></div>
	</div>
</div>