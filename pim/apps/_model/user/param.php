<?php
namespace model\user;

class Param
{
	public function title()
	{
		$arrR	= Array(
					1=>"Mr",
					2=>"Mrs",
					3=>"Miss",
					4=>"Datuk",
					5=>"Rev",
					99=>"Other"
						);

		return $no?$arrR[$no]:$arrR;
	}

	public function gender($no = null)
	{
		$arrR	= Array(
					1=>"Male",
					2=>"Female"
						);

		return $no?$arrR[$no]:$arrR;
	}
}