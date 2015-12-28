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

function user()
{
	return model::orm('user/user')->where('user.userID', authData('user.userID'))->execute()->getFirst();
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

/**
 * Return site/member entity, of the current authdata.
 * Should only be used by frontend.
 * @return model/site/member
 */
function member()
{
	$userID = authData('user.userID');

	$user = orm('site/member')->where('userID', $userID)->execute();

	if(count($user) == 0)
		return false;
	foreach($user as $row)
		return $row;
}

?>