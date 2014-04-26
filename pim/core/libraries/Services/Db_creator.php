<?php
class Db_Creator
{
	var	$schema		= Null;
	var $path		= Null;
	var $dbName		= Null;
	
	//define type length;
	var $typeLengthR	= Array(
							"int"=>11,
							"varchar"=>100
								);

	public function __construct($path = null)
	{
		$this->path		= $path;

		//initiate connection.
		$this->dbName	= apps::config("db_name");
		$this->mysqli	= new mysqli(apps::config("db_host"),apps::config("db_user"),apps::config("db_pass"),apps::config("db_name"));
	}

	public function execute()
	{
		if($this->loadSchema())
		{
			$this->checkDb();
			$this->runProcess();
		}
	}

	public function loadSchema()
	{
		$path	= !$this->path?"apps/_structure/schema.yaml":$this->path;

		if(!file_exists($path))
		{
			error::set('DB Creator',"couldn't find $path.");
			return false;
		}

		if(is_null($this->schema))
		{
			require_once "core/libraries/Services/Spyc.php";
			$this->schema	= spyc_load_file("apps/_structure/schema.yaml");
		}

		return $this->schema;
	}

	private function checkDb()
	{
		$dbName	= $this->dbName;
		//check db existance first.
		$res	= $this->mysqli->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'");

		if($res->num_rows == 0)
		{
			if(!isset($_GET['db_create']))
			{
				error::set("DB Creatror","$dbName didn't exists.");
				return false;
			}
			else //create it.
			{
				$this->mysqli>query("CREATE DATABASE IF NOT EXISTS $dbName");
			}
		}
	}

	public function runProcess()
	{
		//Prepare list of type length first.
		$typeLengthR	= Array(
					"int"=>11,
					"varchar"=>100
								);

		$table_query	= "";
		foreach($this->schema as $tableName=>$colR)
		{
			//Create table.
			$result		= $this->mysqli->query("SHOW TABLES LIKE '$tableName'");//check table existance first.
			$result		= $result->num_rows>0;

			$no	= 1;

			$result_colR	= Array();
			if($result)
			{
				//show col.
				$result_col	= $this->mysqli->query("SHOW COLUMNS FROM $tableName");
				if($result_col)
				{
					while($row = $result_col->fetch_assoc())
					{
						$result_colR[$row['Field']]	= $row;
					}
				}
			}

			$table_colR	= Array();
			$colNameR	= Array();
			foreach($colR as $col)
			{
				$attr_check	= preg_match("/\[.*?\]/",$col,$match);

				//attribute not found.
				if(!$attr_check)
				{
					error::set("DB Creator [table : $tableName]","Undefined Attribute : ".$col);
					continue;
				}

				$attrR		= explode(",",str_replace(Array("[","]"),"",$match[0]));
				$type		= !preg_match("/\(.*?\)/",$attrR[0])?$attrR[0].(in_array($attrR[0],array_keys($typeLengthR))?"(".$typeLengthR[$attrR[0]].")":""):$attrR[0];

				$colName	= trim(str_replace($match[0],"",$col));

				$colNameR[]	= $colName;
				if($result) //table already created.
				{
					if(!in_array($colName,array_keys($result_colR)))
					{
						$table_colR[]	= (!$result?"":"ADD COLUMN ")."$colName $type";
					}
					else
					{
						//type checking.
						if($type != $result_colR[$colName]['Type'])
						{
							error::set("DB Creator [table : $tableName]","Type different : ".$col);
						}
					}
				}
				else
				{
					$table_colR[]	= "$colName $type ".($no == 1?"AUTO_INCREMENT":"");
				}

				if($no == 1)
				{
					$primary_key	= $colName;
				}

				$no++;
			}
			//compare difference.
			if(count($colNameR)> 0)
			{
				foreach(array_keys($result_colR) as $colName)
				{
					if(!in_array($colName,$colNameR))
					{
						if(!isset($_GET['db_clean']))
						{
							error::set("DB Creator [table : $tableName]","Extra column : ".$colName);
						}
					}
				}
			}

			if(count($table_colR) == 0)
			{
				continue;
			}

			$table_query	.= !$result?"CREATE TABLE IF NOT EXISTS $tableName (":"ALTER TABLE $tableName ";
			$table_query	.= implode(",",$table_colR).(!$result?", PRIMARY KEY ($primary_key));":";");
		}

		if($table_query)
		{
			$this->mysqli->multi_query($table_query);
		}
	}
}
return;
?>