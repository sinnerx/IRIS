<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<a href='info'>Edit Announcement</a>
</h3>
<div class='well well-sm'>
Edit selected announcement
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12'>
	<section class="panel panel-default">
		<div class="panel-body">
			<form class="form-horizontal" method='post' enctype="multipart/form-data">
			<div class="form-group">
			<label class="col-sm-3">1. Announcement Text</label>
			<div class="col-sm-9"><?php echo form::textarea("announcementText","size='40' style='height: 100px;' class='form-control'",$row['announcementText']);?><?php echo flash::data('announcementText');?></div>
			</div>
			<div class="form-group">
			<label class="col-sm-3">2. Expire Date</label>
			<div class="col-sm-9"><?php echo form::text("announcementExpiredDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['announcementExpiredDate'])));?><?php echo flash::data("announcementExpiredDate");?></div>
			</div>
			<div class="form-group">
			<label class="col-sm-3">3. Redirection Link</label>
			<div class="col-sm-9"><?php echo form::text("announcementLink","size='40' class='form-control'",$row['announcementLink']);?></div>
			</div>
			<div class="form-group">
			<label class="col-sm-3">4. Status</label>
			<div class="col-sm-9"><?php
			echo $row['announcementStatus'] == 1?"<font color='green'>Active</font>":"<font color='red'>In-Active</font>";
			?></div>
			</div>
			

			<div class='row'>
	<div class='col-sm-12' style='text-align:center;'>
<div class="form-group">
			<?php echo form::submit("Update & Approve","class='btn btn-primary'");?>
			<input type='button' value='Cancel' onclick='window.location.href = "<?php echo url::base("cluster/overview");?>"' class='btn btn-default' />
			</div>
	</div>
	</div>
			</form>
		</div>
	</section>
	</div>
</div>