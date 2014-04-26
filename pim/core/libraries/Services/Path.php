<?php

class Path
{
	private function concat_path($first_path,$path)
	{
		return refine_path(trim($first_path,"/").($path?"/".$path:""));
	}

	## path to assets
	public function asset($path = null)
	{
		$path	= self::concat_path("assets",$path);
		return $path;
	}

	## path to apps/_files
	public function files($path = null)
	{
		$path	= self::concat_path("apps/_files",$path);
		return $path;
	}
}


?>