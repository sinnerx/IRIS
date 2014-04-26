<?php
######
# Session Class
# API : set. get, has, destroy
#
# Ahmad Rahimie @ Eimihar
# Github.com/eimihar
# eimihar.rosengate.com
######
class Session
{
	## set session.
	public function set($name,$val,$incr = false)
	{
		if(is_array($name))
		{
			//recursive.
			foreach($name as $key=>$val)
			{
				self::set($name,$val);
			}

			return;
		}

		$nameR	= explode(".",$name);
		if(count($nameR) == 1)
		{
			$_SESSION[$name]	= $val;
			return;
		}

		$array	= &$_SESSION;
		foreach($nameR as $no=>$key)
		{
			if (!isset($array[$key]) or !is_array($array[$key]))
			{
				$array[$key] = array();
			}

			$array	= &$array[$key];
		}

		$array	= $val;
	}

	## get session, default, if not set.
	public function get($name,$default = false)
	{
		if(!self::has($name)) ##if session not set, return default
			return $default;
		
		$nameR	= explode(".",$name);
		if(count($nameR) == 1)
			return $_SESSION[$name];

		$array	= $_SESSION[$nameR[0]];
		foreach($nameR as $no=>$key)
		{
			if($no == 0)continue;

			## loop until the end of key.
			$array	= $array[$key];
		}

		return $array;
	}

	## check session existance.
	public function has($name)
	{
		$nameR	= explode(".",$name);
		if(count($nameR) == 1) return isset($_SESSION[$name]);

		$array	= $_SESSION[$nameR[0]];
		foreach($nameR as $no=>$key)
		{
			if($no == 0)continue;
			if(!isset($array[$key]))
			{
				return false;
			}
			$array	= $array[$key];
		}

		return isset($array);
	}

	## inspired bit by laravel array_forget usage of array_shift.
	public function destroy($name = null)
	{
		## destroy all.
		if(!$name)
		{
			session_destroy();
			return;
		}

		$nameR	= explode(".",$name);
		## 1 dimension
		if(count($nameR) == 1)
		{
			unset($_SESSION[$name]);
			return;
		}

		## multidimension
		$array	= &$_SESSION;
		foreach($nameR as $no=>$key)
		{
			if($no == 0) continue;
			$key	= array_shift($nameR);
			$array	=& $array[$key];
		}
		
		unset($array[array_shift($nameR)]);
	}
}
?>