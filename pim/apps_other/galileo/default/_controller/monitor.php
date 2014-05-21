<?php
Class Controller_Monitor
{
	public function schema()
	{
		$this->template	= false;
		$schema				= model::load("schema")->read();
		$data['schema']		= $schema[0];
		$data['commentR']	= $schema[1];

		$data['db_table']	= model::load("schema")->getDBTables();

		view::render("monitor/schema",$data);
	}

	## execute ?db_update actually, from other apps.
	public function update()
	{
		## redirect back to schema using js.
		echo "<script type='text/javascript'>window.location.href = 'schema';</script>";

	}
}

?>