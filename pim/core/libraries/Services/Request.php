<?php
######
# Session Class
# API : get, post, named, isAjax, method
#
# Ahmad Rahimie @ Eimihar
# Github.com/eimihar
# eimihar.rosengate.com
######

class Request
{
	### return $_GET, default instead if not specified.
	public static function get($name = null,$default = false)
	{
		return !$name?$_GET:(isset($_GET[$name])?($_GET[$name] == ""?($default !== false?true:$default):$_GET[$name]):$default);
	}

	### return $_POST, default instead if not specified.
	public static function post($name = null,$default = null)
	{
		if(!$name)
		{
			if(count($_POST) == 0)
			{
				return false;
			}
		}
		return !$name?$_POST:($_POST[$name] != ""?$_POST[$name]:$default);
	}

	public static function has($param)
	{
		// based on method
		$method = strtolower(self::method());

		if($method == 'get')
		{
			$params = array_merge($_POST, $_GET);
		}
		else
		{
			$params = array_merge($_GET, $_POST);
		}

		if(is_array($param))
		{
			foreach($param as $key)
			{
				if(!isset($params[$key]))
					return false;
			}
		}
		else
		{
			if(!isset($params[$key]))
				return false;
		}

		return true;
	}

	/**
	 * Prioritize current method
	 */
	public static function param($name, $default = false)
	{
		return strtolower(self::method()) == 'get' ? self::get($name, self::post($name, $default)) : self::post($name, self::get($name, $default));
	}

	### return named parameter, from routing.
	public static function named($name = null,$default = null)
	{
		return apps::param($name,$default);
	}

	### check ajax request.
	public static function isAjax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	### return method.
	public static function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
}


?>