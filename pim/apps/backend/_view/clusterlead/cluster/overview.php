<style type="text/css">
	
.no-sites
{
	font-size:30px;
	padding:10px 150px 0px 150px;
	text-align: center;
	opacity: 0.5;
	text-shadow:3px 3px 10px #9a9a9a;
}

.state-name
{
	font-weight: bold;
	color: #177bbb;
    display: block;
    font-size: 16px;
    margin-bottom: 20px;
    text-transform: uppercase;
}


.site-name{
	margin-bottom:20px;
	
}

.state-site-list
{
	margin-bottom:10px;
}

.site-name.unfloat
{
	float:none;
	border-bottom:1px dashed #e4e9f1;
}

.panel-default{
	padding:0px !important;
	
}


.col-sm-9 h4{
	line-height:normal !important;
	
}
.site-name a {
    transition: all 0.4s ease-out 0s;
}

.site-name a.badge{
	padding-left:7px;
	padding-right:7px;
	
}

.second-block{
padding: 0 !important;	
}


.row.state-list{
padding:20px 25px;	
}

#site-detail .panel-body{
	padding-left:25px;
	padding-right:25px;
	
}

#site-detail .pull-right{
	margin-right:25px;
	margin-top:15px;
	
}


#site-detail .btn-wrapper .pull-right{
	margin-right:0px;
	margin-top:0px;
	
}

#request-list{
	padding:25px;
	
}

</style>
<script type="text/javascript">
var base_url	= "<?php echo url::base();?>/";

// factored under cluster.
var cluster	= new function()
{
	this.overview	= new function()
	{
		var context	= this;
		this.currentSiteID	= null;
		this.showDetail	= function(txt)
		{
			//show txt in site-detail.
			$("#site-detail").show().html(txt);
		};

		// get detail.
		this.getDetail	= function(siteID)
		{
			//use a shared ajax controller.
			p1mloader.start("#site-detail");
			$.ajax({type:"GET",url:base_url+"ajax/shared/site/getDetail/"+siteID}).done(function(txt)
			{
				context.showDetail(txt);
			});
		};

		this.getSiteRequests = function(siteID)
		{
			this.currentSiteID	= siteID;
			p1mloader.start("#site-detail");
			$.ajax({type:"GET",url:base_url+"ajax/request/lists/"+siteID}).done(function(txt)
			{
				context.showDetail(txt);
			});
		};

		this.minimize	= function()
		{
			$("#site-list").removeClass("col-sm-6").addClass("col-sm-2");
			$("#site-detail").removeClass("col-sm-6").addClass("col-sm-10");

			$(".site-name").addClass("unfloat").removeClass("col-sm-4").addClass("col-sm-12");
		}

		this.deductTotal	= function(siteID)
		{
			if($("#requestButton"+siteID)[0])
			{
				var total	= Number($("#requestButton"+siteID).html())-1;

				// destroy element, if 0 request.
				if(total == 0)
				{
					$("#requestButton"+siteID).remove();
				}

				// else just update.
				$("#requestButton"+siteID).html(total);
			}
		}
	};
};

</script>
<h3 class="m-b-xs text-black">
<a href='info'>Cluster Overview</a>
</h3>
<div class='well well-sm'>
Overview of your cluster. You could be notified here, if there's any request by any site, assigned under your cluster.
</div>
<div class='row'>
	<div class='col-sm-6' id='site-list'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		<?php
		if($res_sites):?>
		<?php
			foreach($stateR as $stateID=>$stateName)
			{
				if(isset($res_sites[$stateID]))
				{
					echo "<div class='row state-list'><div class='col-sm-12'>";
					echo "<div class='state-name'>$stateName</div>";
					echo "<div class='state-site-list'>";
					$no	= 1;
					$total	= 3;
					$opened	= false;
					foreach($res_sites[$stateID] as $row)
					{
						if($no == 1){
							$opened	= true;
							echo "<div class='row'>";
						}

						$siteID		= $row['siteID'];
						$siteName	= ucwords($row['siteName']);

						## check if the site got request.
						$request	= "";
						if(isset($requestR[$siteID]))
						{
							$request	= "<a id='requestButton$siteID' href='javascript:cluster.overview.getSiteRequests($siteID);' class='badge bg-danger' data-toggle='tooltip' data-placement='bottom' title='' data-original-title='".count($requestR[$siteID])." Change request'>".count($requestR[$siteID])."</a>";
						}

						echo "<div class='col-sm-".(12/$total)." site-name'><a onclick='cluster.overview.getDetail($siteID);' href='#'>$siteName</a> $request</div>";

						if($no == $total)
						{
							$opened = false;
							echo "</div>";
							$no = 0;
						}

						$no++;
					}

					if($opened)
					{
						echo "</div>";
					}
					echo "</div>"; ## the close of .site-list
					echo "</div></div>"; ## the close of row and col-sm-12
				}
			}?>

		</div>
		<?php else:?>
		<div class='row'><div class='col-sm-12 no-sites'>
		No sites has ever been assigned under your cluster. <br>Please contact us if you need any help.
		</div></div>
		<?php
		endif;

			?>
		</section>
	</div>
	<div class='col-sm-6' id='site-detail'>
	
	</div>
</div>
<div class='row'>

</div>