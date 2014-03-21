<div class='text'>
A component based on session, basically to help in flashing temporary value on the next page request. Have only 3 api method, set, data and has. <br>
This component was also used by :<br>
- <?php echo html::link("Input","docs/core/input");?> flashing data to re-populate input, or message for certain name.<br> 
- <?php echo html::link("Redirection","docs/core/redirect");?> flash data for success message or error message.<br>
It's encouraged to visit the page for further information.
</div>
<div class='text'><div class='text-title'>1. Set message and flashes!</div>
It's common task in web development, to constantly send/flash a message on the next page. It's usually for telling what happened before. Error message, warning, or success message, or any text message.<br><br>
For example, let say we have this scenario in Controller Hello:
<pre><code>
//let say we just use a typical controller/method routing like http://localhost/mysite/{:controller}/{:method}
Class Controller_Hello
{
	//basically by executing this controller will redirect to http://localhost/mysite/hello/mars with the message set.
	public function earth()
	{
		flash::set("some_name","some message");
		redirect::to("hello/mars")
	}

	//if this is executed through redirection, it echo the message. else, it echo nothing.
	public function mars()
	{
		echo flash::data('some_name',"a message.");
	}
}
</code></pre>
</div>

<div class='text'><div class='text-title'>2. Flashing _main message.</div>
By default if you echo flash::data() without parameter. It will set default parameter as _main. It's useful, because in redirection, you can set a message like this :
<pre><code>
//in some controller..
public function fly()
{
	redirect::to("hello/somewhere","Some message to be set in _main.");
}
</code></pre>
And in the hello controller :
<pre><code>
public function somewhere()
{
	// will echo the redirection message.
	echo flash::data();
}
</code></pre>
</code></pre>
</div>