<?php
/***
Author : eimihar.rosengate.com

SIMPLE DOCUMENTATION :
1. SQL SELECT BUILDER
2. SQL PREPARATION
3. EXECUTION
4. RESULT PREPARATION
5. UTILITIES

***/
class db
{
	static $instance	= Null;

	public static $connection = null;

	public static function getConnection()
	{
		if(!self::$connection)
			self::$connection = new PDO("mysql:host=".apps::config('db_host').";dbname=".apps::config('db_name'), apps::config('db_user'), apps::config('db_pass'));

		return self::$connection;
	}

	/**
	 * Instanctiate new db_instance
	 * But statically coupled with apps class. bad idea.
	 */
	public static function create($table = null)
	{
		$db = new Db_instance(self::getConnection());

		// $db->connect(apps::config('db_host'), apps::config('db_user'), apps::config('db_pass'), apps::config('db_name'));

		if($table)
			$db->from($table);

		return $db;
	}

	public static function __callStatic($method,$args)
	{
		if(!self::$instance)
			self::$instance	= new Db_instance(self::getConnection());

		if($method != "connect")
		{
			if(!self::$instance->db)
			{
				error::set("DB","No db connection has been made yet.");
				return;
			}
		}
		
		return call_user_func_array(Array(self::$instance,$method), $args);
	}
}

class Db_instance
{
	private $columnR	= Array();
	private $tableR		= Array();
	private $whereR		= Array();
	private $limit		= "";
	private $joinR		= Array();
	private $orderbyR 	= Array();
	private $groupBy	= null;
	public $db			= Null;
	private $result		= Null;
	private $sql		= Null;
	private $param		= Array();
	private $paramVal	= null;
	private $freeze		= false;
	private $frozensql	= Array();
	private $cached		= Array();

	public function __construct($connection)
	{
		$this->db = $connection;
	}

	public function connect($host,$user,$pass,$db)
	{
		$this->db = \db::getConnection();/*
		try{
			$this->db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		}catch(PDOException $e)
		{
			error::set("PDO Error",$e->getMessage());
		}*/
	}

	public function checkQuery()
	{
		//check if got a minimal query to be run.
		if(count($this->tableR) > 0)
		{
			return true;
		}

		return false;
	}

	### 1. SQL SELECT BUILDER ###
	public function select()
	{
		$this->clearResult();

		$args	= func_get_args();
		$args	= count($args) == 1 && $args[0] == null?Array("*"):$args;

		## get all args.
		foreach($args as $colR)
		{
			$colR	= !is_array($colR)?explode(",",$colR):$colR;

			foreach($colR as $col)
			{
				$this->columnR[]	= $col;
			}
		}

		return $this;
	}

	public function from($tables)
	{
		$this->clearResult();

		$tableR	= !is_array($tables)?explode(",",$tables):$tables;

		foreach($tableR as $table)
		{
			$this->tableR[]	= trim($table);
		}

		return $this;
	}

	public function where($key,$val = "_noinput")
	{
		$this->clearResult();

		$this->_where($key,$val,"AND");

		return $this;
	}

	public function or_where($key,$val = "_noinput")
	{
		$this->clearResult();

		$this->_where($key,$val,"OR");

		return $this;
	}

	private function _where($key,$val = "_noinput" ,$type = "AND")
	{
		$this->clearResult();

		$pre_delimiter	= count($this->whereR) > 0?" $type ":"";

		if(is_array($key))
		{
			foreach($key as $col=>$val)
			{
				## find operator on key
				$keyR	= explode(" ",trim($col));
				$operator	= "=";
				$key	= $col;
				if(count($keyR) > 1)
				{
					$key		= $keyR[0];
					$param2		= trim($keyR[1]);
					$operator	= $param2 == ""?$operator:$param2;

					## check NOT IN
					if($param2 == "NOT" && isset($keyR[2]) && $keyR[2] == "IN")
					{
						$operator = "NOT IN";
					}
				}

				## OLD
				#$val	= $operator == "IN"?$val:"'$val'";

				## Parameterize.
				## if operator is IN, no need parameterize.
				if($operator == "IN" || $operator == "NOT IN")
				{
					$val			= $val;
				}
				else
				{
					$this->param[]	= $val;
					$val			= "?";
				}

				$cond[]	= "$key $operator $val";
			}

			$cond	= implode(" $type ",$cond);
		}
		## plain cond
		else if($val === "_noinput")
		{
			$cond	= trim($key);
		}
		else if(is_array($val))
		{
			#$cond	= str_replace("?",$val,$key);
			$cond	= $key;

			## Parameterize
			foreach($val as $value)
			{
				$this->param[]	= $value;
			}

			## if key got one. obviously got not prepared first value. so we set as 'IN' operator.
			$condR	= explode(" ",$cond,2);
			if(count($condR) == 1)
			{
				## create new condition with question mark for parameterized query.
				$cond	= "$cond IN (".implode(",",array_fill(0,count($val),"?")).")";
			}
			else if(in_array($condR[1],Array("NOT IN","!=")))
			{
				$condR[1] = $condR[1] == "!="?"NOT IN":$condR[1];
				$cond	= "$condR[0] $condR[1] (".implode(",",array_fill(0,count($val),"?")).")";
			}
		}
		else ## is a single value and value isn't array.
		{
			## find operator on key
			$keyR	= explode(" ",trim($key));
			$operator	= "=";
			if(count($keyR) > 1)
			{
				$key		= $keyR[0];
				$operator	= trim($keyR[1]);
				$operator	= $operator == ""?$operator:$operator;
			}

			## check NOT IN
			if($operator == "NOT" && isset($keyR[2]) && $keyR[2] == "IN")
			{
				$operator = "NOT IN";
			}

			## parameterize
			if($operator != "IN" && $operator != "NOT IN")
			{
				$this->param[]	= $val;
				$val			= "?";
			}

			$cond	= "$key $operator $val";
		}

		$this->whereR[]		= $pre_delimiter."(".$cond.")";
	}

	public function group_by($col, $order = Null)
	{
		$this->groupBy = null;

		$this->clearResult();

		if(is_array($col))
			$col = implode(", ", $col);

		$this->groupBy = $col;

		if($order)
			$this->groupBy .= ' '.$order;

		return $this;
	}

	public function order_by($col,$type = Null)
	{
		$this->clearResult();

		if(!$type)
		{
			## if order in array,
			if(is_array($col))
			{
				foreach($col as $order)
				{
					$this->order_by($order);
				}
			}
			## else if just a string.
			else
			{
				$this->orderbyR[]	= trim($col);
			}
		}
		## standard parameter, $col and type (asc or desc)
		else
		{
			$this->orderbyR[]	= $col." ".strtoupper(trim($type));
		}

		return $this;
	}

	public function limit($limit,$offset = Null)
	{
		$this->clearResult();

		$this->limit	= "LIMIT ".(!$offset?$limit:"$offset,$limit");

		return $this;
	}

	public function join($table,$cond,$type = "LEFT JOIN")
	{
		$this->clearResult();

		$this->joinR[]	= "$type $table ON $cond";

		return $this;
	}

	public function innerjoin($table,$cond,$type = "INNER JOIN")
	{
		$this->clearResult();

		$this->joinR[]	= "$type $table ON $cond";

		return $this;
	}
	### SQL SELECT BUILDER, END ###
	### 2. SQL PREPARATION ###
	public function prepareSQL($count = false)
	{
		$columns	= !$count?count($this->columnR) == 0?"*":implode(",",$this->columnR):"count(*) as num_rows";
		$table		= implode(",",$this->tableR);
		$where		= count($this->whereR) == 0?"":"WHERE ".implode("",$this->whereR);
		$order_by	= count($this->orderbyR) == 0?"":"ORDER BY ".implode(", ",$this->orderbyR);
		$limit		= $this->limit;
		$join		= count($this->joinR) == 0?"":implode(" ",$this->joinR);
		$group_by	= $this->groupBy == null?"":"GROUP BY ".$this->groupBy;

		$this->sql	= "SELECT $columns FROM $table $join $where $group_by $order_by $limit";

		return $this->sql;
	}

	### 3. EXECUTION ###
	## Execute based on prepared parameter.
	public function get($table = Null,$where = Null, $limit = Null,$offset = Null)
	{
		if($table && !in_array($table,$this->tableR))
		{
			$this->tableR[]	= $table;
		}

		if($where)
		{
			call_user_func_array(Array($this,"_where"),is_array($where)?$where:Array($where));
		}

		if($limit)
		{
			$this->limit($limit,$offset);
		}

		## Execute
		$this->prepareSQL();

		$this->execute($table === false?false:true);
		return $this;
	}

	/**
	 * Build the query through the given closure, with the passed instance of db. 
	 * @param \Callback callback
	 */
	public function prepare($callback)
	{
		$callback($this);

		return $this;
	}

	## Custom SQL Execution
	public function query($sql,$bindParam = false)
	{
		$this->sql		= $sql;
		if(is_array($bindParam))
		{
			$statement	= $this->db->prepare($this->sql);

			$no = 1;
			foreach($bindParam as &$val)
			{
				$statement->bindParam($no,$val);
				$no++;
			}

			## execute statement.
			$statement->execute();

			## and store pdo object in result.
			$this->result	= $statement;
			return $this;
		}
		## Execute SQL and store result using PDO fetchALL (ASSOC)
		$this->execute();
		return $this;
	}

	## Main execution method.
	private function execute($flag = true)
	{
		$params = $this->param;
		
		## clear sql, only with true flag.
		if($flag === true)
		{
			$this->clear();
		}
		
		## Execute SQL and store result using PDO fetchALL (ASSOC)

		$statement		= $this->db->prepare($this->sql);

		## if got a pending prepared statement
		if(count($params) > 0)
		{
			$no = 1;
			foreach($params as &$value)
			{
				$statement->bindParam($no,$value);
				$no++;
			}
		}

		$execution 		= $statement->execute();
		$result			= $statement;

		## old way of executing.
		#$result			= $this->db->query($this->sql);

		if(!$execution)
		{
			error::set("PDO Error",Array($statement->errorInfo(),$this->sql,$params));
			return;
		}

		## clear param
		$this->param	= Array();

		$this->result	= $result;

		// clear record.
	}

	## Execute insert
	public function insert($table,$data)
	{
		foreach($data as $col=>$val)
		{
			$colR[]	= $col;
			$valR[]	= $val;
		}

		## use prepared statement.
		$this->sql	= "INSERT INTO $table (".implode(",",$colR).") VALUES (:".implode(",:",$colR).")";
		$statement	= $this->db->prepare($this->sql);
		
		## and bind param.
		foreach($data as $col=>&$val)
		{
			$statement->bindParam(':'.$col,$val);
		}

		## execute only in non frozen mode.
		if(!$this->_checkFreeze())
		{
			if(!$statement->execute())
			{
				error::set("PDO Error",Array($statement->errorInfo(),$this->sql,$data));
			}
		}

		$this->clear();

		return $this;
	}

	public function getLastInsertID()
	{
		return $this->db->lastInsertId();
	}

	public function update($table,$data)
	{
		foreach($data as $col=>$val)
		{
			if(is_array($val))
			{
				$dataR[]	= $col;
				foreach($val as $v)
				{
					$valR[]	= $v;
				}
			}
			else
			{
				$dataR[]	= "$col = ?";
				$valR[]		= $val;
			}
		}

		$this->sql	= "UPDATE $table SET ".implode(",",$dataR)." WHERE ".implode("",$this->whereR);

		## use prepared statement.
		$statement	= $this->db->prepare($this->sql);

		$no	= 1;
		## combine

		$param	= array_merge($valR,$this->param);
		foreach($param as &$val)
		{
			$statement->bindParam($no,$val);

			$no++;
		}

		## execute only in non frozen mode.
		if(!$this->_checkFreeze())
		{
			if(!$statement->execute())
			{
				error::set("PDO Error",Array($statement->errorInfo(),$this->sql,$this->param));
				return false;
			}
		}

		$this->param	= Array();
		$this->clear();
		return $this;
	}

	## cache functions. pending.
	/*private function cacheKey()
	{
		return md5(serialize(Array($this->sql,$this->param)));
	}

	private function checkCache()
	{
		return isset($this->cached[$this->cacheKey()]);
	}

	private function getCache()
	{
		return $this->cached[$this->cacheKey()];
	}

	private function setCache($val)
	{
		$this->cached[$this->cacheKey()]	= $val;
	}

	## clear all cached query, every insert, update and delete transactions.
	private function clearCache()
	{
		$this->cached	= Array();
	}*/

	## cache functions end.

	## Execute delete
	public function delete($table,$where = Null)
	{
		if($where)
		{
			$this->where($where);
		}

		$where	= count($this->whereR) == 0?"":"WHERE ".implode("",$this->whereR);
		$this->sql	= "DELETE FROM $table $where";

		$statement	= $this->db->prepare($this->sql);

		if(count($this->param) > 0)
		{
			$no	= 1;
			foreach($this->param as &$val)
			{
				$statement->bindParam($no,$val);
				$no++;
			}
		}

		## execute only in non frozen mode.
		if(!$this->_checkFreeze())
		{
			if(!$statement->execute())
			{
				error::set("PDO Error",Array($statement->errorInfo(),$this->sql));
				return false;
			}
		}

		$this->param	= Array();
		$this->clear();

		return $this;
	}
	### EXECUTION, ENDS ###
	### 4. RESULT PREPARATION ###
	public function num_rows()
	{
		## if result haven't been calculated, let's execute and return count, without affecting current state.
		if(!$this->result)
		{
			if(is_null($this->result))
			{
				$sql	= $this->prepareSQL(true);

				## prepare statement.
				$statement	= $this->db->prepare($sql);
				if(count($this->param) > 0)
				{
					$no	= 1;
					foreach($this->param as &$val)
					{
						$statement->bindParam($no,$val);
						$no++;
					}
				}

				$result	= $statement->execute(); 

				#$result	= $this->db->query($sql);

				if(!$result)
				{
					error::set("PDO Error",$statement->errorInfo());
					return;
				}
				$row	= $statement->fetch(PDO::FETCH_ASSOC);

				return $row['num_rows'];
			}

			return 0;
		}

		return count($this->result->fetchAll(PDO::FETCH_ASSOC));
	}

	public function row($col = Null)
	{
		if(!$this->result)
		{
			return false;
		}
		
		$result	= $this->result->fetchAll(PDO::FETCH_ASSOC);

		$result	= $result[0];

		return !$col?$result:$result[$col];
	}

	public function result($id = Null,$group = false)
	{
		if(!$this->result)
		{
			return false;
		}

		$result	= $this->result->fetchAll(PDO::FETCH_ASSOC);

		if($id)
		{
			$forgedResult	= Array();

			foreach($result as $row)
			{

				if($group === true)
				{
					$forgedResult[$row[$id]][]	= $row;
				}
				## second level group.
				else if(is_string($group))
				{
					$forgedResult[$row[$id]][$row[$group]] = $row;
				}
				else
				{
					$forgedResult[$row[$id]]	= $row;
				}
			}

			return $forgedResult;
		}

		return $result;
	}

	## this is good for data record filteration like '$post['$userID']['$date'][]	= $row;'', for resultGroup(array('userID','date'))
	public function resultByGroup($idR)
	{
		$result	= $this->result;
		$arrR	= Array();

		foreach($result as $row)
		{
			## prepare the structure.
			foreach($idR as $col)
			{
				$arrR[$val]	= &$arrR[$val];
			}

			## then push row into it.
			$arrR[]	= $row;
		}
		echo "<pre>";
		print_r($arrR);
		return $arrR;
	}

	### 5. UTILITIES ###
	## return last sql
	public function getLastSQL()
	{
		return $this->sql;
	}

	public function lastQuery()
	{
		return $this->getLastSQL();
	}

	public function getLastID($table,$column,$row = false)
	{
		if(!$row)
		{
			db::select($column);
		}
		
		db::from($table)->limit(1)->order_by($column,"desc")->get();

		## return row instead.
		if($row)
		{
			return db::row();
		}
		else
		{
			return db::row($column);
		}
		
	}

	## clear sql preparation ##
	public function clear()
	{
		$this->columnR	=
		$this->tableR	=
		$this->whereR	= 
		$this->joinR	=
		$this->orderbyR	= Array();
		$this->limit	= "";
		$this->groupBy	= null;
		$this->param = array();
	}

	## clear result. ran in every query selection builder
	public function clearResult()
	{
		$this->result	= null;
	}

	## DB Freeze! freeze sql from using any insert(), update(), and delete(). custom query not included.
	public function freeze()
	{
		$this->frozensql	= Array();	## reset
		$this->freeze		= true;		## and freeze.
	}

	public function unfreeze()
	{
		$this->freeze	= false;
	}

	public function isFreezing()
	{
		return $this->freeze;
	}

	private function _checkFreeze()
	{
		if($this->freeze)
		{
			$this->frozensql[]	= $this->sql;
			return true;
		}

		return false;
	}

	public function getFrozenSql()
	{
		return $this->frozensql;
	}
}

?>