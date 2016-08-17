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
			p1mloader.start("#widget-requestlist");
			$.ajax({type:"GET",url:href}).done(function(txt)
			{
				p1mloader.stop("#widget-requestlist");
				$("#widget-requestlist").html(txt);
				context.overview.prepareLinking();
			});
		};

		this.clearRequest = function(href)
		{
			p1mloader.start("#widget-requestlist");
			$.ajax({type:"GET",url:href}).done(function(txt)
			{
				p1mloader.stop("#widget-requestlist");
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
<style type="text/css">
.kpi-font {
	font-size: 20px;
}
.panel-pd {
	padding: 0;
}
.panelhd-pd {
	padding: 5px;
}
.custom-fa-pad {
	padding-top:5px;
}
</style>
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
				<!-- <a href='<?php echo url::base("site/kpiMonthly/".$year);?>'  class='fa fa-external-link' data-toggle='ajaxModal' style="color:green;"> KPI yearly view</a>				 -->
				<?php echo form::select("month",model::load("helper")->monthYear("month"),'onchange="site.overview.updateDate();" class="form-control" style="display: inline; width: 100px;"',$month);?>
				<?php echo form::select("year",model::load("helper")->monthYear("year"),'onchange="site.overview.updateDate();" class="form-control" style="display: inline; width: 100px;"',$year);?>			
			</div>	
			</div>
		</div>
	</div>
	</div>



<form method='post'>


<div class='row' style="padding-top: 20px;">
	<div class='col-lg-12'>

			<div class='col-sm-6 col-md-4' >
				<section class="panel panel-default panel-pd">
					<header  class="panel-heading panelhd-pd">
					Event  
					<?php if ($kpi['event'] >= $max['event']) {  ?>
					<i class="fa fa-check fa-lg pull-right custom-fa-pad" style="color:green"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg pull-right custom-fa-pad" style="color:red"></i>
					<?php }  ?>
					</header>
					

					<div class="panel-body text-center">
						<div class="col-md-12">
                            <a class="block hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-danger hover-rotate"></i>
                                <i class="i i-list2 i-1x text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-danger kpi-font"><?php echo  ($kpi['event']); ?> / <?php echo  $max['event']; ?></span>
                                <small class="text-muted text-u-c">Event Created</small>
                              </span>
                            </a>
                             <a href='<?php echo url::base("activity/add#event");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Create Event</a>
                        </div>
                    </div>



				</section>
			</div>

			<div class='col-sm-6 col-md-4' >
				<section class="panel panel-default panel-pd">
					<header  class="panel-heading panelhd-pd">
					Entrepreneurship Class
					<?php if ($kpi['entrepreneurship_class'] >= $max['entrepreneurship_class']) {  ?>
					<i class="fa fa-check fa-lg pull-right custom-fa-pad" style="color:green"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg pull-right custom-fa-pad" style="color:red"></i>
					<?php }  ?>

					</header>

					<div class="panel-body text-center">
						<div class="col-md-12">
                            <a  class="block hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-success-lt hover-rotate"></i>
                                <i class="i i-study i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-success kpi-font"><?php echo  ($kpi['entrepreneurship_class']); ?> / <?php echo  $max['entrepreneurship_class']; ?></span>
                                <small class="text-muted text-u-c">Class Created </small>
                              </span>
                            </a>
                            <a href='<?php echo url::base("activity/add#training");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Create Class</a>
                        </div>
                    </div>

				</section>
			</div>

			<div class='col-sm-6 col-md-4'>
				<section class="panel panel-default panel-pd">
					<header  class="panel-heading panelhd-pd">
					Entrepreneurship Program 
					 <?php if ($kpi['entrepreneurship_sales'] >= $max['entrepreneurship_sales']) {  ?>
					<i class="fa fa-check fa-lg pull-right custom-fa-pad" style="color:green"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg pull-right custom-fa-pad" style="color:red"></i>
					<?php }  ?>
					</header>
					
					<div class="panel-body text-center">	
						<div class="col-md-12">
                            <a  class="block hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-info hover-rotate"></i>
                                <i class="i i-statistics i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-info kpi-font"><?php echo  ($kpi['entrepreneurship_sales']); ?> / <?php echo  $max['entrepreneurship_sales']; ?></span>
                                <small class="text-muted text-u-c">Sales  ( RM )</small>
                              </span>
                            </a>
                            <a href='#' class='fa' style="float:right;">&nbsp;</a>
                            <!-- <a href='<?php echo url::base("sales/add");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Insert Sales</a> -->
                        </div>
                    </div>    	
				</section>	
			</div>	

			<div class='col-sm-6 col-md-4'> 								
                <section class="panel panel-default panel-pd">
                <header class="panel-heading panelhd-pd">
                Training
                <?php if ($kpi['training_hours'] >= $max['training_hours']) {  ?>
					<i class="fa fa-check fa-lg pull-right custom-fa-pad" style="color:green"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg pull-right custom-fa-pad" style="color:red"></i>
					<?php }  ?>
                </header>
                		
                	<div class="panel-body text-center">
                		<div class="col-md-12">
                            <a class="block hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-primary hover-rotate"></i>
                                <i class="i i-laptop i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h1 block m-t-xs text-primary kpi-font"><?php echo  $kpi['training_hours']; ?> / <?php echo  $max['training_hours']; ?></span>
                                <small class="text-muted text-u-c">Training Hours</small>
                              </span>
                            </a>
                            <a href='<?php echo url::base("activity/add#training");?>'  class='fa  fa-plus-square-o' style="color:green;float:right;"> Create Training</a>
                        </div>
                    </div>

              </section>
			</div>

			<div class="col-sm-6 col-md-4">
                <section class="panel panel-default panel-pd">
                  <header class="panel-heading panelhd-pd">
                    Active member (login) 
                    <?php if ($kpi['active_member_percentage'] >= 40) {  ?>
					<i class="fa fa-check fa-lg pull-right custom-fa-pad" style="color:green"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg pull-right custom-fa-pad" style="color:red"></i>
					<?php }  ?>
                  </header>
                 
                  	<div class="panel-body text-center">
                 		<div class="col-md-12">
                            <a class="block hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-primary hover-rotate"></i>
                                <i class="i i-users2 i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                
                                <span class="h1 block m-t-xs text-primary kpi-font"><?php echo  round($kpi['active_member_percentage'], 2); ?>%</span>
                                <small class="text-muted text-u-c">Member login</small>
                              </span>
                            </a>
                            <a href='#' class='fa' style="float:right;">&nbsp;</a>
                        </div>
                    </div>
                </section>
            </div>


            <div class="col-sm-6 col-md-4">
                <section class="panel panel-default panel-pd">
                  <header class="panel-heading panelhd-pd">
                    Total of Site Member 
                    <?php if ($kpi['active_member_percentage'] >= 40) {  ?>
					<i class="fa fa-check fa-lg pull-right custom-fa-pad" style="color:green"></i>
					<?php } else { ?>
					<i class="fa fa-exclamation fa-lg pull-right custom-fa-pad" style="color:red"></i>
					<?php }  ?>
                  </header>
                 
                  	<div class="panel-body text-center">
                 		<div class="col-md-12">
                            <a class="block hover">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-primary hover-rotate"></i>
                                <i class="i i-users2 i-sm text-white"></i>
                              </span>
                              <span class="clear">
                                
                                <span class="h1 block m-t-xs text-primary kpi-font"><?php echo $kpi['total_members']; ?></span>
                                <small class="text-muted text-u-c">Members</small>
                              </span>
                            </a>
                            <a href='#' class='fa' style="float:right;">&nbsp;</a>
                        </div>
                    </div>
                </section>
            </div>

	</div>
</div>
</form>

<div class='row'>
	<div class='col-sm-12'>
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


<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css");?>" type="text/css" />

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js");?>"></script>							
	