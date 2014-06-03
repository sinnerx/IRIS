var pim = function(conf)
{
	this.base_url	= conf['base_url']+"/";
	this.redirect	= function(url)
	{
		window.location.href	= this.base_url+url;
	}
	this.uriHash	= new function()
	{
		this.callbacks	= {};
		var context	= this;
		this.get	= function(segment)
		{
			uri	= window.location.href.split("#")[1];
			uri	= segment?uri.split("/")[segment]:uri;

			return uri?uri:false;
		}
		this.set	= function(txt)
		{
			window.location.hash	= txt?txt:"";
		}
		this.addCallback = function(arr)
		{
			//merge every registration.
			this.callbacks	= $.extend(this.callbacks,arr);
		}
		this.runCallback	= function(hash)
		{
			//if isn't set, execute based on uri.
			var hash	= hash?hash:this.get();

			$(document).ready(function()
			{
				//loop and find and execute.
				for(i in context.callbacks)
				{
					if(i == hash)
					{
						//execute.
						context.callbacks[i]();
					}
				}
			});
		}

		//run all associated callback.
		$(document).ready(function()
		{
			context.runCallback();
		});
	}

	this.func	= new function()
	{
		this.switchShow	= function(first,second)
		{
			$(first).slideDown();
			$(second).slideUp();
		}
	}
}