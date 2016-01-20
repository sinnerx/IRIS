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

/**
 * Get current user model
 * @return \model\user\user
 */
function user()
{
	if(RuntimeCaches::has('currentUserInstance'))
		return RuntimeCaches::get('currentUserInstance');

	$user = orm('user/user')->find(session::get('userID')); 

	RuntimeCaches::set('currentUserInstance', $user);

	return $user;
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

/**
 * Roman number
 * credit http://www.go4expert.com/articles/roman-php-t4948/
 */
function numberToRoman($num) 
{
 // Make sure that we only use the integer portion of the value
 $n = intval($num);
 $result = '';

 // Declare a lookup array that we will use to traverse the number:
 $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);

 foreach ($lookup as $roman => $value) 
 {
     // Determine the number of matches
     $matches = intval($n / $value);

     // Store that many characters
     $result .= str_repeat($roman, $matches);

     // Substract that from the number
     $n = $n % $value;
 }

 // The Roman numeral should be built, return it
 return $result;
}

function pprint_r($d)
{
	echo '<pre>';
	print_r($d);
}

?>