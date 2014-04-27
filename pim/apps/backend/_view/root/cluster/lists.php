<script type="text/javascript">
var base_url = "<?php echo url::base();?>/";

var cluster	= new function()
{
	var addedSite	= [];
	var clusterID	= null;
	var context		= this;

	//show list of site of this cluster.
	this.show	= function(cID)
	{
		addedSite	= []; //reset
		clusterID	= cID;//set global clusterID.

		this.minimizeList();
		p1mloader.start("#site-list"); //loader start
		$.ajax({type:"POST",url:base_url+"ajax/cluster/siteList/"+clusterID}).done(function(txt)
		{
			$("#site-list").html(txt).show();
		});
	};

	this.minimizeList	= function()
	{
		$("#cluster-list").removeClass("col-sm-12").addClass("col-sm-5");
		$("#addForm").hide();
	};

	//used in ajax/cluster/siteList
	this.getAddForm		= function()
	{
		addedSite	= []; //reset
		p1mloader.start("#site-add");  //loader start
		$.ajax({type:"POST",url:base_url+"ajax/cluster/siteAdd/"+clusterID}).done(function(txt)
		{
			$("#site-add").html(txt).show();

			//bind state-site-list with add site.
			$(".state-site-list.not-exists").click(function()
			{
				var siteID	= $(this).attr("data-siteid");
				context.addSite(siteID);
			});
		});
	}

	//in ajax/cluster/siteAdd 
	this.addSite	= function(siteID)
	{
		if($.inArray(siteID,addedSite) < 0)
		{
			// push if not yet added.
			addedSite.push(siteID);
		}

		$("#site-add .save-button").show();
		$(".state-site-list[data-siteid="+siteID+"]").addClass("site-added");
	}

	//save site into cluster.
	this.saveSite	= function()
	{
		//execute.
		$.ajax({type:"POST",data:{siteList:addedSite},url:base_url+"ajax/cluster/siteAddExecute/"+clusterID}).done(function(txt)
		{
			context.show(clusterID);
		});
	}

	this.removeSite	= function(site)
	{
		$.ajax({type:"POST",data:{cluster:clusterID,site:site},url:base_url+"ajax/cluster/removeSite"}).done(function(txt)
		{
			if(txt == true)
			{
				//refresh
				context.show(clusterID);
			}
		});
	}
};

</script>
<h3 class="m-b-xs text-black">
<a href='info'>List of Clusters</a>
</h3>
<div class='well well-sm'>
Listing the groups of Pi1M sites for administration purpose. Every cluster must be assigned to a user with 'Cluster-lead' role.
</div>
<?php echo flash::data();?>
<div class='row'>
<div class='col-sm-12' id='cluster-list'>
<section class="panel panel-default">
<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
	<div class="col-sm-3 pull-right">
	<div class="input-group">
	  <input type="text" class="input-sm form-control" placeholder="Search">
	  <span class="input-group-btn">
	    <button class="btn btn-sm btn-default" type="button">Go!</button>
	  </span>
	</div>
	</div>
	<div class='col-sm-6 pull-left'>
	<div class='row' id='addForm'>
		<div class='col-sm-3'>
		<button type='button' class='class="btn btn-sm btn-bg btn-default pull-left'><a onclick='$("#clusterAddForm").slideToggle();' href='#'>Add Cluster +</a></button>
		</div>
		<div class='col-sm-6' style='display:none;' id='clusterAddForm'>
			<form method="post">
				<div class='input-group' style='position:relative;left:-40px;'>
				<?php echo form::text("clusterName","class='form-control' placeholder='Cluster Name'");?>
				<div class='input-group-btn'>
				<?php echo form::submit("Add!","class='btn btn-default'");?> 
				</div>
				</div>
			</form>
		</div>
	</div>
	</div>
</div>
<div class="table-responsive">
	<table id='table-site-list' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th width='30%'>Cluster Name</th>
			<th class='site-col'>Cluster Lead</th>
			<th width="60px"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if($res_cluster):
		$no	= 1;
		foreach($res_cluster as $row):
			$name	= $row['clusterName'];
			$clusterID = $row['clusterID'];
			$unassignUrl	= url::base("cluster/unassign?clusterID=".$row['clusterID']."&userID=".$row['userID']);
			$userEmail	= (!$row['userEmail']?"<span style='opacity:0.5;'>Null</span>":$row['userEmail']." <a href='$unassignUrl' class='i i-cross2'></a>");
			$assignUrl	= url::base("cluster/assign?clusterID=$clusterID");
			$assignIcon	= !$row['userEmail']?"<a href='$assignUrl' class='fa fa-user'></a>":"";

			echo "<tr><td>$no.</td><td>$name</td><td>$userEmail</td><td>$assignIcon <a href='#' onclick='cluster.show($clusterID);' class='fa fa-list' title='List of monitored site'></a></td></tr>";
			$no++;
		endforeach;
	else:
	echo "<tr><td colspan='5' align='center'>No cluster was added yet.</td></tr>";
	endif;
	?>
	</tbody>
	</table>
</div>
</section>
</div>
<div id='site-list' class='col-sm-7' style='display:none;'>
	
</div>
</div>