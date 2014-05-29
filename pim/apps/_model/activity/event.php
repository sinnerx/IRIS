<?php
namespace model\activity;
use db, model, pagination;
class Event
{
	public function type($id = null)
	{
		$arr	= Array(
					1=>"Lawatan"
						);

		return $id?$arr[$id]:$arr;
	}
}



?>