<?php
require_once "libraries/Framework.php";

apps::run(ROOT_FOLDER,function($router)
{
	$router->add("domain:all",function($param) use($router)
	{
		## db connection.
		db::connect(apps::config('db_host'),apps::config('db_user'),apps::config('db_pass'),apps::config("db_name"));

		if($param['domain_name'] == "localhost")
		{
			## dispatch this single route at test/
			$router->add("[:controller]/[**:method]","controller=tests:{controller}@{method}")
			->add(function(){error::show();})
			->dispatch("test");
		}

		## if current domain is localhost and exedra.
		if(in_array($param['domain_name'], Array("localhost","p1m.gaia.my","celcom1cbc.com","dev.celcom1cbc.com","pro.celcom1cbc.com")))
		{
			## require exedra docs application only in localhost.
			if($param['domain_name'] == "localhost")
				$router->add("apps_other:exedra_docs")->dispatch("exedradocs");

			## add kepler
			$router->add("apps_other:galileo")->dispatch("monitor");

			## for p1m.gaia.my git pull request script.
			if($param['domain_name'] == "p1m.gaia.my")
			{
				$router->add("gitpull",function()
				{
					# outside public_html
					require_once "../../php_scripts/gitpull.php";
				});
			}

			## require main routes;
			$router->add("apps/_structure/routes.php");

			## no route found at all.
			$router->add('_404',function()
			{
				require_once "pim/apps/404.php";
			});
		}

		if(isset($_GET['db_update']))
		{
			$db_creator	= new db_creator();
			$db_creator->execute();
		}

		## show error for development environment.
		$router->add(function()
		{
			if(apps::environment() == "dev")
			{
				error::show();
			}
			else
			{
				## else if not in development environment, and got error..
				if(error::check())
				{
					#require_once "apps/404.php";
				}
			}
		});

		## main dispatch.
		$router->dispatch();
	});
},$argv);


?>