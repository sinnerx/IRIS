<script src="<?php echo url::asset("_scale/js/wysiwyg/jquery.hotkeys.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/bootstrap-wysiwyg.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/demo.js");?>"></script>


<h3 class="m-b-xs text-black">
<a href='info'>Site Information</a> > Edit
</h3>
<div class='well well-sm'>
Edit your site information, and make it awesome to read!
</div>
<?php echo flash::data();?>
<form method='post' id='siteAddForm'>
<div class='row'>
	<div class='col-sm-3'>
		<section class='panel panel-default'>
		<div class='panel-body' style='height:218px;'>
		<div class='form-group'>
			<label>4. Phone No.</label>
			<?php echo form::text("siteInfoPhone","class='form-control'",$row['siteInfoPhone']);?>
		</div>
		<div class='form-group'>
			<label>5. Fax No.</label>
			<?php echo form::text("siteInfoFax","class='form-control'",$row['siteInfoFax']);?>
		</div>
		</div>
		</section>
	</div>
	<div class='col-sm-9'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>6. Coordinates</label>
			<div class='row'>
				<div class='col-md-3'>
					Latitude : <?php echo form::text("siteInfoLatitude","class='form-control'",$row['siteInfoLatitude']);?>
				</div>
				<div class='col-md-3'>
					Longitude : <?php echo form::text("siteInfoLongitude","class='form-control'",$row['siteInfoLongitude']);?>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<label>7. Address</label>
			<?php echo form::textarea("siteInfoAddress","class='form-control'",$row['siteInfoAddress']);?>
		</div>
		</div>
		</section>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		 <div class="form-group">
		 <label>8. Site Description</label>
	        <div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#editor">
	          <div class="btn-group">
	            <!-- <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a> -->
	              <ul class="dropdown-menu">
	              </ul>
	          </div>
	          <div class="btn-group">
	            <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
	              <ul class="dropdown-menu">
	              <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
	              <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
	              <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
	              </ul>
	          </div>
	          <div class="btn-group">
	            <a class="btn btn-default btn-sm" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
	          </div>
	          <div class="btn-group">
	            <a class="btn btn-default btn-sm" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
	          </div>
	          <div class="btn-group">
	            <a class="btn btn-default btn-sm" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
	          </div>
	          <div class="btn-group">
	            <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
	            <div class="dropdown-menu">
	              <div class="input-group m-l-xs m-r-xs">
	                <input class="form-control input-sm" placeholder="URL" type="text" data-edit="createLink"/>
	                <div class="input-group-btn">
	                  <button class="btn btn-default btn-sm" type="button">Add</button>
	                </div>
	              </div>
	            </div>
	            <a class="btn btn-default btn-sm" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
	          </div>
	          
	          <div class="btn-group hide">
	            <a class="btn btn-default btn-sm" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
	            <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
	          </div>
	          <div class="btn-group">
	            <a class="btn btn-default btn-sm" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
	            <a class="btn btn-default btn-sm" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
	          </div>
	        </div>
	        <div id="editor" class="form-control" style="overflow:scroll;height:150px;max-height:150px">
	          <?php echo flash::data("_post.siteInfoDescription",$row['siteInfoDescription']);?>
	        </div>
	        <input type='hidden' name='siteInfoDescription' id='siteInfoDescription' />
	    </div>
	    </div>
		</section>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12 text-center'>
	<?php echo form::submit("Update site!","class='btn btn-primary'");?>
	</div>
</div>
<script type="text/javascript">
	
$("#siteAddForm").submit(function()
{
	$("#siteInfoDescription").val($("#editor").html());
});

</script>
</form>