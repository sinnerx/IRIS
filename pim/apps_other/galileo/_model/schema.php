<?php

Class Model_Schema
{
	private function load()
	{
		$schema	= file_get_contents("apps/_structure/schema.yaml");

		return $schema;
	}

	public function read()
	{
		$schema	= $this->load();

		$tableR	= Array();
		foreach(explode("\n",$schema) as $line)
		{
			list($table_name,$comment)	= explode("#",$line,2);
			$table_name	= trim($table_name);
			if(strpos($table_name," ") === false && $table_name[strlen($table_name)-1] == ":")
			{
				$name	= $table_name;
			}
			else
			{
				if(!isset($name) || $line == "" || $table_name == "")
				{
					continue;
				}

				list($column,$type)	= explode(" ",$table_name);
				$name				= str_replace(":", "", $name);
				$tableR[$name]['columns'][]	= $column;
				$tableR[$name]['type'][$column]	= $type;
			}
		}

		return $tableR;
	}

	public function getDBTables()
	{
		db::connect(apps::config("db_host"),apps::config("db_user"),apps::config("db_pass"),apps::config("db_name"));
		$dbName	= apps::config("db_name");

		$tables	= db::query("show tables")->result();
		$time	= microtime(true);
		$tableR	= Array();
		foreach($tables as $key=>$table)
		{
			$table		= $table['Tables_in_'.apps::config("db_name")];
			$result_col	= db::query("show columns from $table")->result();

			foreach($result_col as $row)
			{
				$col_name	= $row['Field'];
				$type		= $row['Type'];

				$tableR[$table]['columns'][]		= $col_name;
				$tableR[$table]['type'][$column]	= $type;
			}
		}


		return $tableR;
	}
}



?>