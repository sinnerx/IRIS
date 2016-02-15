<?php
namespace model\localization;

Abstract class Localization
{
	protected $texts;
	public function getText($key)
	{
		$lang	= "ms";

		return $this->texts[$lang.'.'.$key];
	}

	public function replaceArray($key,$array)
	{
		$textR	= $this->getText($key);

		if(!is_array($textR))
			return false;

		$newText	= Array();
		foreach($array as $k=>$val)
		{
			$newText[$k]	= isset($textR[$k])?$textR[$k]:$val;
		}

		return $newText;
	}
}



?>