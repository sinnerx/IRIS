<?php
/*
List of model alias helper for Pim.
*/

## alias access/auth->getAuthData(); but uses dot notation as object.property separator.
function authData($params = null)
{
	if(!$params)
	{
		$param1	= null;
		$param2 = null;
	}
	else
	{
		list($param1,$param2) = explode(".",$params);
	}

	return model::load("access/auth")->getAuthData($param1,$param2);
}

?>