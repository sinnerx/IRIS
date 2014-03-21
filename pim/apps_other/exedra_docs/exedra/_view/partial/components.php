<style type="text/css">
	
#components-starting
{
	padding:0px 10px 5px 10px ;
}
.starting-title
{
	font-size:1.5em;
	opacity: 0.7;
	text-align: center;
}
.starting-content
{
	font-size: 1.5em;
	opacity: 0.7;
	text-align: center;
}

</style>
<div class='components-tabs'>
	<div id='components-tabs-starting' onclick='tabs.show("starting")'>Getting Started</div>
	<div id='components-tabs-mvc' onclick='tabs.show("mvc");'>M.V.C</div>
	<div id='components-tabs-core' onclick='tabs.show("core");'>Core Components</div>
	<div id='components-tabs-db' onclick='tabs.show("db");'>Query Builder</div>
	<div id='components-tabs-pathurl' onclick='tabs.show("pathurl");'>Path and URL</div>
</div>
<?php /*
<div class='components-list' id='components-starting'>
<div class='starting-title'>
Wanna give it a try?
</div>
<div class='starting-content'>
	<a href='<?php echo url::base("documentation/index");?>'>Launch Documentation</a>
</div>
</div>*/?>
<ul class='components-list' id='components-starting'>
	<li>Documentation <label>index, installation, requirement</label></li>
	<li>F.A.Q. <label>list, ask, suggestion</label></li>
</ul>
<?php
foreach($componentR as $name=>$listR)
{
	list($name,$title)	= explode("|",$name);
	
	echo "<ul class='components-list' id='components-$name'>";

	foreach($listR as $compName=>$compApi)
	{
		#$compApi	= str_replace(",", ", ", $compApi);
		#$compApiR	= explode(",",$compApi);

		$apiR	= Array();
		if(strpos($compApi, ":") !== false)
		{
			list($class,$compApi)	= explode(":",$compApi);
		}

		foreach(explode(",",$compApi) as $api)
		{
			$apiR[]	= "<a href='".url::base("docs/$name/$class#$api")."'>$api</a>";
		}

		echo "<li onclick='window.location = \"".url::base("docs/$name/$class")."\";'>$compName <label>API : ".implode(", ",$apiR)."</label></li>";
	}

	echo "</ul>";
}
?>