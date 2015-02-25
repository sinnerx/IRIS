<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css");?>" type="text/css" />
<script type="text/javascript">
var site = new function()
{
	var context	= this;
	this.overview = new function()
	{
		this.updateDate = function()
		{
			window.location.href = pim.base_url+"site/overview/"+$("#year").val()+"/"+$("#month").val();
		}

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
}


</script>
<h3 class='m-b-xs text-black'>
Overview
</h3>
<div class='well well-sm'>
Dashboard overview
</div>


	<div class='row'>
	<div class='col-lg-12 pull-right'>
		<div class='row'>
			<div class='col-lg-6' >
			</div>	

			<div class='col-lg-6'>			
			<div style="float:right">
				<a href='<?php echo url::base("site/kpiMonthly/".$year);?>'  class='fa fa-external-link' data-toggle='ajaxModal' style="color:green;"> KPI monthly view</a>				
				<?php echo form::select("month",model::load("helper")->monthYear("month"),'onchange="site.overview.updateDate();"',$month);?>
				<?php echo form::select("year",model::load("helper")->monthYear("year"),'onchange="site.overview.updateDate();"',$year);?>			
			</div>	
			</div>
		</div>
	</div>
	</div>



<form method='post'>


<div class='row'>
	<div class='col-lg-12 pull-right'>
		<div class='row'>

			<div class='col-lg-4' >
				<section class="panel panel-default">
					<header  class="panel-heading">
					Event  
					<?php if ($data['event']['done'] == '1') {  ?>
					<i class="fa fa-check fa-lg" style="color:green;float:right;"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg" style="color:red;float:right;"></i>
					<?php }  ?>
					</header>
					

					<div class="panel-body text-center">
						<div class="col-md-12">
                            <a class="block padder-v hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-danger hover-rotate"></i>
                                <i class="i i-list2 i-1x text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-danger" style="font-size: 62px; margin-top: -10px;"><?php echo  ($data['event']['totalEvent']); ?> / <?php echo  $data['event']['maxEvent']; ?></span>
                                <small class="text-muted text-u-c">Event Created</small>
                              </span>
                            </a>
                             <a href='<?php echo url::base("activity/add#event");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Create Event</a>
                        </div>
                    </div>



				</section>
			</div>

			<div class='col-lg-4' >
				<section class="panel panel-default">
					<header  class="panel-heading">
					Entrepreneurship Class
					<?php if ($data['entClass']['done'] == '1') {  ?>
					<i class="fa fa-check fa-lg" style="color:green;float:right;"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg" style="color:red;float:right;"></i>
					<?php }  ?>

					</header>

					<div class="panel-body text-center">
						<div class="col-md-12">
                            <a  class="block padder-v hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-success-lt hover-rotate"></i>
                                <i class="i i-study i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-success"  style="font-size: 62px; margin-top: -10px;"><?php echo  ($data['entClass']['totalEvent']); ?> / <?php echo  $data['entClass']['maxClass']; ?></span>
                                <small class="text-muted text-u-c">Class Created </small>
                              </span>
                            </a>
                            <a href='<?php echo url::base("activity/add#training");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Create Class</a>
                        </div>
                    </div>

				</section>
			</div>



			<div class='col-lg-4'>
				<section class="panel panel-default">
					<header  class="panel-heading">
					Entrepreneurship Program 
					 <?php if ($data['entProgram']['done'] == '1') {  ?>
					<i class="fa fa-check fa-lg" style="color:green;float:right;"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg" style="color:red;float:right;"></i>
					<?php }  ?>
					</header>
					
					<div class="panel-body text-center">	
						<div class="col-md-12">
                            <a  class="block padder-v hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-info hover-rotate"></i>
                                <i class="i i-statistics i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-info" style="font-size: 58px; margin-top: -8px;"><?php echo  ($data['entProgram']['total']); ?> / <?php echo  $data['entProgram']['maxSale']; ?></span>
                                <small class="text-muted text-u-c">Sales  ( RM )</small>
                              </span>
                            </a>
                            <a href='<?php echo url::base("sales/add");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Insert Sales</a>
                        </div>
                    </div>    	
				</section>	
			</div>
		</div>


		<div class='row'>

			<div class='col-lg-4'> 								
                <section class="panel panel-default">
                <header class="panel-heading">
                Training
                <?php if ($data['training']['done'] == '1') {  ?>
					<i class="fa fa-check fa-lg" style="color:green;float:right;"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg" style="color:red;float:right;"></i>
					<?php }  ?>
                </header>
                		
                	<div class="panel-body text-center">
                		<div class="col-md-12">
                            <a class="block padder-v hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-primary hover-rotate"></i>
                                <i class="i i-laptop i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-primary" style="font-size: 62px; margin-top: -10px;"><?php echo  ($data['training']['hour']); ?> / <?php echo  $data['training']['maxHour']; ?></span>
                                <small class="text-muted text-u-c">Training Hours</small>
                              </span>
                            </a>
                            <a href='<?php echo url::base("activity/add#training");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Create Training</a>
                        </div>
                    </div>

              </section>
			</div>	
	

			<div class="col-lg-4">
                <section class="panel panel-default">
                  <header class="panel-heading">
                    Active member (login) 
                    <?php if ($data['login']['done'] == '1') {  ?>
					<i class="fa fa-check fa-lg" style="color:green;float:right;"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg" style="color:red;float:right;"></i>
					<?php }  ?>
                  </header>
                 
                  	<div class="panel-body text-center">
                 		<div class="col-md-12">
                            <a class="block padder-v hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-primary hover-rotate"></i>
                                <i class="i i-users2 i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                
                                <span class="h1 block m-t-xs text-primary" style="font-size: 62px; margin-top: -10px;"><?php echo  $data['login']['target']; ?></span>
                                <small class="text-muted text-u-c">Member login</small>
                              </span>
                            </a>

                        </div>
                    </div>
                </section>
                </div>


		</div>

<!--             <div class='col-sm-12' id='widget-requestlist'>
			<?php
			## load it first along with the first request;
		//	controller::load("sitemanager/ajax_request","lists");?>
			</div>



			<div class='col-sm-12' id='widget-messagelist'>
			<?php 
			## load it first along with the first request;
		//	controller::load("sitemanager/ajax_message","lists");?>
			</div> -->


	</div>
</div>
</form>
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css");?>" type="text/css" />

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>							
	