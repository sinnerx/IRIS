<script type="text/javascript">
	
function add()
{
	$("#form_add").slideDown();
	window.location.href	= "#add";
}

$(document).ready(function()
{
	var param	= window.location.href.split("#")[1];

	if(param == "add")
	{
		add();
	}
});

function showPicture(obj)
{
	$("#table-slider img").slideUp();

	var img	= $(obj).parent().parent().find("img");

	if(img.css("display") == "none")
	{
		img.slideDown();	
	}
	
}

</script>
<style type="text/css">
	
#form_add
{
	display: none;
}

#table-slider td
{
	position: relative;
}

#table-slider img
{
	border:1px solid #d6ddeb;
}

.sliderName
{
	font-weight: bold;
}

.general-label
{
	position:absolute;
	right:0px;
	opacity: 0.5;
}

</style>
<h3 class="m-b-xs text-black">
<a href='info'>Page Slider</a>
</h3>
<div class='well well-sm'>
<?php if(session::get("userLevel") == 2):?>
Listing all your site slider here.
<?php else:?>
Listing all the general slider, viewable by all site. Only add-able by root admin.
<?php endif;?>
</div>
	<?php echo flash::data();?>
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
	<div class='col-sm-3 pull-left'>
	<button type='button' class='class="btn btn-sm btn-bg btn-default pull-left' onclick='add();'><a href='javascript:void(0);'>Add +</a></button>
	</div>
</div>
<div class='row wrapper' id='form_add'>
	<form method='post' enctype="multipart/form-data">
	<div class='col-sm-3'>
		<div class='form-group'>
			<label>1. Slider Name</label>
			<?php echo form::text("siteSliderName","class='form-control' placeholder='Name of slider'");?>
			<?php echo flash::data("siteSliderName");?>
		</div>
	</div>
	<div class='col-sm-3'>
		<div class='form-group'>
			<label>2. Choose Image</label>
			<?php echo form::file("siteSliderImage","class='form-control'");?>
			<?php echo flash::data("siteSliderImage");?>
		</div>
	</div>
	<div class='col-sm-3'>
		<div class='form-group'>
			<label>3. Redirection link</label>
			<?php echo form::text("siteSliderLink","class='form-control' placeholder='Link to be redirection after user clicks it.'");?>
			<?php echo flash::data("siteSliderLink");?>
		</div>
	</div>
	<div class='col-sm-3'>
	<input type='submit' value='Add' class='btn btn-primary pull-left' style='position:relative;top:25px;' />
	</div>
	</form>
</div>
<div class="table-responsive">
	<table id='table-slider' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th class="th-sortable" data-toggle="class">Slider
			</th>
			<th>Date Added</th>
			<th width="60"></th>
		</tr>
	</thead>
	<tbody>
		<?php if($res_slider):
		$no	= 0;
		foreach($res_slider as $row):
		$no++;
		$imageUrl	= url::asset("frontend/images/slider/".$row['siteSliderImage']);

		$active		= $row['siteSliderStatus'] == 1?"active":"";
		$opacity	= $row['siteSliderStatus'] == 0?"style='opacity:0.5;'":"";
		$href		= ($row['siteSliderStatus'] == 1?"deactivate":"activate")."?".$row['siteSliderID'];
		$href		= "?toggle=".$row['siteSliderID'];
			?>
		<tr <?php echo $opacity;?>>
			<td><?php echo $no;?>.</td>
			<td width='80%'>
			<?php if($row['siteSliderType'] == 2 && session::get("userLevel") != 99):?>
			<span class='general-label'>General slider</span>
			<?php endif;?>
			<div class='sliderName'><?php echo $row['siteSliderName'];?> <a href='javascript:void(0);' class='fa fa-picture-o' onclick='showPicture(this);'></a></div>
			<img src="<?php echo $imageUrl;?>" height='200px' style='display:none;'>
			<br>Redirection link : <?php echo $row['siteSliderLink'];?>
			</td>
			<td><?php echo date("d-m-Y g:i A",strtotime($row['siteSliderCreatedDate']));?></td>
			<td>
				<?php if($row['siteSliderType'] == 1 || session::get("userLevel") == 99):?>
				<a href='<?php echo url::base("site/slider_edit/".$row['siteSliderID']);?>' class='fa fa-edit'></a>
				<a href="<?php echo $href;?>" class="<?php echo $active;?>" ><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
			<?php endif;?>
			</td>
		</tr>
		<?php 
		endforeach;
		else:?>
			<tr>
				<td align="center" colspan='4'>No slider was added yet.</td>
			</tr>
		<?php endif;?>
	</tbody>
	</table>
</div>
</section>