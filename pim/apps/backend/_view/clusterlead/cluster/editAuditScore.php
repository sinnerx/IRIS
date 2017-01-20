<h3 class="m-b-xs text-black">
<a href='info'>Edit Audit Score</a>
</h3>
<div class='well well-sm'>
Edit Audit Score
</div>
<form method='post' enctype="multipart/form-data">
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-8'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group form-inline">
				Audit Score : </p>
				<?php echo form::text("auditScoreText","style='width: 74.5%;' size='40' class='form-control input-s' placeholder='Insert Audit Score'", $clusterAuditScore);?>
			</div>
			<?php echo form::submit("Update","name='updatebtn' onclick='return confirmation();' class='btn btn-primary pull-left'");?>
		</div>
	</section>
	</div>
</div>
</form>