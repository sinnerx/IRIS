<div class='text'>
	Helps you in getting an absolute URL, by writing a relative one. A lot of time, developers tend to write a relative kind of url. So,thing is, there's a number of time one need to configure his environment to suits this kind of problem.
</div>
<div class='text'><div class='text-title'>1. Base URL</div>
<div class='text-subtitle'>1.1 Configuring</div>
Base URL, is basically a root URL for your application. You can set it in config files :
<pre><code>
apps
  _config
      <u>config.php</u>
</code></pre><br>
There're three way of configuring your base url. <br>
First, by environment key :
<pre><code>
$config['dev_prod'][]	= Array(
					"base_url"=>"http://localhost/mysite")
</code></pre>
Second, by domain name, which we encourage, because it's easier :
<pre><code>
$config['domain']['localhost'][]			= Array(
								"base_url"=>"http://localhost/mysite"
											);
$config['domain']['exedra.rosengate.com'][]	= Array(
								"base_url"=>"http://exedra.rosengate.com/mysite"
													);
</code></pre>
Third, by simply declaring base_url as key in your config variable.
<pre><code>
$config['base_url']	= "http://localhost/mysite";
</code></pre>
<br>
<div class='text-subtitle'>1.2 Usage</div>
You can use it by simply calling
<pre><code>
// return the base_url (http://localhost/mysite).
$url	= url::base();	

// return the url, appended after base_url. (http://localhost/mysite/about/profile)
$url	= url::base("about/profile");

// or you can use this. It's the same thing, just an alias.
$url	= url::to()
</code></pre>
</div>

<div class='text'><div class='text-title'>Asset URL</div>
Basically it's a URL to that points asset's folder, which hold your files like css, js, icons and etc. 
<br>You can configure it like the same way you configure base_url. Example :
<pre><code>
$config['asset_url']	= "http://localhost/mysite/assets";
</code></pre>
Your assets folder is just located at your root file. Just call this :
<pre><code>
// get the folder path.
$asset_folder	= url::asset();

// get url to some file.
$js_url	= url::asset('js/jquery.min.js');
</code></pre>
</div>