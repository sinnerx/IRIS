<?php
Class Controller_Monitor
{
	public function schema()
	{
		$this->template	= false;
		$data['schema']		= model::load("schema")->read();

		$data['db_table']	= model::load("schema")->getDBTables();

		view::render("monitor/schema",$data);
	}
}

?>