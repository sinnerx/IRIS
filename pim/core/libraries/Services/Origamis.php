<?php

/**
 * A collection of Origami, hence the plurality.
 */

class Origamis extends ArrayIterator
{
	protected $table;

	protected $primaryKey;

	public function configure($table, $primaryKey)
	{
		$this->table = $table;
		$this->primaryKey = $primaryKey;
	}

	public function getFirst()
	{
		if($this->count() == 0)
			return false;

		$this->rewind();
		foreach($this as $orm)
			return $orm;
	}

	public function getAllId()
	{
		$ids = array();

		$primary = $this->primaryKey;

		if($this->count() > 0)
			foreach($this as $origami)
				$ids[] = $origami->$primary;

		return $ids;

	}

	public function toList($key, $column)
	{
		$new = array();

		if($this->count() > 0)
			foreach($this as $origami)
				$new[$origami->$key] = $origami->$column;

		return $new;
	}
}



?>