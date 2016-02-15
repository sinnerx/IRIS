<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
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

.announcementText
{
	font-weight: bold;
}

.announcementExpiredDate
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
<?php if(session::get("userLevel") == 99):?>
<a href='info'>Announcements</a>
<?php else:?>
<a href='info'>My Announcement Posts</a>
<?php endif;?>
</h3>
<div class='well well-sm'>
<?php if(session::get("userLevel") == 99):?>
List of requested Announcement on all Pi1M sites. Only root admin can manage this section.
<?php else:?>
Listing all your request Announcement here.
<?php endif;?>
</div>
	<?php echo flash::data();?>
<section class="panel panel-default">
<div class="row wrapper">
	<div class="col-sm-3 pull-right">
	</div>
	<div class='col-sm-3 pull-left'>
	<button type='button' class='class="btn btn-sm btn-bg btn-default pull-left' onclick='add();'><a href='javascript:void(0);'>Add +</a></button>
	</div>
</div>
<div class='row wrapper' id='form_add'>
	<form method='post'>
	<div class='col-sm-5'>
		<div class='form-group'>
			<label>1. Announcement Text</label>
			<?php echo form::textarea("announcementText","class='form-control' placeholder='Type an announcement'");?>
			<?php echo flash::data("announcementText");?>
		</div>
	</div>
	<div class='col-sm-3'>
		<div class='form-group'>
			<label>2. Expired Date</label>
			<?php echo form::text("announcementExpiredDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy' value='".date('d-m-Y', strtotime(date('d-m-Y'). ' + 5 days'))."'");?>
			<?php echo flash::data("announcementExpiredDate");?>
		</div>
	</div>
	<div class='col-sm-3'>
		<div class='form-group'>
			<label>3. Links</label>
			<?php echo form::text("announcementLink","class='form-control' placeholder='Place a link here'");?>
			<?php echo flash::data("announcementLink");?>
		</div>
	</div>
	<div class='col-sm-1'>
	<input type='submit' value='Add' class='btn btn-primary pull-left' style='position:relative;top:25px;' />
	</div>
	</form>
</div>
<div class="table-responsive">
	<table id='table-slider' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th class="th-sortable" data-toggle="class">Announcement
			</th>
			<th>Date Added</th>
			<th>Date Expired</th>
			<th width="29"></th>
			<th width="29"></th>
		</tr>
	</thead>
	<tbody>
		<?php if($announcement):
		$requestdata = model::load('site/request')->replaceWithRequestData('announcement.update', array_keys($announcement));
		$no	= pagination::recordNo();
		foreach($announcement as $row):
		$row = isset($requestdata[$row['announcementID']])?array_merge($row,$requestdata[$row['announcementID']]):$row;
		$active		= $row['announcementStatus'] == 1?"active":"";
		$opacity	= $row['announcementStatus'] == 0 || isset($requestdata[$row['announcementID']])?"style='opacity:0.5;'":"";
		$href		= ($row['announcementStatus'] == 1?"deactivate":"activate")."?".$row['announcementID'];
		$href		= "?toggle=".$row['announcementID'];
			?>
		<tr <?php echo $opacity;?>>
			<td><?php echo $no++;?>.</td>
			<td width='40%'>
			<div class='announcementText'>
				<?php echo $row['announcementText'];?>
			</div>
			</td>
			<td><?php echo date("d-m-Y g:i A",strtotime($row['announcementCreatedDate']));?></td>
			<td><?php echo date("d-m-Y",strtotime($row['announcementExpiredDate']));?></td>
			<?php if($row['siteID'] != 0 || session::get("userLevel") == 99):?>
            <td>
			
				
                
				<?php if(session::get("userLevel") == 99):?>
					<a href="<?php echo $href;?>" class="<?php echo $active;?>" ><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
				<?php else: ?>
					<a><?php echo model::load('template/icon')->status($row['announcementStatus']); ?></a>
				<?php endif; ?>
			
			</td>
			<td><?php if($row['announcementStatus'] != 2):?>
				<a href='<?php echo url::base("site/editAnnouncement/".$row['announcementID']);?>' class='fa fa-edit'></a>
				<?php endif; ?></td>
            <?php endif;?>
            
		</tr>
		<?php 
		endforeach;
		else:?>
			<tr>
				<td align="center" colspan='4'>No announcement was added yet.</td>
			</tr>
		<?php endif;?>
	</tbody>
	</table>
</div>
<footer class='panel-footer'>
	<div class="row">
		<div class="col-sm-12">
			<?php echo pagination::link();?>
		</div>
	</div>
</footer>
</section>