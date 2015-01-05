<style type="text/css">
	.modal-dialog{
		width:800px;
	}

	#userdetail-info-main th, #userdetail-info-main td, #userdetail-info-additional th, #userdetail-info-additional td
	{
		width: 25%;
	}

	.mb-content table tr:first-child td, .mb-content table tr:first-child th
	{
		border-top:0px;
	}

	.userdetail-subtitle
	{
		padding:5px 5px 5px 5px;
		font-size:1.1em;
		text-decoration: underline;
		font-weight: bold;
	}

	#userdetail-info-main
	{
		margin-bottom: 20px;
	}

	#introductional-container img
	{
		max-width: 100%;
	}


</style>
<script type="text/javascript">
 
var options =  <?php echo json_encode($data['data2'], JSON_NUMERIC_CHECK); ?>;

$(function() {


$('#containerX').highcharts(options)

});
</script>





<div class="modal-dialog">
	<div class="modal-content">
		<div id="containerX" style="min-width: 750px; height: 400px; margin: 0 auto"></div>

	</div>
</div>