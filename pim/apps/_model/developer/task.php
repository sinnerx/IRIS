<?php
namespace model\developer;
use db, session, model;

/**
 * More likely a migration handler.
 */

class Task
{
	protected $tasks = array();

	public function __construct()
	{
		$this->register();
	}

	/**
	 * Define all your task here.
	 */
	public function register()
	{
		model::load('developer/tasks')->registry($this);
	}



	public function has($code)
	{
		return isset($this->tasks[$code]);
	}

	public function getTasks()
	{
		return $this->tasks;
	}

	/**
	 * @return ready, pending, success
	 */
	public function getStatus($code)
	{
		$result = db::where('taskCode', $code)->get('task_log')->result();

		if($this->tasks[$code]['repeatable'])
		{
			return count($result);
		}
		else
		{
			if(!$result)
				return 'ready';

			return $row['taskLogStatus'] === 0 ? 'incomplete' : 'success';
		}
	}

	/**
	 * run the task
	 */
	public function run($code)
	{
		if(!$this->has($code))
			return;

		db::insert('task_log', array(
			'taskCode' => $code,
			'taskLogStatus' => 0,
			'taskLogCreatedDate' => now(),
			'taskLogCreatedUser' => session::get('userID')
			));

		$callback = $this->tasks[$code]['callback'];

		$taskLogID = db::getLastID('task_log', 'taskLogID');

		if(is_callable($callback))
		{
			$callback();
		}
		else
		{
			call_user_func_array(array(model::load('developer/tasks'), $callback), array());
		}

		db::where('taskLogID', $taskLogID)->update('task_log', array('taskLogStatus' => 1));
	}

	public function addTask($code, $parameters = array())
	{
		if(!is_array($task))
		{
			$task = $parameters;
			$task['code'] = $code;
		}

		// check against db. execution already exists, throw exception.
		if($this->has($task['code']))
			throw new \Exception("Task already exists. ".$task['code'], 1);

		if(!isset($task['callback']))
			$task['callback'] = $task['code'];

		$this->tasks[$task['code']] = array(
			'description' => $task['description'],
			'callback' => $task['callback'],
			'repeatable' => isset($task['repeatable']) ? $task['repeatable'] : false
			);
	}
}