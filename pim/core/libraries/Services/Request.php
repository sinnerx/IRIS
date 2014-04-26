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
	public function get($name = null,$default = false)
	{
		return !$name?$_GET:(isset($_GET[$name])?($_GET[$name] == ""?($default !== false?true:$default):$_GET[$name]):$default);
	}

	### return $_POST, default instead if not specified.
	public function post($name = null,$default = null)
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

	### return named parameter, from routing.
	public function named($name = null,$default = null)
	{
		return apps::param($name,$default);
	}

	### check ajax request.
	public function isAjax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	### return method.
	public function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
}


?>