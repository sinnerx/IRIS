<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script src="<?php echo url::asset("backend/tools/tinymce/js/tinymce/tinymce.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/tokenizer.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/bootstrap-tokenizer.css"); ?>" type="text/css" />
<script src="<?php echo url::asset("backend/tools/bootstrap-tokenizer/bootstrap-tokenizer.js"); ?>"></script>
<script src="<?php echo url::asset("backend/js/pimgallery.js"); ?>"></script>
<script>
		function removeActivity()
		{
			document.getElementById('activity').innerHTML = 'Activity Link&nbsp;&nbsp;&nbsp;<span class="caret"></span>';
			document.getElementById('activityID').value = '';
			document.getElementById('activityArticleType').value = '';
		}

		function confirmation(){
			var r = confirm("Are you sure?");
    		if (r == true) {
        		return true;
    		} else {
        		return false;
    		}
		}
		
		var selection = null;

        tinymce.init({
        	selector:'textarea.mce',
        	menubar: false,
    		toolbar: ["undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | mybutton"],
        	relative_urls: false,
        	remove_script_host: false,
        	setup : function(ed) {
        		// Add a custom button
		        ed.addButton('mybutton', {
				title : 'Insert/edit Photo',
				image : '<?php echo url::asset("backend/tools/tinymce/js/tinymce/24_upload.png"); ?>',
				onclick : function() {
					// Add you own code to execute something on click
					ed.focus();
					selection = ed.selection;
			        }
		        });
		    }
        });

		function setUrl(res)
		{
			selection.setContent("<p contenteditable='false' style='text-align:center;'><img style='max-width:100%;' src='"+res.photoUrl+"' /></p><br/>");
		}

		$(document).ready(function()
		{
			setTimeout(function()
			{
				$("#mce_14").attr("data-toggle","ajaxModal").attr("onclick","pimgallery.start(this,setUrl);");
			},500);

			<?php 
			foreach($category as $cat): 
				if($cat['child']):
			?>
			$('#parent<?php echo $cat['categoryID']; ?>').click(function(){
				if($(this).is(':checked')){
					$('#child<?php echo $cat['categoryID']; ?>').slideDown();
				}else{
					$('#child<?php echo $cat['categoryID']; ?>').find('input').attr("checked", false);
					$('#child<?php echo $cat['categoryID']; ?>').slideUp();
				}
			});
			<?php 
				endif;
			endforeach; 
			?>

		})
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
	<div class='col-sm-8'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group form-inline">
				<?php echo form::text("articleName","style='width: 75.5%;' size='40' class='form-control input-s' placeholder='Insert title'", $row['articleName']);?>
				<?php echo form::text("articlePublishedDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['articlePublishedDate'])));?>
				<?php echo flash::data('articleName');?>
				<?php echo flash::data("articlePublishedDate");?>
			</div>
			<div class="form-group">	
				<div class="btn-group m-r">
                    <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                        <span class="dropdown-label">
							<div id="activity">
								<?php
									if($activity[0]['activityID']):
								?>
								<i class="fa fa-link"></i> 
								<?php echo $activity[0]['data']["activityName"]; ?> &nbsp;&nbsp;&nbsp;
								<a>
									<i style="cursor: pointer;" onclick="removeActivity();" class="fa fa-times text-danger text"></i>
								</a>
								<?php
									else:
								?>
								Activity Link
								<?php
									endif;
								?>&nbsp;&nbsp;&nbsp;
                        		<span class="caret"></span>
							</div>
						</span> 
                    </button>
                    <ul class="dropdown-menu dropdown-select">
                        <li><a href="<?php echo url::base('ajax/activity/previous'); ?>" data-toggle="ajaxModal">As a report</a></li>
	                    <li><a href="<?php echo url::base('ajax/activity/incoming'); ?>" data-toggle="ajaxModal">As a reference</a></li>
	                </ul>
                </div>
				<input type="hidden" name="activityID" id="activityID" <?php if($activity[0]['activityID']){ echo 'value="'.$activity[0]['activityID'].'"'; } ?> />
				<input type="hidden" name="activityArticleType" id="activityArticleType" <?php if($activity[0]['activityID']){ echo 'value="'.$activity[0]['activityArticleType'].'"'; } ?> />
			</div>
			<div class="form-group">
				<?php echo form::textarea("articleText","size='40' style='height: 300px;' class='mce'", $row['articleText']);?>
				<?php echo flash::data('articleText');?>
			</div>
		</div>
	</section>
	</div>
	<div class='col-sm-4'>
	<section class="panel panel-default">
		<header class="panel-heading">Publish</header>
		<div class="panel-body">
			<div class="form-group">
				<?php if($row['articleStatus'] == 3){echo form::submit("Save as draft","name='articleStatus' class='btn btn-xs btn-default pull-left'");} ?>
				<?php echo form::submit("Publish blog","name='articleStatus' onclick='return confirmation();' class='btn btn-xs btn-primary pull-right'");?>
			</div>
		</div>
	</section>
	<section class="panel panel-default">
		<header class="panel-heading">Categories</header>
		<div class="panel-body">
	        <header class="panel-heading bg-light">
	            <ul class="nav nav-tabs nav-justified">
	                <li class="active"><a href="#all" data-toggle="tab">All categories</a></li>
                    <li><a href="#most" data-toggle="tab">Most used</a></li>
	            </ul>
	        </header>
	        <div class="panel-body" style="border-style:solid;border-width:1px;border-color:#eaeef1 !important;">
	            <div class="tab-content">
	                <div class="tab-pane active" id="all"><!--  style="overflow: auto;height:100px;"> -->
	                	<?php
	                		foreach($category as $cat):
	                	?>	
	                	<input <?php if($cat['checked']){echo 'checked';} ?> type="checkbox" id="parent<?php echo $cat['categoryID']; ?>" name="category[]" value="<?php echo $cat['categoryID']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cat['categoryName']; ?><br/>
	                	<div <?php if(!$cat['checked']){echo 'style="display: none;"';} ?> id="child<?php echo $cat['categoryID']; ?>">
	                	<?php
	                			if($cat['child']):
	                				foreach($cat['child'] as $c):
	                	?>
	                	<span style="padding-left: 15px;"><input <?php if($c['checked']){echo 'checked';} ?> type="checkbox" name="category[]" value="<?php echo $c['categoryID']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $c['categoryName']; ?></span><br/>
	                	<?php
	                				endforeach;
	                			endif;
	                	?>
	                	</div>
	                	<?php
	                		endforeach;
	                	?>	
	                </div>
                    <div class="tab-pane" id="most"><!--  style="overflow: auto;height:100px;"> -->
	                	<input type="checkbox">
	                </div>
	            </div>
	        </div>
        </div>
    </section>
	<section class="panel panel-default">
		<header class="panel-heading">Post Tags</header>
		<div class="panel-body">
			<div class="form-group">
			<?php
				foreach($row['articleTags'] as $tag){
					$tags = $tags.','.$tag['articleTagName'];
				}
			?>
			<?php echo form::text("articleTags","size='40' class='form-control span4' id='articleTags' data-provide='tokenizer'",$tags);?>
			<?php echo flash::data('articleTags');?>
			</div>
		</div>
	</section>
	</div>
</div>
</form>