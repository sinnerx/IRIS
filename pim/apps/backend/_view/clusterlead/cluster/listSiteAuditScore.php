<script type="text/javascript">
var base_url	= "<?php echo url::base();?>/";
var site	= new function()
{
	this.index	= new function()
	{
		this.showDetail	= function(siteID)
		{
			$("#table-site-list tr").removeClass("activ");
			$("#row"+siteID).addClass("activ");
			this.minimizeList();

			var speed	= $("#site-detail").css("display") == "block"?1:500;
			setTimeout(function(){
			$.ajax({type:"POST",url:base_url+"ajax/shared/site/getDetail/"+siteID}).done(function(txt)
			{
				$("#site-detail").html(txt).show();
			});
			},speed);
		}

		this.minimizeList	= function()
		{
			$("#site-list").removeClass("col-sm-12").addClass("col-sm-5");
			$("#table-site-list th")[1].colSpan			= "4";
			$(".site-col").hide();
		}

		//return query object.
		this.getQueryString	= function()
		{
			var q	= window.location.search.substring(1).split("&");
			var query	= {};
			for(i in q)
			{
				if(q[i] != "")
				{
					query[q[i].split("=")[0]]	= q[i].split("=")[1];
					//alert(q[i].split("=")[0]);
				}
			}

			return query;
		}

	}
}

</script>
<style type="text/css">
	
#site-list
{
	-webkit-transition:all 0.5s;
}

#table-site-list tr.activ
{
	font-weight: bold;
}

.manager-name .i
{
	display: none;
}

.manager-name:hover .i
{
	display: inline-block;
}

</style>
<h3 class="m-b-xs text-black">
<a href='info'>All Sites (Audit Score)</a>
</h3>
<div class='well well-sm'>
List of all Pi1M sites
</div>
<?php echo flash::data();?>
<div class='row'>
<div class='col-sm-12' id='site-list'>
<section class="panel panel-default">
<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
	<div class="col-sm-3 pull-right">
		<form method='get' id='formSearch'><!-- search form -->
		<div class="input-group">
		  <input type="text" class="input-sm form-control" name='search' value='<?php echo request::get("search");?>' placeholder="Search">
		  <span class="input-group-btn">
		    <button class="btn btn-sm btn-default" type="button" onclick='$("#formSearch").submit();'>Go!</button>
		  </span>
		</div>
		</form>
	</div>
</div>
<div class="table-responsive">
	<table id='table-site-list' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th>Site</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($res_site)
		{
			$no	= pagination::recordNo();
			foreach($res_site as $row):
			$slug	= $row['siteSlug'];
			$siteID	= $row['siteID'];

			## if got site manager, prepare lel.
			$sitemanager	= Array();
			if(isset($sitemanagerR[$siteID]))
			{
				foreach($sitemanagerR[$siteID] as $row_manager)
				{
					$userID				= $row_manager['userID'];
					$userDetailHref		= url::base("user/edit/".$row_manager['userID']);
					$name				= $row_manager['userProfileFullName'];
					$phoneNo			= $row_manager['userProfilePhoneNo']?"[".$row_manager['userProfilePhoneNo']."]":"";
					$sitemanager[]	= "<span class='manager-name'><a href='$userDetailHref' data-original-title='$name $phoneNo' data-toggle='tooltip' data-placement='bottom'>".$row_manager['userEmail']."</a><a href='#' onclick='site.index.removeManager($siteID,$userID);' class='i i-cross2'></a></span>";
				}
			}

			$assignIcon	= count($sitemanager) < 2?"<a href='".url::base("site/assignManager/$siteID")."' class='fa fa-user pull-right'></a>":"";

				?>
			<tr id='row<?php echo $row['siteID'];?>'>
				<td><?php echo $no;?>.</td>
				<td><?php echo $row['siteName'];?></td>
				<td width='90px'>
					<?php echo $assignIcon;?>
					<a href='<?php echo url::base("cluster/editAuditScore/".$row['siteID']."");?>' class='fa fa-edit'></a>
					<!--<a onclick='return confirm("Confirm delete?");' class='fa fa-times' href='delete/<?php echo $row['siteID']; ?>'></a>-->
				</td>
			</tr>
			<?php
			$no++;
			endforeach;
		}
		else
		{
			echo "<tr><td colspan='5' align='center'>No site was found at all.</td></tr>";
		}
		?>
	</tbody>
	</table>
</div>
<footer class='panel-footer'>
<div class='row'>
	<div class='col-sm-12'>
		<?php echo pagination::link();?>
	</div>
</div>
</footer>
</section>
</div>
<div id='site-detail' style='display:none;' class='col-sm-7'>

</div>
</div>