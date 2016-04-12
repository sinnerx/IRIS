
<script src="<?php echo url::asset("_scale/js/qrcode/jquery.qrcode-0.12.0.min.js");?>"></script>
<script>
$(document).ready(function(){
	$("#ic-qr").qrcode({
    "size": 100,
    "color": "#3a3",
    "text": "<?php echo $_GET['userIC']; ?>"
});
});
</script>
<h3 class="m-b-xs text-black">
<center><a href='info'>Member Information</a></center>
</h3>

<?php echo flash::data();?>
<form method='post'>




 <center><div class='row'>
	<div class='col-sm-12'>
		<div class='panel panel-default'>
		<div class='panel-body'>
		<label>Member QR Code</label><p>	
		<div class='form-group'  id="ic-qr">

		</div>
		<label>Identification No:</label>
			<?php echo $_GET['userIC']; ?><br/>
		
		<label>Name:</label>
			<?php echo $_GET['userProfileFullName']; ?> <?php echo $_GET['userProfileLastName']; ?><br/>
			<label>Site Name:</label>
			<?php print_r($site[0]["siteName"]);?>
		
		</div>
		</div>
		</div>		
	</div>

</div></center>

</form>