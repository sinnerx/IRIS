<div class='text'>
A common component that was written to manage a session in some classy way. Basically have like 4 api method : set, get, has, destroy. Inspired by laravel in using a dot notation syntax, in storing multi dimensional session. It uses the native php $_SESSION as a medium of storage transaction.
</div>
<!-- Set, has, get and destroy -->
<div class='text'><div class='text-title'>1. Set, has?, get and destroy a session.</div>
1. Assigning a value :
<pre><code class='hljs javascript'>
//same as $_SESSION['myname']	= "ahmad rahimie.";
session::set("myname","ahmad rahimie.");
</code></pre>
2. Checking a session :
<pre><code>
// basically it just return true if session key was found, false, or otherwise.
if(session::has("myname"))
{
	// something to do.
}
</code></pre>
3. Retrieving the session value.
<pre><code>
## same as $myname = $_SESSION['myname'];
$myname	= session::get("myname");
</code></pre>
4. Destroying the session.
<pre><code>
//same as unset($_SESSION['myname']);
session::destroy("myname");

//same as session_destroy();
session::destroy();
</code></pre>
</div>
<!-- Dot notation -->
<div class='text'><div class='text-title'>2. Multi-dimensional value assignment</div>
Assigning a multidimensional session :
<pre><code class='hljs javascript'>
session::set("user.name","Rahimie");			// $_SESSION['user']['name']		= "Rahimie";
session::set("user.level","Pro-grammer");	// $_SESSION['user']['level']		= "Pro-grammer";
</code></pre>
Retrieving the session value :
<pre><code>
$mylevel	= session::get("user.level"); 	//return pro-grammer.
</code></pre>
Destroying the session :
<pre><code>
//simply destroy $_SESSION['user']['name'];
session::destroy("user.name");

//or destoy by the both we set before..
session::destroy("user")
</code></pre>
</div>