<?php

class router
{
	private $routeListR			= Array();	## list of route added
	public $paramListR			= Array();  ##
	private $base_path			= "";
	private $dispatchedBasePath	= "";
	public 	$apps_other			= false;	## had to set public because need to access from within closuer in 5.3
	private $dispatched			= false;
	private $pathListR			= Array();
	private $callbackListR		= Array();
	private $storedCallbackR	= Array();
	private $routeMatched		= false;

	#private $basePathListR		= Array();	## reused at dispatch.

	public function __construct($base_path = Null)
	{
		if(!$base_path)
		{
			## get base path from php_self
			$base_path	= explode("/",$_SERVER['PHP_SELF']);
			array_pop($base_path);
			$this->base_path	= implode("/",$base_path);
		}
		else
		{
			$this->base_path	= $base_path;
		}
	}

	## Set Base Path for current dispatch.
	public function setBasePath($basePath)
	{
		$this->base_path	= $basePath;
		return $this;
	}

	## get dispatched base path (not first basepath).
	public function getBasePath()
	{
		return $this->dispatchedBasePath;
	}

	## if apps other folder was found.
	public function getAppsOtherFolder()
	{
		return $this->apps_other?$this->apps_other['folder']:false;
	}

	## check whether it's a callback, or a string based controller init. return callback.
	private function checkCallback($param,$controller_param = Null)
	{
		$callback	= (is_object($param) && ($param instanceof Closure))?$param:false;

		## check if string is controller initiation.
		if(!$callback && is_string($param) && strpos($param,"controller=") === 0)
		{
			### parse parameter and rebuild callback.
			$param	= substr($param, 11,strlen($param));
			$paramR	= explode("@",$param);

			$method	= array_pop($paramR);
			$controller	= implode("/",$paramR);
			$context	= $this;	## context.

			## rebuild callback with controller initiation.
			$callback	= function($param) use ($controller,$method,$controller_param,$context)
			{
				$tController	= trim($controller,"{}");
				$tMethod		= trim($method,"{}");

				## replace all with param.
				foreach($param as $key=>$val)
				{
					$controller	= str_replace('{'.$key.'}',$val,$controller);
					$method		= str_replace('{'.$key.'}',$val,$method);
				}



				return controller::init($controller,$method,$controller_param == Null?Array():$controller_param);
			};
		}

		return $callback;
	}

	## main multiultility function, to add route. 
	public function add($param_first,$param_second = Null,$callback = Null,$final_param = Null)
	{
		## clear pathList everytime add is used. so that it should be directly chained with dispatch for it's use.
		$this->pathListR	= Array();

		if($this->dispatched)
		{
			return $this;
		}

		### Prepare reusable flag and callback.
		## 1. param first is callback, or string based controller init.
		$first_callback	= $this->checkCallback($param_first,$param_second);
		
		## 2. param second is callback or string based controller init.
		$second_callback	= !$first_callback?$this->checkCallback($param_second,$callback):false;

		## 3. third callback.
		$third_callback		= !$first_callback && !$second_callback?$this->checkCallback($callback,$final_param):false;

		## if first param is a list of route or a path.
		if((is_array($param_first) || is_string($param_first)) && (!$param_second || is_array($param_second)) && !$callback)
		{
			if(is_string($param_first))
			{
				$path	= $param_first;
				if(strpos($param_first,"routes:") === 0)
				{
					$apps	= substr($param_first,7,strlen($param_first));
					$path	= "apps/$apps/_structure/routes.php";
				}
				### other apps routes initiation.
				else if(strpos($param_first,"apps_other:") === 0)
				{
					$apps			= substr($param_first,11,strlen($param_first));
					$path_folder	= "apps_other/$apps";
					$path			= "apps_other/$apps/_structure/routes.php";

					## save into apps_other;
					$this->apps_other['name']	= $apps;
					$this->apps_other['folder']	= $path_folder;
					$this->apps_other['config']	= null;

					## queue this path, until dispatch.
					$this->pathListR[]	= $path;

					## if some config was injected.
					if($param_second)
					{
						$this->apps_other['config']	= is_string($param_second)?require $param_second:$param_second;
					}

					return $this;
				}

				if(!file_exists($path))
				{
					error::set("Routing","Failed to find routes file. [$path]");
					return $this; ## added $this, for apps_other route
				}

				$this->add(require_once $path);
				return $this; ## added $this, for apps_other route
			}
			else if(is_array($param_first))
			{
				foreach($param_first as $routeR)
				{
					call_user_func_array(array($this,"add"), $routeR);
				}
				return $this;
			}
		}
		## if first param is a callback
		else if($first_callback)
		{
			$callback	= $first_callback;
			$route		= true;
			$method		= Array("GET","POST","PUT","DELETE");
		}
		## first param is a route or method and second is callback.
		else if($second_callback)
		{
			$callback	= $second_callback;

			if(is_bool($param_first))
			{
				$route	= true;
				$method	= Array("GET","POST","PUT","DELETE");
			}
			## first is method, only filter by post.
			else if(in_array($param_first,Array("GET","POST","PUT","DELETE")) || is_array($param_first))
			{
				$route		= true;
				$method		= !is_array($param_first)?array($param_first):$param_first;
			}
			## domain routing.
			else if(strpos($param_first,"domain:") === 0)
			{
				$domain			= substr($param_first, 7,strlen($param_first));
				if($domain == "all")
				{
					$this->paramListR['domain_name']	= $_SERVER['HTTP_HOST'];

					## just execute.
					$this->executeCallback($param_second);
					return $this; ## added $this, for apps_other route
				}

				$domainListR	= explode("|",$domain);

				## just execute:
				if(in_array($_SERVER['HTTP_HOST'],$domainListR))
				{
					$this->paramListR['domain_name']	= $_SERVER['HTTP_HOST'];
					$this->executeCallback($param_second);
				}
				return $this;  ## added $this, for apps_other route
			}
			else
			{
				$route		= $param_first;
				$method		= Array("GET","POST","PUT","DELETE");
			}
		}
		## if callback is in the last. everything should be in place then. =)
		else
		{
			$method		= !is_array($param_first)?array($param_first):$param_first;
			$route		= is_bool($param_second)?true:$param_second;	## if second param, is book, set route to true.
		}

		$this->routeListR[]	= Array($route,$callback,$method);

		return $this;## added $this, for apps_other route
	}

	## do some callback.
	public function executeCallback($callback,$param = null)
	{

		## store callback.
		if($param == "store")
		{
			$this->storedCallbackR[]	= $callback;
			return;
		}

		return call_user_func($callback,$this->paramListR,$this->base_path);
	}

	## Main routes dispatchment function.
	public function dispatch($base_path	= Null)
	{
		if($this->dispatched)
		{
			return;
		}

		$base_path	= trim(!$base_path?$this->base_path:$this->base_path."/".trim($base_path,"/"),"/");
		
		list($request_uri)	= explode("?",$_SERVER['REQUEST_URI']);	//clean anything after ?.
		$request_uri	= trim($request_uri,"/");

		## if matched
		if($base_path != "")
		{
			if(strpos($request_uri, $base_path) === 0)
			{
				## but after the pattern didn't end with '/'.
				if($request_uri[strlen($base_path)] != "/" && $request_uri[strlen($base_path)])
				{
					## keep reseting apps_other. (i've been tweaking here and there to enable other apps load T_T) its no more clean
					#$this->apps_other	= false;
					$this->reset(); ## added 12/4
					return;
				}
			}
			else
			{
				#$this->apps_other	= false; ## keep reseting apps_other
				$this->reset();  ## added 12/4 a quick fix to apps path list unresetted.
				return;
			}
		}

		## pathListR for apps_other route.
		if(count($this->pathListR) > 0)
		{
			foreach($this->pathListR as $path)
			{
				$this->add($path);
			}
		}

		## get only real uri request from request_uri, by substracting base_path from it.
		$uri		= substr($request_uri,strlen(trim($base_path,"/")));
		$uri		= trim($uri,"/");
		list($uri)	= explode("?",$uri,2);
		
		## save dispatched basepath.
		$this->dispatchedBasePath	= $base_path;

		foreach($this->routeListR as $routeNo=>$routeData)
		{
			if($routeData[0] === "_404")
			{
				$this->register404($routeData[1]);
				continue;
			}

			## check request method.
			if(!in_array($_SERVER['REQUEST_METHOD'],$routeData[2]))
			{
				continue;
			}

			if($routeData[0] === true)
			{
				$this->executeCallback($routeData[1],"store");
				continue;
				/*## $this->executeCallback($routeData[1]) // TODO : callback false, will continue routing.
				if($this->executeCallback($routeData[1]) !== false)
				{
					continue;
				}*/
			}
			## has been called, for routed callback.
			if($this->routeMatched)
			{
				continue;
			}

			$route	= trim($routeData[0],"/");



			$routeR	= explode("/",$route);
			$uriR	= explode("/",$uri);

			$match	= true;
			$paramListR	= Array();

			foreach($routeR as $no=>$param)
			{
				### Check parameter.
				# found
				if($param[0] == "[" && $param[strlen($param)-1] == "]")
				{
					$param	= trim($param,"[]");
					$paramR	= explode(":",$param);

					## required ':' for match.
					if(count($paramR) == 1)
					{
						$match	= false;
						break;
					}

					## list the explosion into type and param name ($param)
					list($type,$param_name)	= $paramR;

					## optional flag if got optional mark. '?'.
					$optional	= $param_name[strlen($param_name)-1] == "?";
					$param_name	= trim($param_name,"?");

					## if no data at current uri number.
					if(!isset($uriR[$no]))
					{
						## if optional, continue without breaking.
						if($optional)
						{
							## if no data in uri, set match to true, but continue on searching.
							$match	= true;  
							continue;
							## TODO : not sure about this optional, might break something, might not.
						}

						$match	= false;
						break;
					}

					### main type matching. 
					switch($type)
					{
						case "":# match all, so do nothing.
						if($uriR[$no] == "" && !$optional)
						{
							$match	= false;
							break 2;
						}
						break;
						case "i":# Integer
							if(!is_numeric($uriR[$no]))
							{
								$match	= false;
								break 2;
							}
						break;
						case "**":# trailing!
							## get all the rest of uri for param.
							$paramListR[$param_name]	= array_pop(explode("/",$uri,$no+1));
							$match	= true;
							
							break 2; ## break the param loop, and set match directly to true.
						break;
						default:
							$match	= false;
							break 2;
						break;
					}

					## 
					if(count($routeR) != count($uriR))
					{
						$match	= false;
					}

					### add into param.
					$paramListR[$param_name]	= $uriR[$no];
				}/*
				else if($param[0] == "[" && $param[strlen($param)-1] == "?" && $param[strlen($param)-2] == "]")### check for optional.
				{
					continue;
				}*/
				else #no pattern found, need to equate with uri param
				{
					if(count($routeR) != count($uriR))
					{
						$match	= false;
						#break;	 ## commented 11/3, becoz got a problem uri not same.
					}

					if($uriR[$no] != $param)
					{
						$match	= false;
						break;
					}
				}
			}

			if($match)
			{
				$this->routeMatched		= true;
				$this->paramListR		= array_merge($this->paramListR,$paramListR);
				$this->executeCallback($routeData[1],"store");
				$this->dispatched		= true;
			}
		}

		## execute stored callback.
		$execution	= $this->executeStoredCallback();

		## end of dispatch. if there're any trial to add later, will simply ignore.
		if($execution === false)
		{
			//execute 404 if got.
			if(isset($this->callbackListR['404']))
			{
				$this->executeCallback($this->callbackListR['404']);
			}
		}


		## reset routing after dispatch.
		$this->reset();
		return $this;
	}

	## execute the stored callback.
	private function executeStoredCallback()
	{
		if(!$this->routeMatched)
		{
			return;
		}

		if(count($this->storedCallbackR) > 0)
		{
			## just moved from executeCallback (8/april). if anything failed, revert here.
			## if other apps folder, execute their config
			if($this->apps_other !== false)
			{
				apps::initConfig($this->apps_other['folder'],$this->apps_other['config']);
			}

			foreach($this->storedCallbackR as $callback)
			{
				$result	= $this->executeCallback($callback);

				if($result === false)
				{
					return false;
				}

				echo response::parse($result);
			}

		}

		return true;
	}

	## register 404 callback.
	private function register404($callback)
	{
		$this->callbackListR['404']	= $callback;
	}

	## reset function. used after each dispatch.
	private function reset()
	{
		$this->apps_other		= false;
		$this->routeListR		= Array();	## list of route
		$this->pathListR		= Array();
		$this->storedCallbackR  = Array();	## stored callback on each dispatch.
		$this->routeMatched		= false;	## match routed flag.
	}
}

?>