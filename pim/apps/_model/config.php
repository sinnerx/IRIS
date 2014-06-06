<?php
namespace model;

class Config
{
	var $id	= 1;
	public function get($column)
	{
		db::where("configID",$this->id);

		return db::get("config")->row();
	}

	public function set($column,$val = null)
	{
		db::where("configID",$this->id)
		->update($column,$val);
	}
}


?>