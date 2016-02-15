<style type="text/css">
	
.changeform-table td
{
	padding:5px;
}

</style>
<script type="text/javascript">

function formCheck()
{
	var col	= $("#column").val();

	if(col == "configManagerSiteID" && $("#initialValue").val() != 0)
	{
		if(!confirm("Are you sure on editing p1m manager site. Because this will affect things in your system."))
		{
			return false;
		}
	}

	return true;
}

</script>
<form method='post' action='<?php echo url::base("config/update");?>' onsubmit='return formCheck();'>
<input type='hidden' value='<?php echo $configName;?>' name='column' id='column' />
<input type="hidden" value='<?php echo $value;?>' id='initialValue'>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span><span class='fa fa-gear'></span> Change Config Parameter</span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div>
				<table class='changeform-table'>
				<?php
				switch($configName)
				{
					case "configNewsCategoryID":?>
					<tr>
						<td>News Category</td>
						<td>: 
							<?php echo form::select("value",$catR,null,$value);?>
						</td>
					</tr>
					<?php
					break;
					case "configAllSiteMenu":?>
					<tr>
						<td>All Site Menu</td>
						<td>: <?php
						echo form::select("value",model::load("config")->allSiteMenu(),null,$value);
						?></td>
					</tr>
					<?php
					break;
					case "configMemberFee":
					?>
					<tr>
						<td>Membership Fee (RM)</td>
						<td>: <?php echo form::text("value","size='3'",$value);?></td>
					</tr>
					<?php
					break;
					case "configManagerSiteID":
					?>
					<tr>
						<td>Pim Manager Site</td>
						<td>: <?php
						echo form::select("value",$siteR,null,$value);
						?>

						or <a target="_blank" href='<?php echo url::base("site/add?flag=add-manager-site");?>' class='label label-primary'>create if non-exists.</a>
						</td>
					</tr>
					<?php
					break;
				}
				?>
				</table>
			</div>
		</div>
		<div class='modal-footer'>
			<input type='submit' class='btn btn-primary' />
		</div>
	</div>
</div>
</form>