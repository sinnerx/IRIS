<?php
/*
===============================
==   EXEDRA HMVC FRAMEWORK	 ==
===============================
AUTHOR 		: Eimihar El-Meruiy
GITHUB 		: https://github.com/eimihar

*/

## require router as application routing dependency.
require_once "Router.php";

class Console
{
	public function execute($consoleArgs)
	{
		## remove loader.
		array_shift($consoleArgs);

		## firstparam
		$firstparam	= $consoleArgs[0];

		list($controller,$method)	= explode("@",$firstparam);

		return response::parse(controller::init($controller,$method));
	}
}

class Apps
{
	public static $global;
	private static $apps_config;
	private static $default_apps		= "default";
	private static $current_apps		= Null;
	private static $registeredClass		= Array();
	private static $apps_folder			= "apps";
	private static $apps_other			= false;

	## Initiate config
	public static function run($callback,$argv = null)
	{
		## start session.
		session_start();

		self::setGlobal('time_start',microtime(true));

		$router	= new Router();

		self::loadFunctionLibrary('helper');
		self::register_autoload_path(refine_path(dirname(__FILE__)."/Services"));		## register services autoload path.
		self::register_autoload_path("apps/_");											## register any class that loaded, without the use of model() class;

		self::setGlobal("router",$router); 						## save router instance
		self::initConfig();										## initiate /apps folder config.
		echo !$argv?call_user_func_array($callback,Array($router)):console::execute($argv);			## initiate application or execute console.
	}

	public static function initConfig($folder = "apps",$injectedConfig = null)
	{
		## reset apps_config, everytime it's initiated.
		self::$apps_config	= null;

		self::saveConfig("$folder/_config/config.php",null,$injectedConfig);		## initiate config
		self::saveConfig("$folder/_config/database.php",null,$injectedConfig); 	## database config.
	}

	## get apps folder.
	public static function getAppsFolder()
	{
		## check for folder.
		$apps_other	= self::getGlobal("router")->getAppsOtherFolder();
		return $apps_other !== false?$apps_other:self::$apps_folder;
	}

	public static function register_autoload_path($dir)
	{
		spl_autoload_register(function($class) use ($dir)
		{
			## get last charater from dir.
			$lastDirChar	= $dir[strlen($dir)-1];

			$class	= ucfirst($class);
			#$dir	= trim($dir,"/").($lastDirChar == "_"?"":"/"); # i find this to be trimming '/' from path, whichis not good for linux.
			$dir	= rtrim($dir,"/").($lastDirChar == "_"?"":"/");
			$path	= $dir.$class.".php";

			## only for folder with _, in easy word, with model one.
			if($lastDirChar == "_")
			{
				$path	= refine_path(strtolower($path));
			}

			if(file_exists($path))
			{
				apps::classRegister($class);
				require_once $path;
			}
		});
	}

	public static function loadFunctionLibrary($file)
	{
		if(is_array($file))
		{
			foreach($file as $fileName)
			{
				self::loadFunctionLibrary($fileName);
			}
			return;
		}
		#$path	= "exedra/libraries/Functions/$file.php";
		$path	= dirname(__FILE__)."/Functions/$file.php";

		if(file_exists($path))
		{
			require_once $path;
		}
	}

	## return named param.
	public static function param($name = Null,$default = Null)
	{
		$param	= self::getGlobal("router")->paramListR;

		return $name?(isset($param[$name])?$param[$name]:$default):$param;
	}

	### Global Variable Setter getter ###
	public static function setGlobal($name,$val,$incr = false)
	{
		if($incr)
		{
			self::$global[$name][]	= $val;
		}
		else
		{
			self::$global[$name]	= $val;
		}
	}

	public static function getGlobal($name)
	{
		return self::$global[$name];
	}

	public static function hasGlobal($name)
	{
		return isset(self::$global[$name]);
	}

	### Config ###
	## Get ##
	public static function config($conf = Null,$default = null)
	{
		if(!$conf)
		{
			return self::$apps_config;
		}
		$currentEnv	= self::$apps_config['current_env'];
		return self::$apps_config[$conf];
	}

	## Save ##
	public static function saveConfig($conf,$val = Null,$injectedConfig = null)
	{
		if($val)
		{
			self::$apps_config[$conf]	= $val;
			return;
		}

		if(!is_array($conf))
		{
			if(file_exists($conf))
			{
				self::saveConfig(require_once $conf);
			}

			## if got injected config, rewrite.
			if($injectedConfig)
			{
				foreach($injectedConfig as $key=>$val)
				{
					self::$apps_config[$key]	= $val;
				}
			}

			return;
		}



		$current_env	= !isset($conf['current_env'])?"dev":$conf['current_env'];

		## domain based configuration.
		if(isset($conf['domain']))
		{
			foreach($conf['domain'] as $domain_name=>$domain_conf)
			{
				if($domain_name == $_SERVER['HTTP_HOST'])
				{
					foreach($domain_conf as $conf_name=>$conf_value)
					{
						foreach($conf_value as $confname=>$confvalue)
						{
							apps::$apps_config[$confname]	= $confvalue;
						}
					}
				}

				## unset.
				unset($conf['domain'][$domain_name]);
			}
		}

		foreach($conf as $conf_name => $conf_value)
		{
			if($conf_name == "env_".$current_env)
			{
				foreach($conf[$conf_name] as $confSets)
				{
					foreach($confSets as $confname => $confvalue)
					{
						self::$apps_config[$confname]	= $confvalue;
					}
				}
			}
			else
			{
				self::$apps_config[$conf_name]	= $conf_value;
			}
		}

	}

	### Default and Current apps set getter, initiated at controller initiation ###
	public static function initApps($name)
	{
		self::$current_apps	= $name;
		self::initAppsConfig();
	}

	## Init config
	private static function initAppsConfig()
	{
		## recorrect base url
		$apps	= self::getCurrent();

		if(isset(self::$apps_config["base_url:$apps"]))
		{
			self::$apps_config['base_url']	= self::$apps_config["base_url:$apps"];
		}
	}

	public static function setDefault($name)
	{
		self::$default_apps	= $name;
	}

	## Application
	public static function getDefault()
	{
		return self::config("default_apps")?self::config("default_apps"):self::$default_apps;
	}

	public static function getCurrent()
	{
		return self::$current_apps;
	}

	## Set Environment or get
	public static function environment($env = Null)
	{
		if($env)
		{
			self::config("current_env",$env);
			return;
		}
		return self::config("current_env");
	}

	## Register class.
	public static function classRegister($class)
	{
		self::$registeredClass[]	= $class;
	}

	## return registered class.
	public static function classRegistered($class)
	{
		return in_array($class,self::$registeredClass)?true:false;
	}

	## load library located in apps/_library
	public static function loadLibrary($name)
	{
		if(is_array($name))
		{
			foreach($name as $lib)
			{
				self::loadLibrary($lib);
			}
			return;
		}

		$path	= self::getAppsFolder()."/_library/$name.php";
		if(!file_exists($path))
		{
			error::set("Library Load","Couldn't find library [$name].");
			return;
		}

		require_once $name;
	}

	public static function isOther()
	{
		if(apps::getGlobal("router")->getAppsOtherFolder() !== false)
			return true;

		return false;
	}
}

class Controller
{
	static $instance					= Null;		## list of loaded instance
	private static $currentController	= Null;		## current initiated controller name
	private static $currentMethod		= Null;		## current initiated method name
	private static $lastController		= Null;		## last executed controller
	private static $lastMethod			= Null;		## last executed method
	private static $initiated			= false;	## state of controller initiation
	private static $initiating			= false;	## state of controller current initiation

	public static $loadedListR			= Array(); 	## list of controller.

	private static $hookListR			= Array();	## list of hooks
	private static $hooking				= false;	## store hooking status

	public static $forwarding			= false;

	## Main initiation method. Widely can be used in router.
	public static function init($controller,$param_method = null,$paramR = Array(),$constructData = null)
	{
		## if have been initiated 
		if(self::$initiated)
		{
			error::set("Controller Init","Cannot init controller more than once.");
			return;
		}

		# cannot init controller in a hook
		if(self::$hooking)
		{
			error::set("Hook","Cannot init a controller while hooking. [".self::$hooking."]");
			return;
		}


		## fetch controller from string and method from trailing (if got);
		list($controller,$chosenApps)		= array_reverse(explode(":",$controller));
		$controller	= replace_param($controller);
		$param_method	= replace_param($param_method);
		list($method)						= explode("/",$param_method);

		self::$currentController	= $controller;	## save controller name;
		self::$currentMethod		= $method;		## save method name.

		## if apps : model
		if($chosenApps == "model")
		{
			return self::executeModel($controller,$param_method);
		}
		
		apps::initApps($chosenApps?$chosenApps:apps::getDefault());		## set current apps;

		self::$initiated			= false;		## controller initiation status
		self::$initiating			= true;			## set controller in initiating mode

		$constructData 	= self::implementHookPoint("pre_controller"); ## hook point on controller initiation
	
		$response		= self::load($controller,$param_method,$paramR,$constructData);	## controller load.
		self::implementHookPoint("post_controller");## hook point after that

		## if die() was executed before here, flash message wont be destroyed.
		session::destroy("fdata");

		## if result is returned with some sort of response.
		return $response;
	}

	/*
	a temporary comment to see if feature forwarding is ok, to be used through controller::load
	public static function forward($controller,$method = null,$paramR = Array(),$constructData = null)
	{
		self::$forwarding = true;
		return self::load($controller,$method,$paramR,$constructData);
	}*/
	
	public static function load($controller,$method = null, $paramR = Array(),$constructData = null)
	{
		## cannot load, if haven't init yet, and not in hooking mode.
		if(!self::$initiated && !self::$hooking && !self::$initiating)
		{
			error::set("Controller Load","Cannot load without initiation or not in the mode of hooking.");
			return;
		}

		## if this function was called in controller, while controller havent load any view. we set it as forwarding.
		if(self::$initiated && !template::$loaded && !self::$hooking && $method)
		{
			self::$forwarding = true;
		}

		## check if apps was passed along.
		list($controller,$chosenApps)		= array_reverse(explode(":",$controller));

		## if passed with the string format, prepare the params by exploding with commas notation first.
		if(is_string($paramR))
		{
			$param	= apps::param();

			$newParamR	= Array();
			foreach(explode(",",$paramR) as $paramName)
			{
				if($paramName[0] == "{" && $paramName[strlen($paramName)-1] == "}")
				{
					$paramName	= trim($paramName,"{}");
					$value		= isset($param[$paramName])?$param[$paramName]:/*$paramName*/null;

				}
				else
				{
					$value	= $paramName;
				}

				$newParamR[]	= $value;
			}
			$paramR	= $newParamR;
		}

		$apps			= $chosenApps?$chosenApps:apps::getCurrent();
		## save controller name.
		$v = explode("/",$controller);
		$controller_name	= array_pop($v);	## added 16/3, to permit loading controller under subfolder.
		$controllerFullName	= $controller; ## just added 15/6, to bring it's full name for saving and retrieving purpose.
		$path			= apps::getAppsFolder()."/$apps/_controller/$controller.php";



		## check if in method got param.
		$methodR	= explode("/",$method);
		if(count($methodR) > 1)
		{
			$method			= array_shift($methodR); ##take method from first element.
			$paramR			= array_merge($methodR,$paramR);
		}

		## save all controller trying to load into loadedListR.
		self::$loadedListR[]	= "[".controller::currentState()."]$apps:".$controllerFullName."@".$method;

		## check controller existance.
		if(isset(self::$instance[$controllerFullName]))
		{
			$controller	= self::$instance[$controllerFullName];
		}
		else
		{
			if(!file_exists($path)) ## controller not found.
			{
				$state	= self::currentState();
				error::set("controller : $state","Controller not found.");
				return;
			}

			require_once $path;

			$controller	= "Controller_".$controller_name;

			if(!class_exists($controller))
			{
				$state	= self::currentState();
				error::set("controller : $state","Controller class name [$controller] didn't exists.");
				return;
			}

			$controller	= new $controller($constructData);

			## save controller instance and it's name
			self::$instance[$controllerFullName]	= $controller;
		}

		if(!$method)
		{
			return $controller;
		}

		## set last executed controller and method;
		self::$lastController	= $controllerFullName;
		self::$lastMethod		= $method;

		## moving : from point here.
		

		## check method existance.
		if(!method_exists($controller, $method))
		{
			$state	= self::currentState();
			error::set("controller : $state/load_method","Method did not exists. [$controllerFullName@$method]");
			return;
		}

		$reflection = new ReflectionMethod($controller,$method);

		## check function required parameter
		$reflectionParams	= $reflection->getParameters();

		foreach($reflectionParams as $no=>$obj)
		{
			## check for missing mandatory parameter.
			if(!$obj->isDefaultValueAvailable())
			{
				if(!isset($paramR[$no]))
				{
					$paramName	= $obj->getName();
					error::set("Controller [$apps:$controllerFullName] : method [$method]","Required parameter is missing.! [name : <u>$paramName</u>].");
					return;					
				}
			}
		}

		## is in initiating mode
		## if method is not a public
		## template haven't loaded yet.
		## initiated controller/method = current loading controller
		if(!$reflection->isPublic() && !template::$loaded && self::$initiating && $controllerFullName == self::$currentController && $method == self::$currentMethod)
		{
			error::set("Controller initiation : load_method","Method wasn't set for public [$controllerFullName@$method]");
			return;
		}

		## has been initiated, and method is private.
		## or in hooking mode.
		if((self::$initiated && $reflection->isPrivate()) || self::$hooking)
		{
			$reflection->setAccessible(true);
			return $reflection->invokeArgs(new $controller(), $paramR);
		}

		if(count($paramR) > $reflection->getNumberOfParameters())
		{
			error::set("Controller initiation : param numbers ","Method [$method] Recieved more than available parameter number.");
			return;
		}

		## if in initiating mode, everything should be ok by now.
		## set initiated to true. as controller has succesfully been initiated.;
		if(self::$initiating)
		{
			self::$initiated			= true;
			self::$initiating			= false;
		}

		### Method Execution!!.
		return call_user_func_array(array($controller,$method),$paramR);
	}

	## return string based state.
	private static function currentState()
	{
		$state	= self::$hooking?"hooking":(self::$initiating?"initiation":(self::$initiated?"load":"hooking"));
		return $state;
	}

	public static function hook($type,$callback)
	{
		$point	= $type;
		$apps	= "_all";

		## check got specified apps or not for hook.
		if(strpos($type, ":") !== false)
		{
			list($apps,$point)	= explode(":",$type);
		}

		if(!in_array($point,Array("route_callback","pre_controller","post_controller","pre_template")))
		{
			error::set("Hook","No such hook point. [$point]");
			return;
		}

		self::$hookListR[$point][$apps][]	= $callback;
	}

	public static function implementHookPoint($type)
	{
		$currentApps	= apps::getCurrent();
		$data			= null; ## set return data, to null.
		if(isset(self::$hookListR[$type]['_all']) || isset(self::$hookListR[$type][$currentApps]))
		{
			self::$hooking	= $type;
			$currentApps	= isset(self::$hookListR[$type]['_all'])?"_all":$currentApps;
			foreach(self::$hookListR[$type][$currentApps] as $callback)
			{
				$result = call_user_func($callback);

				## if result isn't null or false, save it for the return.
				$data	= !$result?$data:$result;
			}
			self::$hooking	= false;
		}

		return $data;
	}

	public static function getCurrentController()
	{
		return self::$currentController;
	}

	public static function getCurrentMethod()
	{
		return self::$currentMethod;
	}

	public static function getLastController()
	{
		return self::$lastController;
	}

	public static function getLastMethod()
	{
		return self::$lastMethod;
	}

	public static function getHooking()
	{
		return self::$hooking;
	}
}
## static class for calling model.
class Model
{
	static $loadedModel	= Array();
	public static function load($model)
	{
		$args	= func_get_args();

		## if already got the model just reuse it.
		if(isset(self::$loadedModel[$model]))
		{
			return self::$loadedModel[$model];
		}

		## else, just create new instance.

		if(!self::_require($model))
		{
			return false;
		}

		## instantiate

		## if use model_namespace
		if(apps::config("model_namespace"))
		{
			$classname	= str_replace("/", "\\", "model\\$model");
		}
		## else, use normal convention.
		else
		{
			$v			= explode("/",$model);
			$classname	= array_pop($v);
			$classname	= "Model_".$classname;
		}

		## got more than 1.
		if(count($args) == 1)
		{
			if(!isset(self::$loadedModel[$model]))
			{
				$theModel	= new $classname();
				self::$loadedModel[$model]	= $theModel;
			}
			else
			{
				$theModel	= self::$loadedModel[$model];
			}
		}
		else
		{
			## remove first param.
			array_shift($args);

			## create reflection obj.
			$reflection	= new ReflectionClass($classname);

			## instantiate model, with parameter. (through reflection.)
			$theModel	= $reflection->newInstanceArgs($args);
		}

		return $theModel;
	}

	public static function _require($model,$alias = Null)
	{
		## require, if not exist set error.
		$path	= apps::getAppsFolder()."/_model/$model.php";
		if(!file_exists($path))
		{
			error::set("model : load","File not found ($model.php).");
			return false;
		}

		## require
		require_once $path;

		if(apps::config("model_namespace"))
		{
			$model		= str_replace("/", "\\", $model);
			$classname	= "model\\$model";
		}
		else
		{
			$classname	= "model_$model";
		}

		if(!class_exists($classname))
		{
			error::set("model : classname","Classname model_$model didn't exists in the loaded model.");
			return false;
		}

		if($alias)
		{
			class_alias($classname,$alias);
		}

		return true;
	}
}

## static class for calling view
class View
{
	static $path_main	= Null;	## main path saved for template::showContent
	static $data_main	= Null; ## main data saved for template::showContent

	## Render view along with the template
	public static function render($view,$data = Array())
	{
		if(controller::getHooking())
		{
			error::set("View Render","Cannot render a view in a hook [".controller::getHooking()." : $view].");
			return;
		}

		$apps	= apps::getCurrent();
		$path	= apps::getAppsFolder()."/$apps/_view/$view.php";

		if(file_exists($path))
		{
			if(!template::$loaded &&  (controller::$forwarding || (controller::getCurrentController() == controller::getLastController() && controller::getCurrentMethod() == controller::getLastMethod())))
			{
				controller::$forwarding = false; ## reset forwarding mode if got any.

				self::$path_main	= $path;
				self::$data_main	= $data;
				template::load();
			}
			else
			{
				extract($data);
				require $path;
			}
		}
		else
		{
			$lastController	= controller::getLastController();
			$lastMethod		= controller::getLastMethod();
			error::set("$apps:$lastController/$lastMethod","Unable to load view [$view]");
		}
	}

	public function withJS()
	{

	}

	public function withCSS()
	{
		
	}
}

## static class for calling template.
class Template
{
	## a flag whether a template has been loaded or not.
	static $loaded 		= false;
	static $template	= null;

	## Used in main template.
	public static function showContent()
	{
		$data	= isset(view::$data_main)?view::$data_main:Array();
		$path	= view::$path_main;

		extract($data);
		require_once $path;
	}

	public static function showJs()
	{
		
	}

	public static function showCss()
	{

	}

	public static function set($flag)
	{
		self::$template	= $flag;
	}

	## to be use in View
	public static function load()
	{
		$apps		= apps::getCurrent();
		$template	= controller::$instance[controller::getCurrentController()]->template;
		#$template	= self::$template === false?false:(isset($template)?$template:"default");
		$template	= self::$template === false?(isset($template)?$template:false):(isset($template)?$template:"default");
		
		## set this flag to true. mean, can't run this function again.
		self::$loaded	= true;

		## user manually set template to false. mean, he wanna load view only.
		if($template === false)
		{
			self::showContent();
			return;
		}
		else
		{
			## prepare path first.
			$path		= apps::getAppsFolder()."/$apps/_template/$template.php";
			if(!file_exists($path))
			{
				$controller	= controller::getCurrentController();
				$method		= controller::getCurrentMethod();

				error::set("template","template (<b>$template</b>) was not found in $apps/_template/. [$apps:$controller/$method]");
				$template	= false;
				return;
			}

			## implement hookpoint for template.
			$data	= controller::implementHookPoint("pre_template");

			if(is_array($data))
			{
				extract($data);
			}

			## require the path.
			require $path;
		}	
	}
}

## Handle error
class Error
{
	private static $errorList	= Array();

	public static function set($name,$val)
	{
		$apps				= apps::getCurrent();
		$currentController	= controller::getCurrentController();
		$currentMethod		= controller::getCurrentMethod();

		$current			= $apps && $currentController && $currentMethod?"$apps:$currentController@$currentMethod":"no_initiation";

		$track				= controller::$loadedListR;

		self::$errorList[$name." ($current)"][]	= Array("message"=>$val,"at"=>$track[count($track)-1],"track"=>$track);
	}

	public static function getErrorParam($type)
	{
		$return	= Array();
		foreach(self::$errorList as $key=>$res)
		{
			foreach($res as $errorParam)
			{
				$return[]	= is_array($errorParam[$type])?implode(" ",$errorParam[$type]):$errorParam[$type];
			}
		}
		return $return;
	}

	public static function show()
	{
		if(count(self::$errorList) == 0)
		{
			return;
		}

		response::setStatus(404,implode(", ",self::getErrorParam("message")));
		if(!request::isAjax())
		{
			echo "<script type='text/javascript'>document.body.innerHTML = '';</script><pre>";
			print_r(self::$errorList);
			echo "</pre>";
		}
	}

	public static function check()
	{
		return count(self::$errorList) > 0?true:false;
	}
}

## library loader class load file under apps/_library
class Library
{
	public static function _require($name)
	{
		require_once "apps/_library/$name.php";
	}
}

?>