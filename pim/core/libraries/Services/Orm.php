<?php

## an orm builder.
class Orm
{
	public $model;
	protected $modelName;
	protected $tableName;
	protected $primary;
	protected $anonymous;

	public function __construct($model, $primaryKey = null)
	{
		if(is_array($model) || strpos($model, 'table:') === 0)
		{
			$this->anonymous = true;

			if(is_array($model))
			{
				$tableName = $model[0];
				$primaryKey = $model[1];
			}
			else
			{
				$tableName = str_replace('table:', '', $model);
			}

			$this->tableName = $tableName;
			$this->primary = $primaryKey;

			$this->model = new \Origami;
			$this->model->setTable($this->tableName);
			$this->model->setPrimary($primaryKey);
		}
		else
		{
			$this->anonymous = false;

			$this->modelName = $model;
			$this->model = model::load($model);
			
			// set table and primary key.
			$this->tableName = $this->model->getTable();
			$this->primary = $this->model->getPrimary();
		}

		$this->validate();

		$this->model->modelData['name'] = $model;
	}

	public function __call($func, $args)
	{
		call_user_func_array(array(db::$instance, $func), $args);

		return $this;
	}

	public function create()
	{
		$this->model->modelData['isNew'] = true;
		return $this->model;
	}

	/**
	 * Final
	 */
	public function find($id = null)
	{
		$primary = $this->primary;
		$table = $this->tableName;

		$this->where($table.'.'.$primary, $id);

		$collection =  $this->execute($primary, $id);
		return $collection[$id];
	}

	## validate the model.
	public function validate()
	{
		$class = new ReflectionClass($this->model);
		if(!is_subclass_of($this->model, "\Origami") && $class->name != "Origami")
			error::set($class->name." ORM Initiating Failure", "The model has not extended Origami");

		if(!$this->primary)
			error::set($class->name." ORM Initiating Failure", "This model has no primary column configured");

		if(!$this->tableName)
			error::set($class->name." ORM Initiating Failure", "This model has no table name configured.");

		return true;
	}

	public function paginate(array $config)
	{
		db::$instance->from($this->tableName);
		$config['totalRow'] = db::$instance->num_rows();
		pagination::initiate($config);

		if(isset($config['limit']))
			db::$instance->limit($config['limit'], pagination::recordNo() - 1);
		
		return $this;
	}

	public function execute()
	{
		$table = $this->tableName;

		$primary = $this->primary;

		if($join = $this->model->getJoin())
		{
			foreach($join as $foreignTable=>$foreignID)
			{
				db::join($foreignTable, $table.'.'.$primary.' = '.$foreignTable.'.'.$foreignID);
			}
		}

		$dbResult = db::get($table);

		$result = $dbResult->result($primary);

		$collection = array();

		foreach($result as $id=>$row)
		{
			if($this->anonymous)
			{
				$model = new \Origami;
				$model->setPrimary($this->primary);
				$model->setTable($this->tableName);
			}
			else
			{
				$model = model::create($this->modelName);
			}

			foreach($row as $key=>$val)
			{
				$model->initiateAttribute($key, $val);
			}

			$collection[$id] = $model;
		}

		return $collection;
	}
}

?>