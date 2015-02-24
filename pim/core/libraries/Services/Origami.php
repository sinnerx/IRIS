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
	protected $relationCaches = array();

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
	 * Relational function
	 */
	public function withQuery($callback)
	{
		$callback(db::$instance);

		return $this;
	}

	/**
	 * @return \Origami
	 */
	public function getOne($model, $ref)
	{
		return $this->relate($model, $ref, 'one');
	}

	/**
	 * @return \Origamis
	 */
	public function getMany($model, $ref)
	{
		return $this->relate($model, $ref, 'many');
	}

	/**
	 * Relate with other origami.
	 * @param mixed.
	 *	- string (model name)
	 *  - array (create origami anonymously : first is table name, second is it's primary key)
	 * @param string foreign key
	 * @param string type of relation
	 * @return mixed. if relation type is one, return model object, else return \Origamis
	 */
	public function relate($model, $ref, $type)
	{
		$modelKey = is_array($model) ? serialize($model) : $model;

		if(isset($this->relationCaches[$type][$modelKey][$ref]))
			return $this->relationCaches[$type][$modelKey][$ref];

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