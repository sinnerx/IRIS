<?php
######
# Url Class
# API : base. asset, apps_asset
#
# Ahmad Rahimie @ Eimihar
# Github.com/eimihar
# eimihar.rosengate.com
######

class Url
{
	private static $query;

	## return config:base_url
	public static function getProtocol()
	{
		return apps::config("secure")?"https://":"http://";
	}

	## forge given route with existing named param
	private static function forgeRoute($route)
	{
		$route	= explode("/",$route);

		$newP	= Array();
		foreach($route as $r)
		{
			if($r[0] == "[" && $r[strlen($r-1)])
			{
				$newP[]	= "{".trim($r,"[**:]")."}";
			}
			else
			{
				$newP[]	= $r;
			}
		}

		return implode("/",$newP);
	}

	public static function base($path = Null,$withquery = null)
	{
		if(strpos($path, '{current-uri}') !== false)
		{
			$value	= self::forgeRoute(apps::getGlobal('router')->executedRoute);
			$path	= str_replace('{current-uri}', $value, $path);
		}

		if($path)
		{
			if(strpos($path, "{") !== false)
			{
				foreach(request::named() as $name=>$value)
				{
					$path	= str_replace("{".$name."}",$value,$path);
				}
			}
		}
		if(!apps::config("base_url"))
		{
			$base_url	= self::base_relative($path);
		}
		else
		{
			$base_url	= self::getProtocol().apps::config("base_url").($path?"/".$path:"");
		}

		return !$withquery?$base_url:self::appendQueryString($base_url,$withquery);
	}

	private static function appendQueryString($base_url,$filter)
	{
		$filter	= $filter === true?"_all":$filter;

		$filter	= filter_array(request::get(),$filter);
		$querystring	= Array();
		foreach($filter as $key=>$val)
		{
			$querystring[]	= $key."=".$val;
		}

		return count(explode("?",$base_url)) > 1?$base_url."&".implode("&",$querystring):$base_url."?".implode("&",$querystring);
	}

	public static function to($path = Null)
	{
		return self::base($path);
	}

		### will ignore config['base_url'], relative path by calculated url.
		private function base_relative($path = null)
		{
			$test	= self::getProtocol().$_SERVER['HTTP_HOST']."/".apps::getGlobal("router")->getBasePath();
			return concat_path($test,$path);
		}

	## return config:asset_url 
	public static function asset($path = Null)
	{
		return self::getProtocol().apps::config("asset_url").($path?"/".$path:"");
	}

	## return asset based apps.
	public static function apps_asset($path = Null)
	{
		$curr_apps	= apps::getCurrent("current_apps");
		return self::getProtocol().self::asset()."/$curr_apps".($path?"/".$path:"");
	}

	public static function createByRoute($routeName,$param = Array(),$withBase = false)
	{
		$routeData	= apps::getGlobal("router")->findRouteByName($routeName);
		$uri		= $routeData[0];

		$newUriR	= Array();
		foreach(explode("/",$uri) as $segment)
		{
			if($segment[0] == "[" && $segment[strlen($segment)-1] == "]")
			{
				$ori_segment=$segment;
				$segment	= trim($segment,"[**i:?]");

				if(isset($param[$segment]))
				{
					$segment	= $param[$segment];
				}
				## check in named parameter.
				else if(in_array($segment,array_keys(request::named())))
				{
					$segment	= request::named($segment);
				}
				## else, if it's optional set it to empty.
				else if($ori_segment[strlen($ori_segment)-2] == "?")
				{
					$segment	= "";
				}
				else
				{
					## push and error.
					error::set("URL_Forge","Not enough parameter. parameter : $segment");
				}
			}

			$newUriR[]	= $segment;
		}

		$uri	= trim(implode("/",$newUriR),"/");
		return $withBase?url::base($uri):$uri;
	}

	## alias for self::createByROute but this function set default $withBase as true.
	public static function route($routeName,$param = Array(),$withBase = true)
	{
		return self::createByRoute($routeName,$param,$withBase);
	}
}


?>