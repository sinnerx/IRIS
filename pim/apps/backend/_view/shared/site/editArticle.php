<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script src="<?php echo url::asset("backend/tools/tinymce/js/tinymce/tinymce.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/tokenizer.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/bootstrap-tokenizer.css"); ?>" type="text/css" />
<script src="<?php echo url::asset("backend/tools/bootstrap-tokenizer/bootstrap-tokenizer.js"); ?>"></script>
<script type="text/javascript">
        tinymce.init({selector:'textarea.mce',menubar: false});
</script>
<h3 class="m-b-xs text-black">
<a href='info'>Edit Article</a>
</h3>
<div class='well well-sm'>
Edit article
</div>
<form method='post' enctype="multipart/form-data">
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group" style="margin-left: -30px;">
				<div class="col-sm-7" style="left: 0px;">
					<label class="col-sm-4 control-label">1. Published Date</label>
					<?php echo form::text("articlePublishedDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['articlePublishedDate'])));?>
					<?php echo flash::data("articlePublishedDate");?>
				</div>
				<div class="col-sm-5">
					<label class="col-sm-3 control-label">2. Title</label>
					<?php echo form::text("articleName","size='40' class='form-control input-s'", $row['articleName']);?>
					<?php echo flash::data('articleName');?>
				</div>
			</div>
			<div class="form-group">
			<label>3. Text</label>
			<?php echo form::textarea("articleText","size='40' style='height: 300px;' class='mce'",$row['articleText']);?>
			<?php echo flash::data('articleText');?>
			</div>
			<div class="form-group">
			<label>4. Tag</label>
			<?php
				foreach($row['articleTags'] as $tag){
					$tags = $tags.','.$tag['articleTagName'];
				}
			?>
			<?php echo form::text("articleTags","size='40' class='form-control span4' id='articleTags' data-provide='tokenizer'",$tags);?>
			<?php echo flash::data('articleTags');?>
			</div>
			<div class="form-group">
			<?php echo form::submit("Update blog","class='btn btn-primary pull-right'");?>
			</div>
		</div>
	</section>
	</div>
</div>
</form>