<?php

/**
 * A collection of Origami, hence the plurality.
 */

class Origamis extends ArrayIterator
{
	public function getFirst()
	{
		if($this->count() == 0)
			return false;

		$this->rewind();
		foreach($this as $orm)
			return $orm;
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