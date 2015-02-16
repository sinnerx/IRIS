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

function orm($model, $param2 = null)
{
	return model::orm($model, $param2);
}

// return site entity
// for now it will dual query.
function site($siteID = null)
{
	// use the given siteID, if it's not passed.
	$siteID = !$siteID ? authData('site.siteID') : $siteID;

	if(!$siteID)
		return false;

	return orm('site/site')->find($siteID);
}

?>