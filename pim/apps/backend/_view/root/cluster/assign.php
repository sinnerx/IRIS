<script type="text/javascript">
	
$(document).ready(function()
{
	$(".cluster-override").click(function()
	{
		$("#theForm")[0].action	+= "&override";
		$("#theForm").submit();
	});
});

</script>
<h3 class="m-b-xs text-black">
<a href='info'>Assign <?php echo $userName?"<u>$userName</u>":"someone";?> to <?php echo $clusterName?"<u>$clusterName</u>":"a cluster";?>.</a>
</h3>
<div class='well well-sm'>
By assigning a person to a cluster, he will be able to have a control as an editor over all the sites within the cluster.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-4'>
		<section class='panel panel-default'>
			<div class='panel-body'>
				<form method='post' id='theForm' action='<?php echo url::base("cluster/assign",true);?>'>
					<div class='form-group'>
						<label>Cluster</label>
						<?php $disabled	= $clusterID?"disabled":"";?>
						<?php echo form::select("clusterID",$clusterR,"class='form-control' $disabled",$clusterID);?>
						<?php echo flash::data("clusterID");?>
					</div>
					<div class='form-group'>
						<label>Available User <a href='<?php echo url::base("user/add?level=3");?>' class='fa fa-plus-circle' target="_blank"></a></label>
						<?php $disabled	= $userID?"disabled":"";?>
						<?php echo form::select("userID",$userR,"$disabled class='form-control'",$userID);?>
						<?php echo flash::data("userID");?>
					</div>
					<?php echo form::submit("Assign","class='btn btn-primary'");?>
				</form>
			</div>
		</section>
	</div>
</div>