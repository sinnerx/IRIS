<?php
namespace model\activity;

class Training
{
	public function type($no = null)
	{
		## temporarily empty.
		$arr	= Array(
			0=>"Others",
			1=>"Digital Literacy",
			2=>"Productivity Tools",
			3=>"Multimedia Skills",
			4=>"Soft Skills",
			5=>"Online Marketing Skills",
			6=>"Entrepreneurship Skills",
			7=>"Ethical & Smart Living"
			);

		return $no != null?$arr[$no]:$arr;
	}
}


?>