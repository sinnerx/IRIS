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
	private function getProtocol()
	{
		return apps::config("secure")?"https://":"http://";
	}

	public function base($path = Null,$withquery = null)
	{
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

	private function appendQueryString($base_url,$filter)
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

	public function to($path = Null)
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
	public function asset($path = Null)
	{
		return self::getProtocol().apps::config("asset_url").($path?"/".$path:"");
	}

	## return asset based apps.
	public function apps_asset($path = Null)
	{
		$curr_apps	= apps::getCurrent("current_apps");
		return self::getProtocol().self::asset()."/$curr_apps".($path?"/".$path:"");
	}
}


?>