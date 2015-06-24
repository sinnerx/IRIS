<?php

/**
 * Handles task
 */

class Controller_Task
{
	public function index()
	{
		$data['tasks'] = model::load('developer/task')->getTasks();

		view::render('developer/task/index', $data);
	}

	public function run($code)
	{
		model::load('developer/task')->run($code);
		redirect::to('task/index');
	}
}


?>