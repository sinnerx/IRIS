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

		function setActivityRelation(param)
		{
			$("#activityID").val(param.id);
			$("#activityArticleType").val(param.type);

			if(param.name)
			{

				if(param.disable)
				{
					$("#activity").parent().parent().removeAttr("data-toggle").removeClass("dropdown-toggle").click(function(e)
						{
							return e.preventDefault();
						});
					$("#activity").html('<i class="fa fa-link""></i> '+param.name+' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
				}
				else
				{
					$("#activity").html('<i class="fa fa-link"></i> '+param.name+' &nbsp;&nbsp;&nbsp;<a><i style="cursor: pointer;" onclick="removeActivity();" class="fa fa-times text-danger text"></i></a>&nbsp;&nbsp;&nbsp;');
				}
			}
		}

	<?php if($reportfor['activityID']):?>
	$(document).ready(function()
	{
		setActivityRelation({
			id:"<?php echo $reportfor['activityID'];?>",
			type:"<?php echo 1;?>",
			name:"<?php echo $reportfor['activityName'];?>",
			disable:true
		});
	});
	<?php endif;?>
</script>
<h3 class="m-b-xs text-black">
<a href='info'>Add Article</a>
</h3>
<div class='well well-sm'>
Add an article
</div>
<form method='post'>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-8'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group form-inline">
				<?php echo form::text("articleName","style='width: 74.5%;' size='40' class='form-control input-s' placeholder='Insert title'");?>
				<?php echo form::text("articlePublishedDate","style='margin-top: -5px;' class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime(now(). ' + 5 days')));?>
				<?php echo flash::data('articleName');?>
				<?php echo flash::data("articlePublishedDate");?>
			</div>
			<div class="form-group">	
				<div class="btn-group m-r">
                    <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                        <span class="dropdown-label">
							<div id="activity">
								Activity Link&nbsp;&nbsp;&nbsp;
                        		<span class="caret"></span>
							</div>
						</span> 
                    </button>
                    <ul class="dropdown-menu dropdown-select">
                        <li><a href="<?php echo url::base('ajax/activity/previous'); ?>" data-toggle="ajaxModal">As a report</a></li>
	                    <li><a href="<?php echo url::base('ajax/activity/incoming'); ?>" data-toggle="ajaxModal">As a reference</a></li>
	                </ul>
                </div>
				<input type="hidden" name="activityID" id="activityID" />
				<input type="hidden" name="activityArticleType" id="activityArticleType" />
			</div>
			<div class="form-group">
				<?php echo form::textarea("articleText","size='40' style='height: 300px;' class='mce'");?>
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
				<?php echo form::submit("Save as draft","name='articleStatus' class='btn btn-xs btn-default pull-left'");?>
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
                    <!-- <li><a href="#most" data-toggle="tab">Most used</a></li> -->
	            </ul>
	        </header>
	        <div class="panel-body" style="border-style:solid;border-width:1px;border-color:#eaeef1 !important;">
	            <div class="tab-content">
	                <div class="tab-pane active" style="padding-left: 15px;" id="all"><!--  style="overflow: auto;height:100px;"> -->
	                	<?php
	                		foreach($category as $cat):
	                	?>	
	                	<input type="checkbox" id="parent<?php echo $cat['categoryID']; ?>" name="category[]" value="<?php echo $cat['categoryID']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cat['categoryName']; ?><br/>
	                	<div style="display: none;" id="child<?php echo $cat['categoryID']; ?>">
	                	<?php
	                			if($cat['child']):
	                				foreach($cat['child'] as $c):
	                	?>
	                	<span style="padding-left: 15px;"><input type="checkbox" name="category[]" value="<?php echo $c['categoryID']; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $c['categoryName']; ?></span><br/>
	                	<?php
	                				endforeach;
	                			endif;
	                	?>
	                	</div>
	                	<?php
	                		endforeach;
	                	?>	
	                </div>
                    <!--<div class="tab-pane" id="most">  style="overflow: auto;height:100px;"> 
	                	<input type="checkbox">
	                </div>-->
	            </div>
	        </div>
        </div>
    </section>
	<section class="panel panel-default">
		<header class="panel-heading">Post Tags</header>
		<div class="panel-body">
			<div class="form-group">
				<input name='articleTags' id='articleTags' size='40' class="form-control span4" data-provide="tokenizer" value="">
			</div>
		</div>
	</section>
	</div>
</div>
</form>