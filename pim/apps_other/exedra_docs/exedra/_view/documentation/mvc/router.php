<div class='text'>
	This component handle everything you want to do with your URI routing. And basically just had only 2 super method.<br>
	<b>Add</b>, and <b>Dispatch</b>.<br>
	Concepts :<br>
	- You can add as many route as you can using method add().<br>
	- But everytime dispatch() is called, all the route you ever added before will be removed.<br>
	- add() method can be chained. Refer documentation below.<br><br>
	Anyway, routing concept might looks compilcated to newer programmer.
</div>
<div class='text'><div class='text-title'>1. Setting Up Scenario</div>
	Let us set up some scenario first. Let say, you installed Exedra under <b>http://localhost/mysite</b>, then, everything you added in routing are started after that.
</div>
<!-- Basic Usage -->
<div class='text'>
	<div class='text-title'>
	2. Basic Usage
	</div>
	Let us start with below example :
<pre><code>
//in core/routing.php
apps::run(function($router)
{
	$router->add("news",function()
	{
		//do something here. or initiate a controller, like this :
		controller::init("news","index");
	});

	//dispatch the route list.
	$router->dispatch();
});
</code></pre>
	Okay, that says : everytime you're trying to access http://localhost/mysite/<b>news</b>, it will try to initiate controller 'news' and method 'index'. However there's a simpler method in initiating a controller through routing. I'll talk about that later. For more info about controller, refer <?php echo html::link("here","docs/mvc/controller");?>
</div>
<!-- GET, POST, PUT, DELETE -->
<div class='text'><div class ='text-title'>3. GET, POST, PUT, DELETE</div>
	Chances are, you probably wanna filter your application with some restful route. No worry because, Exedra also provide that service.
<pre><code>
//in core/routing.php
apps::run(function($router)
{
	//single method.
	$router->add("GET","news",function()
	{
		//do something.
	});

	//multiple method.
	$router->add(Array("GET","POST"),"news",function()
	{
		//do anything.
	});

	$router->dispatch();
}
</code></pre>
As you can see there's <b>two</b> way of filtering by method.
</div>
<!-- Controller Initiation -->
<div class='text'><div class='text-title'>4. Controller initiation</div>
As shown before, you can see that there's one method to invoke a controller, but there's simpler one. I ll show you both :
<pre><code>
//first method.
$router->add("news",function()
{
	controller::init("news","index");
}

//second method.
$router->add("news","controller=news@index");

$router->dispatch();
</code></pre>
Basically both invoke the same thing. We just parsed that string and initiate it in the background.
</div>
<!-- Add a route list -->
<div class='text'><div class='text-title'>5. Add a route list.</div>
Like many other framework, they usually store their routing inside some file. But, actually, it just loaded an array from that file. I'll show you that.
<pre><code>
//in core/routing.php
apps::run(function($router)
{
	//from array.
	$myRoutes	= Array(
			"GET","news","controller=news@index",
			"news/latest","controller=news@latest"
						);

	$router->add($myRoutes);

	//from array returned by some file.
	$router->add("apps/_structure/routes.php");

	//dispatch.
	$router->dispatch();
}

//in apps/_structure/routes.php"
$routes	= Array(
	"about","controller=home@about",
	"contact-me","controller=home@contact"
				);

return $routes;
</code></pre>
Actually anything from that array, are just a parameter for the add() method. Exedra just recursively added the route that way.
</div>
<!-- Named Parameters -->
<div class='text'><div class='text-title'>6. Named Parameters</div>
What is a routing without specifying parameters. Of course because Exedra provided a simple method to specify a named parameter. 
<pre><code>
//1. Typical controller/method passing.
$router->add("[:controller]/[**:method]",function($param)
{
	## you can get both parameter name from $param
	$controller	= $param['controller'];
	$method		= $param['method'];

	## then init the controller like the usual way.
	controller::init($controller,$method);
});

//2. Or simple way for above.
$router->add("[:controller]/[**:method]","controller={controller}@{method}");
</code></pre>
By the example above, executing <b>http://localhost/mysite/<u>news</u>/<u>index</u></b> will basically try to initiate a controller called 'news' and method called 'index'. Anyway, What is that trailing star in [**:method]? Ah, it actually is a way to capture the rest of uri segment after [:controller]/. So, that the controller initiation could translate the rest of URI in :method, <u>to be passed to the controller method as parameter/s.</u><br><br>
For example, let say we are executing <b>http://localhost/mysite/hello/super/earth/hour</b>
<pre><code>
//in core/routing.php
## and we have this route.
$router->add("hello/[**:method]","controller=hello@{method}");

//and in our <b>Hello</b> controller at : apps/default/_controller/hello.php
class Controller_Hello
{
	public function super($param1,$param2)
	{
		//echoing $param1 and $param2 will give you 'earth hour'.
		echo $param1." ".$param2;
	}
}
</code></pre>
<br>
You can also simply pass a parameter manually without trailing, to controller initiation, like this :
<pre><code>
//traditional way.
$router->add("hello/[:first]/[:second]",function($param)
{
	## 1st traditional way.
	controller::init("hello","super",Array($param['first'],$param['second']));

	## OR second traditional way (can't init 2 times this is example.)
	controller::init("hello","super","{first},{second}");
});

//result just like above.
$router->add("hello/[:first]/[:second]","controller=hello@super","{first},{second}");
</code></pre>
</div>
<!-- Domain Routing -->
<div class='text'><div class='text-title'>7. Domain routing</div>
There're chances that you might want filter a route based on current domain. Here's how you do it.
<pre><code>
//this is way to permit all domain
$router->add("domain:all",function($param)
{
	## you can get the domain name, by accessing key 'domain_name';
	$domain_name	= $param['domain_name'];
});

//while this is to specifically list.
$router->add("domain:localhost,exedra.rosengate.com",function($param)
{
	## something to do.
});
</code></pre>
You don't actually need to dispatch this route. Because, it is instantly executed. And aren't saved in route list.
</div>