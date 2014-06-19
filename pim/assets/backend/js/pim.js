var pim = function(conf)
{
	var $ = jQuery; //dependant.
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

	this.ajax	= new function()
	{
		this.urlify	= function(selector,container,attrName)
		{
			// if set, will use this attribute, as url storer.
			var attrName	= attrName?attrName:"href";
			$(selector).click(function()
			{
				var href	= $(this).attr(attrName);

				if(href)
				{
					$.ajax({url:href,type:"GET"}).done(function(txt)
					{
						$(container).html(txt);
					});
				}

				return false;
			});
		}

		//ajaxify form.
		this.formify = function(form,container,callback)
		{
			$(form).submit(function(e)
			{
				// gather all name.
				var data	= {};
				var method	= $(this).attr("method")?$(this).attr("method"):"GET";
				$(this).find("input, select").each(function(i,e)
				{
					if($(e).attr("name"))
					{
						data[$(e).attr('name')] = $(e).val();
					}
				});

				// AJAX FABULOUSo.
				var url	= $(this).attr("action");
				var res = $.ajax({type:method,data:data,url:url});

				if(container)
				{
					res.done(function(txt)
					{
						$(container).html(txt);
					});
				}

				if(callback)
				{
					res.done(function(txt)
					{
						callback(txt);
					});
				}

				return false;
			});
		}

		this.getModal = function(url)
		{
			$('#ajaxModal').remove();
			var modal = $('<div class="modal fade" id="ajaxModal"><div class="modal-body"></div></div>');
			$("body").append(modal);
			modal.modal();
			modal.load(url);
		}
	}

	this.upload = new function()
	{
		this.upload	= 1;
		this.execute	= function(fileID,action)
		{
			$("body").append("<iframe style='display:none;' name='pim_upload_frame"+this.upload+"'></iframe>");
			$(fileID).wrap("<form method='post' id='pim_upload_form"+this.upload+"' action='"+action+"' target='pim_upload_frame"+this.upload+"' enctype='multipart/form-data'></form>");

			$("#pim_upload_form"+this.upload).submit();
			this.upload++;
		}
	}
	

	this.func	= new function()
	{
		this.switchShow	= function(first,second)
		{
			$(first).slideDown();
			$(second).slideUp();
		}

		this.inArray	= function(n,arr)
		{
			for(i in arr)
			{
				if(arr[i] == n)
				{
					return true;
				}
			}

			return false;
		}

		this.arrayRemoveElement	= function(e,v)
		{
			var newr	= [];
			for(i in e)
			{
				if(e[i] != v)
				{
					newr.push(e[i]);
				}
			}

			return newr;
		}
	}
}