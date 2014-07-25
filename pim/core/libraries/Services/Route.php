<?php

class Route
{
	private static $dataR	= null;

	## return route detail based on named route. only applicable to stringed route.
	public function get($name,$routeData = null)
	{
		$router	= apps::getGlobal("router");

		## get named route.
		$route	= $router->namedRoute[$name];

		if($route)
		{
			$data	= Array();
			$no		= 1;
			foreach($route as $r)
			{
				if(strpos($r, "controller=") === 0)
				{
					## fetch controller/method. from controller=[cname]@[mName]/[p1]/[p2]
					$r	= str_replace("controller=", "", $r);

					list($controller,$methodParam)	= explode("@",$r);
					$data['controller']	= $controller;

					## get method 
					$methodParam	= explode("/",$methodParam);
					$data['method']		= array_shift($methodParam);
				}
				## only first or second parameter can be considered route.
				else if(is_string($r) && $no < 3)
				{
					$data['route']	= $r;
				}

				$no++;
			}
		}

		return !$routeData?$data:$data[$routeData];
	}

	## prepare a well associated route list
	public function getRouteList()
	{
		if(self::$dataR)
			return self::$dataR;

		$router	= apps::getGlobal("router");

		## get named route.
		$route	= $router->routeList;

		$dataR	= Array();
		foreach($route as $k=>$rR)
		{
			$data	= Array();
			$no		= 1;
			foreach($rR as $r)
			{
				if(!is_object($r) && strpos($r, "controller=") === 0)
				{
					## fetch controller/method. from controller=[cname]@[mName]/[p1]/[p2]
					$r	= str_replace("controller=", "", $r);

					list($controller,$methodParam)	= explode("@",$r);
					$data['controller']	= $controller;

					## get method 
					$methodParam	= explode("/",$methodParam);
					$data['method']		= array_shift($methodParam);
				}
				## only first or second parameter can be considered route.
				else if(is_string($r) && $no < 3)
				{
					$data['route']	= $r;
				}

				$no++;
			}

			$dataR[$k]	= $data;
		}

		## save.
		self::$dataR	= $dataR;

		return $dataR;
	}

	public function getCurrent($routeData = null)
	{
		$router	= apps::getGlobal("router");

		## get executed route.
		$routeList	= self::getRouteList();

		## find current route.
		foreach($routeList as $n=>$data)
		{
			if($data['route']	== $router->executedRoute)
			{
				return !$routeData?$data:$data[$routeData];
			}
		}

		return false;
	}
}

?>