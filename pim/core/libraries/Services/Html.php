<?php

class Html
{
	public function link($text,$href = "#")
	{
		$href	= $href != "#"?url::base($href):"#";
		return "<a href='$href'>$text</a>";
	}
}

?>