<script type="text/javascript">
var site = new function()
{
	var context	= this;
	this.overview = new function()
	{
		this.getRequestList	= function(href)
		{
			$.ajax({type:"GET",url:href}).done(function(txt)
			{
				p1mloader.start("#widget-requestlist");
				$("#widget-requestlist").html(txt);
				context.overview.prepareLinking();
			});
		};

		this.clearRequest = function(href)
		{
			$.ajax({type:"GET",url:href}).done(function(txt)
			{
				p1mloader.start("#widget-requestlist");
				$("#widget-requestlist").html(txt);
				context.overview.prepareLinking();
			});
		}

		this.prepareLinking = function()
		{
			//if there is.
			if($("#widget-requestlist")[0])
			{
				this.ajaxHref("#widget-requestlist .pagination-numlink a",context.overview.getRequestList);
				this.ajaxHref(".clearRequest",context.overview.clearRequest);
				this.ajaxHref("#widget-messagelist .pagination-numlink a",context.overview.getMessageList);
			}
		};

		// function to replace link invoke.
		this.ajaxHref = function(selector,callback)
		{
			$(selector).each(function(i,val)
			{
				var href	= $(val).attr("href");
				$(val).attr("href","javascript:void(0)").click(function()
				{
					callback(href);
				});
			})
		}

		this.getMessageList	= function(href)
		{
			$.ajax({type:"GET",url:href}).done(function(txt)
			{
				$("#widget-messagelist").html(txt);
				context.overview.prepareLinking();				
			});
		}
	};

	$(document).ready(function()
	{
		context.overview.prepareLinking();
	});
}


</script>
<h3 class='m-b-xs text-black'>
Overview
</h3>
<div class='well well-sm'>
Dashboard overview
</div>
<div class='row'>
	<div class='col-sm-12 pull-right'>
		<div class='row'>
			<div class='col-sm-12' id='widget-requestlist'>
			<?php
			## load it first along with the first request;
			controller::load("sitemanager/ajax_request","lists");?>
			</div>
			<div class='col-sm-12' id='widget-messagelist'>
			<?php 
			## load it first along with the first request;
			controller::load("sitemanager/ajax_message","lists");?>
			</div>
		</div>
	</div>
</div>