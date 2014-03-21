<div class='text'>
As part of M.V.C. View serves as a layout, or a place where you most of your html code in. It interact highly with controller. You can also, call a model in here. Since Exedra highly self-claimed itself as the best simple H.M.V.C framework, number of things could be done with that. What's actually an HMVC, we will go through that.
</div>
<div class='text'><div class='text-title'>1. View location</div>
Your view folder is located individually inside your application folder. Same like _controller. Here's for example :
<pre><code class='hljs javascript'>
apps
  default
    <b>_view</b>
</code></pre>
</div>

<div class='text'><div class='text-title'>2. Rendering a view</div>
You can render a view through a controller, like this :
<div class='text-subtitle'>1. Render</div>
<pre><code>
class Controller_Home
{
	public function index()
	{
		view::render("home/index");
	}
}
</code></pre>
Okay, then, if there's a file 'apps/default/_view/home/index.php' it will render, else it will invoke some error.<br><br>
<div class='text-subtitle'>2. Passing a data</div>
You can pass a data, on which the key, will be extracted as variable inside the view file.
<pre><code>
$data['first_data']		= "test";
$data['second']		= Array("some","array","record");
$data['list']			= model::load("news")->lists();

view::render("'home/index",$data);
</code></pre>
So, in your view you can access the data just like this :
<pre><code class='hljs php'>
//inside apps/default/_view/home/index.php.
&lt;?php
echo "First data ".$first_data;
echo implode(",",$second);
echo implode(",",$list);
?&gt;
</code></pre>
Simple, and bonne nuit!!
</div>