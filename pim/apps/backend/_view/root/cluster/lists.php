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

	this.checkDelete = function()
	{
		return confirm("Are you sure?");
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
	<!-- <div class="input-group">
	  <input type="text" class="input-sm form-control" placeholder="Search">
	  <span class="input-group-btn">
	    <button class="btn btn-sm btn-default" type="button">Go!</button>
	  </span>
	</div> -->
	</div>
	<div class='col-sm-6 pull-left'>
	<div class='row' id='addForm'>
		<div class='col-sm-3'><a class='btn btn-primary' onclick='$("#clusterAddForm").slideToggle();' href='#'>Add Cluster +</a>
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
			<th>Ops Manager</th>
			<th width="80px"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if($res_cluster):
		$no	= 1;
		foreach($res_cluster as $cluster):
			$name	= $cluster->clusterName;
			$clusterID = $cluster->clusterID;
			
			
			$clusterLeadR	= $clusterLeadByClusterR[$clusterID];

			$userEmail	= Array();
			if(count($clusterLeadR) > 0)
			{
				foreach($clusterLeadR as $row)
				{
					$userID			= $row['userID'];
					$unassignUrl	= url::base("cluster/unassign?clusterID=".$row['clusterID']."&userID=".$row['userID']);
					$userEmail[]	= "<a href='".url::base("user/edit/$row[userID]")."'>$row[userEmail] <a onclick='return cluster.checkDelete();' href='$unassignUrl' class='i i-cross2'></a></a>";
				}
			}
			$userEmail	= count($userEmail) == 0?"Null":implode(" ",$userEmail);

			$assignUrl	= url::base("cluster/assign?clusterID=$clusterID");
			$assignIcon	= "<a href='$assignUrl' class='fa fa-plus'></a>";
			$deleteUrl = url::base('cluster/delete/'.$clusterID);
			?>

			<tr>
				<td><?php echo $no;?>.</td>
				<td><?php echo $name;?></td>
				<td><?php echo $userEmail;?> <?php echo $assignIcon;?></td>
				<td>
					<?php if($cluster->hasOpsmanager()):?>
						<?php echo $cluster->getOpsmanager()->userEmail;?> <a href='<?php echo url::base('cluster/deassignOpsmanager/'.$cluster->clusterID);?>' class='i i-cross2'></a>
					<?php else:?>
						<a href='<?php echo url::base('cluster/assignOpsmanager/'.$cluster->clusterID);?>' class='fa fa-plus'></a>
					<?php endif;?>
				</td>
				<td></td>
				<td><a href='#' onclick='cluster.show(<?php echo $clusterID;?>);' class='fa fa-list' title='List of monitored site'></a> <a href='<?php echo $deleteUrl;?>' onclick='return confirm(\"Delete this cluster. Are you sure?\");' class='i i-cross2'></a>
				</td>
			</tr>
			<?php
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