<script type="text/javascript">
	
var report = new function()
{
	this.generate = function()
	{
		var year	= $("#year").val();
		var month	= $("#month").val();
		window.location.href = pim.base_url+"report/quarterlyReport/";
	}
}

</script>
<?php

//var_dump($data);

//var_dump($data[3]["Training"]);

?>
<input type="button" id="wordbtn" value="Generate words file" onclick='report.generate();'>


<p>


<form method="post" action="generateZipQuarterReport">
<input type="submit" id="submitbtn" value="Generate zip file">
</form>

