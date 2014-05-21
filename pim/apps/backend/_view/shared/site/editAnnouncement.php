<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<a href='info'>Edit Announcement</a>
</h3>
<div class='well well-sm'>
Edit selected announcement
</div>
<form method='post' enctype="multipart/form-data">
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-3'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
			<label>1. Announcement Text</label>
			<?php echo form::text("announcementText","size='40' class='form-control'",$row['announcementText']);?>
			<?php echo flash::data('announcementText');?>
			</div>
			<div class="form-group">
			<label>2. Expire Date</label>
			<?php echo form::text("announcementExpiredDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['announcementExpiredDate'])));?>
			<?php echo flash::data("announcementExpiredDate");?>
			</div>
			<div class="form-group">
			<label>2. Status</label><br>
			<?php
			echo $row['announcementStatus'] == 1?"Active":"In-Active";
			?>
			</div>
			<div class="form-group">
			<?php echo form::submit("Update announcement","class='btn btn-primary pull-right'");?>
			</div>
		</div>
	</section>
	</div>
</div>
</form>