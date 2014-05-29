<?php
namespace model\activity;

class Training
{
	public function type($no = null)
	{
		## temporarily empty.
		$arr	= Array(
			0=>"Default"
			);

		return $no != null?$arr[$no]:$arr;
	}
}


?>