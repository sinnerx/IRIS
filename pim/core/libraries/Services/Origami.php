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
	protected $db;

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
			db::create()->where($primaryCol, $this->$primaryCol)->update($this->table, $data);

			return $this;
		}
		else
		{
			db::create()->insert($this->table, $data);
			$lastID = db::create()->getLastID($this->table, $primaryCol);

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

		db::create()->delete($this->getTable(), array(
			$primary => $this->getAttribute($primary)
			));

		return $this;
	}

	public function getAttribute($key)
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
	public function toArray(array $keys = array())
	{
		if(count($keys) > 0)
		{
			$newArr = array();
			foreach($keys as $k => $v)
			{
				$key = is_string($k) ? $k : $v;

				$newArr[$v] = $this->modelData['attributes'][$key];
			}

			return $newArr;
		}
		
		return $this->modelData['attributes'];
	}

	/**
	 * Relational function
	 * Bad singletonian method.
	 */
	public function withQuery($callback)
	{
		if(!$this->db)
			$this->db = \db::create();

		$callback($this->db);

		return $this;
	}

	public function clearQuery()
	{
		$this->db = null;
	}

	/**
	 * @return \Origami
	 */
	public function getOne($model, $ref, $foreignKey = null)
	{
		if($foreignKey)
			$ref = array('local' => $ref, 'foreign' => $foreignKey);

		return $this->relate($model, $ref, 'one');
	}

	/**
	 * @return \Origamis
	 */
	public function getMany($model, $ref, $foreignKey = null)
	{
		if($foreignKey)
			$ref = array('local' => $ref, 'foreign' => $foreignKey);
		
		return $this->relate($model, $ref, 'many');
	}

	/**
	 * Relate with other origami.
	 * @param mixed.
	 *	- string (model name)
	 *  - array (create origami anonymously : first is table name, second is it's primary key)
	 * @param string|array foreign key
	 * @param string type of relation
	 * @return mixed. if relation type is one, return model object, else return \Origamis
	 */
	public function relate($model, $ref, $type)
	{
		$baseTable = $this->table;
		$modelKey = is_array($model) ? serialize(array_merge(array($baseTable), $model)) : $baseTable.'_'.$model;

		if(is_string($ref))
		{
			$localKey = $ref;
			$foreignKey = $ref;
		}
		else
		{
			$localKey = $ref['local'];
			$foreignKey = $ref['foreign'];
		}

		// global caches
		if(\RuntimeCaches::has($cacheHash = md5($modelKey.'_'.$this->$localKey.'_'.$type)))
		{
			// db::clear();
			$this->clearQuery();
			return \RuntimeCaches::get($cacheHash);
		}

		if(isset($this->relationCaches[$type][$modelKey][$localKey]))
			return $this->relationCaches[$type][$modelKey][$localKey];

		$primary = $this->primary;

		$model = model::orm($model);

		if($this->db)
			$model->setDbInstance($this->db);

		$foreignTable = $model->model->getTable();

		$collection = $model->where($foreignTable.'.'.$foreignKey, $this->$localKey)->execute();

		$this->clearQuery();

		if($type == 'one')
		{
			$result = $collection->getFirst();
		}
		else
		{
			$result = $collection;
		}

		\RuntimeCaches::set($cacheHash, $result);

		return $result;
	}
}

?>