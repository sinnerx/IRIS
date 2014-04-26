<?php

### return result with based on : _all, except:, and list,of,item
function filter_array($listR,$param,$isAssoc = null)
{
	$isAssoc	= $isAssoc === true?true:($isAssoc === false?false:(array_values($listR) === $listR?false:true));

	if($param == "_all")
	{
		return $listR;
	}

	$exception	= strpos($param, "except:") === 0;
	$param	= $exception?substr($param, 7,strlen($param)):$param;
	$paramR	= explode(",",$param);
	$result	= Array();
	foreach($listR as $key=>$val)
	{
		if(($isAssoc && !in_array($key,$paramR) && $exception) || ($isAssoc && in_array($key,$paramR) && !$exception))
		{
			$result[$key]	= $val;
		}
		else if((!$isAssoc && !in_array($val,$paramR) && $exception) || (!$isAssoc && in_array($val, $paramR) && !$exception))
		{
			$result[]	= $val;
		}
	}

	return $result;
}

### get now date in Y-m-d H:i:s format.
function now()
{
	return date("Y-m-d H:i:s");
}

#### function to refine path, by changing slashes to work either in WINNT or Linux
function refine_path($path)
{
	switch(PHP_OS)
	{
		case "WINNT":
		return str_replace("/","\\",$path);
		break;
		case "Linux":
		return str_replace("\\", "/", $path);
		break;
	}
}

function concat_path($first_path,$path)
{
	return trim($first_path,"/").($path?"/".$path:"");
}

## replace string with param.
function replace_param($name)
{
	$paramR	= request::named();

	foreach($paramR as $key=>$value)
	{
		$name	= str_replace('{'.$key.'}',$value,$name);
	}

	return $name;
}

?>