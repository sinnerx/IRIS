<?php
namespace model\activity;
use db, model, pagination;
class Event
{
	public function type($id = null)
	{
		$arr	= Array(
					1=>"Lawatan",
					2=>"Hari Terbuka Pi1M",
					3=>"Perasmian Pi1M",
					4=>"Kempen & Promosi",
					5=>"Kaji Selidik",
					6=>"Pemeriksaan Audit",
					7=>"Program Bersama Komuniti",
					99=>"Lain-lain"
						);

		return $id?$arr[$id]:$arr;
	}
}



?>