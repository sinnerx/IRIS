<script type="text/javascript">
var base_url	= "<?php echo url::base();?>/";
var site	= function()
{
	this.showDetail	= function(siteID)
	{
		$("#table-site-list tr").removeClass("activ");
		$("#row"+siteID).addClass("activ");
		this.minimizeList();

		var speed	= $("#site-detail").css("display") == "block"?1:500;
		setTimeout(function(){
		$.ajax({type:"POST",url:base_url+"ajax/site/getDetail/"+siteID}).done(function(txt)
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
}

var site	= new site();

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

</style>
<h3 class="m-b-xs text-black">
<a href='info'>Preview</a>
</h3>
<div class='well well-sm'>
Preview list of site along with their information.
</div>
<div class='row'>
<div class='col-sm-12' id='site-list'>
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
	<div class='col-sm-5'>
		<?php echo form::select("state",Array(9=>"Selangor"),"class='input-sm form-control input-s-sm inline v-middle'","","[STATE]");?>
	</div>
</div>
<div class="table-responsive">
	<table id='table-site-list' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th>Site
			</th>
			<th class='site-col'>Url</th>
			<th class='site-col' colspan='2'>Manager</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($res_site)
		{
			$no	= pagination::recordNo();
			foreach($res_site as $row):?>
			<tr id='row<?php echo $row['siteID'];?>'>
				<td><?php echo $no;?>.</td>
				<td><?php echo $row['siteName'];?></td>
				<td class='site-col'>http://p1m.gaia.my/<?php echo strtolower($row['siteSlug']);?></td>
				<td class='site-col'><?php echo ucfirst($row['userProfileFullName']);?></td>
				<td width='24px;'><a href='javascript:void(0);' onclick="site.showDetail(<?php echo $row['siteID'];?>)" class='fa fa-search'></a></td>
			</tr>
			<?php
			$no++;
			endforeach;
		}
		else
		{

		}
		?>
	</tbody>
	</table>
</div>
<footer class='panel-footer'>
<div class='row'>
	<div class='col-sm-12'>
		Total Record : <?php echo pagination::get("totalRow");?><?php echo pagination::link();?>
	</div>
</div>
</footer>
</section>
</div>
<div id='site-detail' style='display:none;' class='col-sm-7'>

</div>
</div>