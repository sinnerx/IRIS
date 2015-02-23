<?php
/**
 * A super simple orm base model to satisfy pim project.
 */
class Origami
{
	protected $table;
	protected $primary;
	protected $prefix;
	protected $exception = array();

	public $modelData = array(
		'name'=> null,
		'isNew'=>false,
		'initate'=>false,
		'changedKeys'=>array(),
		'attributes'=>array()
		);

	public function save()
	{
		if(count($this->modelData['changedKeys']) == 0)
			return $this;

		foreach($this->modelData['changedKeys'] as $column)
		{
			$data[$column] = $this->modelData['attributes'][$column];
		}

		if(count($this->exception))
		{
			foreach($this->exception as $key)
				unset($data[$key]);
		}

		unset($data['table']);
		unset($data['primary']);

		$primaryCol = $this->primary;

		if(isset($data[$primaryCol]))
		{
			unset($data[$primaryCol]);
		}

		if(!$this->modelData['isNew'])
		{
			db::where($primaryCol, $this->$primaryCol)->update($this->table, $data);

			return $this;
		}
		else
		{
			db::insert($this->table, $data);
			$lastID = db::getLastID($this->table, $primaryCol);

			$this->modelData['isNew'] = false;
			$this->$primaryCol = $lastID;

			return $this;
		}
	}

	public function setTable($table)
	{
		$this->table = $table;
	}

	public function setPrimary($primary)
	{
		$this->primary = $primary;
	}

	public function setModelName($name)
	{
		$this->modelData['name'] = $name;
	}

	public function __set($column, $val)
	{
		$column = $this->prefix($column);
		
		if(!$this->modelData['initiate'] && !in_array($column, $this->modelData['changedKeys']))
			$this->modelData['changedKeys'][] = $column;

		if($this->isNotOrmProperties($column))
		{
			$this->modelData['attributes'][$column] = $val;
		}
	}

	private function isNotOrmProperties()
	{
		return !in_array($column, array('modelData'));
	}

	public function __get($column)
	{
		$column = $this->prefix($column);

		return $this->modelData['attributes'][$column];
	}

	private function prefix($column)
	{
		return $column;
	}

	public function initiateAttribute($key, $val)
	{
		$this->modelData['initiate'] = true;
		$this->$key = $val;
		$this->modelData['initiate'] = false;
	}

	public function set($key, $val = null)
	{
		if(is_array($key))
		{
			foreach($key as $k=>$v)
				$this->set($k, $v);

			return $this;
		}

		$this->$key = $val;
		return $this;
	}

	public function delete()
	{
		$primary = $this->getPrimary();
		db::delete($this->getTable(), array(
			$primary => $this->get($primary)
			));

		return $this;
	}

	public function get($key)
	{
		return $this->$key;
	}

	public function getTable()
	{
		return $this->table;
	}

	public function getPrimary()
	{
		return $this->primary;
	}

	public function getJoin()
	{
		return $this->join;
	}

	/**
	 * @return object as array.
	 */
	public function toArray()
	{
		return $this->modelData['attributes'];
	}

	/**
	 * @return \Origami
	 */
	public function getOne($model, $ref)
	{
		return $this->relate($model, $ref, 'one');
	}

	/**
	 * @return array
	 */
	public function getMany($model, $ref)
	{
		return $this->relate($model, $ref, 'many');
	}

	public function relate($model, $ref, $type)
	{
		$primary = $this->primary;

		$model = model::orm($model);
		$foreignTable = $model->model->getTable();

		$collection = $model->where($foreignTable.'.'.$ref, $this->$ref)->execute();

		if($type == 'one')
			return $collection->getFirst();
		else
			return $collection;
	}
}

?>