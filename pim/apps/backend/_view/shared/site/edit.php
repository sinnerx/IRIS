<script src="<?php echo url::asset("_scale/js/wysiwyg/jquery.hotkeys.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/bootstrap-wysiwyg.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/demo.js");?>"></script>
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">

<script type="text/javascript">
	
function prepareSlug(val)
{
	var slug	= val?val:$("#siteName").val();
	$("#siteSlug").val(slug.split(" ").join("-").toLowerCase());
}

</script>


<h3 class="m-b-xs text-black">
Edit Site Information
</h3>
<div class='well well-sm'>
Edit your site information, and make it awesome to read!
</div>
<?php
## if got unapproved flag, show the message.
if($unapproved && !flash::data()):?>
<div class='alert alert-danger'>
Content is waiting to be approved.
</div>
<?php
endif;
?>
<?php echo flash::data();?>
<form method='post' id='siteAddForm' enctype="multipart/form-data">
<div class='row'>
	<div class='col-sm-6'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
			<label>1. Pi1m Name</label>
			<div class='row'>
				<div class='col-sm-9'>
				<?php echo form::text("siteName","size='40' $disabled onkeyup='prepareSlug();' class='form-control'",$row['siteName']);?>
				<?php echo flash::data("siteName");?>
				</div>
				<div class='col-sm-3'>
				<?php echo form::select("stateID",$stateR,"$disabled class='form-control'",$row['stateID'],"[Select State]");?>
				<?php echo flash::data("stateID");?>
				</div>
			</div>
			</div>
			
		</div>
	</section>
	</div>
	<div class='col-sm-6'>
	<section class='panel panel-default'>
		<div class="panel-body">
			<div class="form-group">
			<label>2. Pi1m Slug</label>
			<div class='row'>
				<div class='col-sm-9'>
				<?php echo form::text("siteSlug","class='form-control' onkeyup='prepareSlug(this.value);' $disabled placeholder=\"A url represents the p1m, make sure it's as clear as possible.\"",$row['siteSlug']);?>
				<?php echo flash::data("siteSlug");?>
				</div>
				<div class='col-sm-3'>
				<?php echo form::text("siteRefID","placeholder='Old Pi1M Site ID' $disabled class='form-control'",$row['siteRefID']);?>
				<?php echo flash::data("siteRefID");?>
				</div>
			</div>
			</div>
		</div>
	</section>
	</div>
</div>

<div class='row'>
<div class='col-sm-12'>
		<section class='panel panel-default'>
		<div class='panel-body'>
	

		<div class='row'>
		<div class='col-sm-4'>
			<div class='form-group'>
				<label>LOA Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoLoaDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoLoaDate'])));?>
					<?php echo flash::data("siteInfoLoaDate");?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Commencement Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoCommencementDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoCommencementDate'])));?>
			<?php echo flash::data("siteInfoCommencementDate");?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Actual Start Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoActualStartDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoActualStartDate'])));?>
			<?php echo flash::data("siteInfoActualStartDate");?>
			</div>
				</div></div>
			</div>
		</div>



		<div class='row'>
		<div class='col-sm-4'>
			<div class='form-group'>
				<label>SAT Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoSatDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoSatDate'])));?>
					<?php echo flash::data("siteInfoSatDate");?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Operation Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoOperationDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoOperationDate'])));?>
			<?php echo flash::data("siteInfoOperationDate");?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Completion Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoCompletionDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoCompletionDate'])));?>
			<?php echo flash::data("siteInfoCompletionDate");?>
			</div>
				</div></div>
			</div>
		</div>
		</div>
		</section>
		</div>
</div>

<div class='row'>
<div class='col-sm-12'>
		<section class='panel panel-default'>
		<div class='panel-body'>
	

		<div class='row'>
					<div class='col-sm-4'>
			<div class='form-group'>
				<label>Parliament</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoParliament","class='form-control'",$row['siteInfoParliament']);?>
						<?php echo flash::data("siteInfoParliament");?>
			
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Phase</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoPhase","class='form-control'",$row['siteInfoPhase']);?>
						<?php echo flash::data("siteInfoPhase");?>
			
			</div>
				</div></div>
			</div>
		</div>



		<div class='row'>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>District</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoDistrict","class='form-control'",$row['siteInfoDistrict']);?>
						<?php echo flash::data("siteInfoDistrict");?>
			
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Population</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoPopulation","class='form-control'",$row['siteInfoPopulation']);?>
						<?php echo flash::data("siteInfoPopulation");?>
			</div>
				</div></div>
			</div>
		</div>
		</div>
		</section>
		</div>
</div>
<div class='row'>
	<div class='col-sm-3'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<label>3. Phone No.</label>
			<?php echo form::text("siteInfoPhone","class='form-control'",$row['siteInfoPhone']);?>
		</div>
		<div class='form-group'>
			<label>4. Fax No.</label>
			<?php echo form::text("siteInfoFax","class='form-control'",$row['siteInfoFax']);?>
		</div>
		<div class='form-group'>
			<label>5. Site Email.</label>
			<?php echo form::text("siteInfoEmail","class='form-control'",$row['siteInfoEmail']);?>
			<?php echo flash::data("siteInfoEmail");?>
		</div>
		</div>
		</section>
	</div>
	<div class='col-sm-9'>
		<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
			<div class='row'>
				<div class='col-md-6'>
				<label>6. Links</label>
				<div class='row'>
					<div class='col-md-6'>
						Twitter : <?php echo form::text("siteInfoTwitterUrl","class='form-control'",$row['siteInfoTwitterUrl']);?>
					</div>
					<div class='col-md-6'>
						Facebook : <?php echo form::text("siteInfoFacebookUrl","class='form-control'",$row['siteInfoFacebookUrl']);?>
					</div>
				</div>
				</div>

				<div class='col-md-6'>
				<label>7. Coordinates</label>
				<div class='row'>
					<div class='col-md-6'>
						Latitude : <?php echo form::text("siteInfoLatitude","class='form-control'",$row['siteInfoLatitude']);?>
					</div>
					<div class='col-md-6'>
						Longitude : <?php echo form::text("siteInfoLongitude","class='form-control'",$row['siteInfoLongitude']);?>
					</div>
				</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-6'>
			<div class='form-group'>
				<label>8. Address</label>
				<?php echo form::textarea("siteInfoAddress","style='height:90px;' class='form-control'",$row['siteInfoAddress']);?>
			</div>
			</div>
			<div class='col-sm-6'>
			<div class='form-group'>
				<label>9. Site Image.</label>
				<?php if($row['siteInfoImage']):?>
					<img width='100%' src='<?php echo url::asset("frontend/images/siteImage/".$row['siteInfoImage']);?>' />
				<?php endif;?>
				<?php echo form::file("siteInfoImage","style='position:relative;top:5px;'");?>
			</div>
			</div>
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
		 <label>14. Site Description</label>
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