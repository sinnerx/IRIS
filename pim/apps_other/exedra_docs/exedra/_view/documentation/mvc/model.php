<div class='text'>
Writing up a model is as easy as a piece of cake. However, it's adviced to not be confused with 'model' in some framework. In some framework, developers are often told that 'model' is a representation of database entities. Which what not Exedra preaches. We see model as a place to store your Domain logic. <br><br>Futhermore, we didn't implement any ORM here. ;)
</div>
<div class='text'><div class='text-title'>1. Creating a Model</div>
Models are basically shared across all your application. All your model stays directly under the folder 'apps/_model'. For example let's create a model called 'news'. First, create a file.
<pre><code>
apps
  _model
      <u>news.php</u>
</code></pre>
We've done creating a file. Now, let's write some code like :<br>
- defining class name,<br>
- writing method.
<pre><code>
//apps/_model/news.php
class Model_News
{
	public function lists()
	{
		## let's do something, like fetching news from database using query builder.
		$newslist	= db::get("news")->result();

		## return the result.
		return $newslist;
	}
}
</code></pre>
As we can see, we used a prefix 'Model_' for the model classname. This is to avoid class name collision. Yes, you can use namespace or anything you preferred. ;)<br>
<br>
</div>

<div class='text'><div class='text-title'>2. Using a model.</div>
Okay. Next, how about using it in our controller. Say, we got a controller named 'Home', with method 'index'. And do something like :<br>
- Accessing model.<br>
- Use it.
<pre><code>
// in apps/default/_controller/home.php
class Controller_Home
{
	public function index()
	{
		## fetch the model.
		$news	= model::load("news");

		## maybe then we can bind it for view.
		$data['news']	= $news;

		view::render("home/index",$data);
	}
}
</code></pre>
Yes, it's as simple as that. Every model loaded are saved, and everytime you load [model::load("news")], it will use the same instance.<br> 
However, you can still create a new instance, as simple as passing second parameter as true :
<pre><code>
$news = model::load("news",true);
</code></pre>
</div>