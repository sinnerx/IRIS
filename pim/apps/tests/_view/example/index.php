<div style='position:absolute;right:0px;'>
	<div>Menu</div>
	<ul>
	<?php

	foreach($listtest as $method=>$text)
	{
		$url	= url::base("{controller}/$method");
		echo "<li><a href='$url'>$text</a></li>";
	}

	?>
	</ul>
</div>