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

	public function takeOver()
	{
		if(form::submitted())
		{
			$email = request::post('userEmail');

			$accessAuth = model::load("access/auth");
			$user = db::where('userEmail', $email)->where('userLevel', 2)->get('user')->row();

			if($user)
			{
				$accessAuth->login($user['userID'], $user['userLevel']);
				redirect::to(model::load("access/data")->firstLoginLocation(2));
			}
			else
			{
				redirect::to('', 'User not found.', 'error');
			}
		}

		view::render('developer/task/takeOver');
	}
}


?>