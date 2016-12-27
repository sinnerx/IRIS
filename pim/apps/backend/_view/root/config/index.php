<script type="text/javascript">
	
var config = new function()
{
	this.change = function(name)
	{
		pim.ajax.getModal(pim.base_url+"ajax/config/changeForm/"+name);
	}
}

</script>
<style type="text/css">
	
.config-detail
{
	float:right;
}

</style>
<h3 class='m-b-xs text-black'>
Parameters Configuration
</h3>
<div class='well well-sm'>
Providing you way of configuring how should look like. But beware, that certain changes are not undo-able.
</div>
<?php echo flash::data();?>
<?php if(!$row_conf):?>
<div class='well well-sm bg-danger' style="float:none;">
This have no configuration records yet. Everything is set to default. <a href='?create=true'>Click here to create</a>.
</div>
<?php else:?>
<section class='panel panel-default'>
<div class='table-responsive'>
	<table class='table'>
		<tr>
			<td style="width:200px;">News Category ID

			</td>
			<td>: <?php echo $row_conf['configNewsCategoryID']?:"Default (first category in category list)";?> <a href='javascript:config.change("configNewsCategoryID");' class='fa fa-edit'></a>

			<span class='config-detail'>
				This category will be used as news category listing in frontend
			</span>
			</td>
		</tr>
		<?php if(false):?>
		<tr>
			<td>Site menu</td>
			<td>: <?php echo model::load("config")->allSiteMenu($row_conf['configAllSiteMenu']?:1);?> <a href='javascript:config.change("configAllSiteMenu");' class='fa fa-edit'></a>

			<span class='config-detail'>
			Site top menu standards
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<td>Site member's fee (RM)</td>
			<td>: <?php echo $row_conf['configMemberFee'];?> <a href='javascript:config.change("configMemberFee");' class='fa fa-edit'></a>

			<span class='config-detail'>
			Site Member Registration Fee
			</span>
			</td>
		</tr>
		<?php if(false):?>
		<tr>
			<td>Manager Site</td>
			<td>: <?php echo $configManagerSiteName;?> <a href='javascript:config.change("configManagerSiteID");' class='fa fa-edit'></a>

			<span class='config-detail'>
			A manager exclusive p1im site 
			</span>
			</td>
		</tr>
		<?php endif;?>
	</table>
</div>
</section>
<?php endif;?>