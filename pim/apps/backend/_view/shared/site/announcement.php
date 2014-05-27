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
<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
	<div class="col-sm-3 pull-right">
	<!-- <div class="input-group">
	  <input type="text" class="input-sm form-control" placeholder="Search">
	  <span class="input-group-btn">
	    <button class="btn btn-sm btn-default" type="button">Go!</button>
	  </span>
	</div> -->
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
			<th width="60"></th>
		</tr>
	</thead>
	<tbody>
		<?php if($announcement):
		$no	= 0;
		foreach($announcement as $row):
		$no++;

		$active		= $row['announcementStatus'] == 1?"active":"";
		$opacity	= $row['announcementStatus'] == 0?"style='opacity:0.5;'":"";
		$href		= ($row['announcementStatus'] == 1?"deactivate":"activate")."?".$row['announcementID'];
		$href		= "?toggle=".$row['announcementID'];
			?>
		<tr <?php echo $opacity;?>>
			<td><?php echo $no;?>.</td>
			<td width='40%'>
			<?php if($row['siteID'] == 0 && session::get("userLevel") != 99):?>
			<span class='general-label'>General announcement</span>
			<?php endif;?>
			<div class='announcementText'><?php echo $row['announcementText'];?></div>
			</td>
			<td><?php echo date("d-m-Y g:i A",strtotime($row['announcementCreatedDate']));?></td>
			<td><?php echo date("d-m-Y",strtotime($row['announcementExpiredDate']));?></td>
			<td>
			<?php if($row['siteID'] != 0 || session::get("userLevel") == 99):?>
				<a href='<?php echo url::base("site/editAnnouncement/".$row['announcementID']);?>' class='fa fa-edit'></a>
				<?php if(session::get("userLevel") == 99):?>
					<a href="<?php echo $href;?>" class="<?php echo $active;?>" ><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
				<?php endif; ?>
			<?php endif;?>
			</td>
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
		<?php 
		if($paginate){
		?>
        <div class="text-center">
            <ul class="pagination pagination">
		<?php
			$paginate =  substr_replace($paginate,'',0,5);$paginate = substr_replace($paginate,'',-6);

			## echo the pagination link
			while($paginate!=''){
		?>
                <li>
                <?php
                	if(strpos(substr($paginate,0,strpos($paginate,"</a>")+4), 'Previous') !== false){
                		echo substr_replace(substr($paginate,0,strpos($paginate,"</a>")+4),'<i class="fa fa-chevron-left"></i>', strpos(substr($paginate,0,strpos($paginate,"</a>")+4), 'Previous'), 8);
                	}else if(strpos(substr($paginate,0,strpos($paginate,"</a>")+4), 'Next') !== false){
                		echo substr_replace(substr($paginate,0,strpos($paginate,"</a>")+4),'<i class="fa fa-chevron-right"></i>', strpos(substr($paginate,0,strpos($paginate,"</a>")+4), 'Next'), 8);
                	}else{
                		echo substr($paginate,0,strpos($paginate,"</a>")+4);
                	}
                	$paginate = substr($paginate,strpos(substr($paginate,0,strpos($paginate,"</a>")+4),'</a>')+4);
                ?>
                </li>
		<?php
			}
		?>
          	</ul>
        </div>
       	<?php
        }
        ?>
</div>
</section>