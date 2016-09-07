<script type="text/javascript">

function prepareSlug()
{
	var slug	= $("#siteName").val();
	$("#siteSlug").val(slug.split(" ").join("-").toLowerCase());
}

</script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/jquery.hotkeys.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/bootstrap-wysiwyg.js");?>"></script>
<script src="<?php echo url::asset("_scale/js/wysiwyg/demo.js");?>"></script>
<style type="text/css">
.input-error
{
	color:red;
}
.main-info
{
	border-top:1px solid #c7cfe0;
	border-bottom:1px solid #c7cfe0;
	padding:5px;
	margin-bottom: 20px;
}

</style>
<h3 class='m-b-xs text-black'>
Add new site
</h3>
<div class='well well-sm'>
At least one manager is required for each site. Please make sure the site manager is registered first before adding the site.
</div>
<?php echo flash::data();?>
<form method='post' id='siteAddForm'>
<div class='row'>
	<div class='col-sm-6'>
	<section class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
			<label>1. P1m Name</label>
			<div class='row'>
				<div class='col-sm-9'>
				<?php
				$readOnly	= $siteName?"readonly='true'":"";
				echo form::text("siteName","$readOnly size='40' class='form-control' onkeyup='prepareSlug();' placeholder='For example Kampung Pandan'",$siteName);?>
				<?php echo flash::data('siteName');?>
				</div>
				<div class='col-sm-3'>
				<?php echo form::text("siteRefID","class='form-control' placeholder='Old Pi1M Site ID'",$newSiteRefID);?>
				<?php echo flash::data('siteRefID');?>
				</div>
			</div>
			</div>
			<div class="form-group">
			<label>2. P1m Slug</label>
			<?php
			$readOnly	= $siteSlug?"readonly='true'":"";
			echo form::text("siteSlug","$readOnly class='form-control' placeholder=\"A url represents the p1m, make sure it's as clear as possible.\"",$siteSlug);?>
			<?php echo flash::data("siteSlug");?>
			</div>
		</div>
	</section>
	</div>
	<div class='col-sm-6'>
		<section class="panel panel-default">
		<div class="panel-body">
		<div class="form-group">
			<label>3. Manager Email</label> <a target='_blank' href='<?php echo url::base("user/add?level=2");?>' class='fa fa-plus-circle'></a>
			<?php echo form::text("manager","size='40' class='form-control'  placeholder=\"The p1m manager's email for this p1m.\"");?>
			<?php echo flash::data('manager');?>
		</div>
		<div class='form-group'>
			<label>4. P1m State</label>
			<?php echo form::select("stateID",$stateR,"class='form-control'","","[Select State]");?>
		</div>
		</div>
		</section>
	</div>
</div>
<div class='well well-sm'>Optional Fields : Manager can later update below information through his panel.</div>

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
					
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Commencement Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoCommencementDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoCommencementDate'])));?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Actual Start Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoActualStartDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoActualStartDate'])));?>
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
					
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Operation Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoOperationDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoOperationDate'])));?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Completion Date</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoCompletionDate","class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('d-m-Y', strtotime($row['siteInfoCompletionDate'])));?>
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
					<?php //$parliament	= model::load("helper")->parliament(); ?>
					<?php echo form::select("siteInfoParliament",$parliament,"class='form-control'",$row['siteInfoParliament'],"[SELECT PARLIAMENT]");?>
						<?php //echo form::text("siteInfoParliament","class='form-control'",$row['siteInfoParliament']);?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Phase</label>
				<div class='row'>
					<div class='col-md-12'>
					<?php echo form::select("siteInfoPhase",$batchList,"class='form-control'",$row['siteInfoPhase'],"[SELECT BATCH]");?>
						<?php //echo form::text("siteInfoPhase","class='form-control'",$row['siteInfoPhase']);?>
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
					<?php //$district	= model::load("helper")->district(); ?>
					<?php echo form::select("siteInfoDistrict",$district,"class='form-control'",$row['siteInfoDistrict'],"[SELECT DISTRICT]");?>
						<?php //echo form::text("siteInfoDistrict","class='form-control'",$row['siteInfoDistrict']);?>
			</div>
				</div></div>
			</div>
			<div class='col-sm-4'>
			<div class='form-group'>
				<label>Population</label>
				<div class='row'>
					<div class='col-md-12'>
						<?php echo form::text("siteInfoPopulation","class='form-control'",$row['siteInfoPopulation']);?>
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
		<div class='panel-body' style='height:218px;'>
		<div class='form-group'>
			<label>4. Phone No.</label>
			<?php echo form::text("siteInfoPhone","class='form-control'");?>
		</div>
		<div class='form-group'>
			<label>5. Fax No.</label>
			<?php echo form::text("siteInfoFax","class='form-control'");?>
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
					Latitude : <?php echo form::text("siteInfoLatitude","class='form-control'");?>
				</div>
				<div class='col-md-3'>
					Longitude : <?php echo form::text("siteInfoLongitude","class='form-control'");?>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<label>7. Address</label>
			<?php echo form::textarea("siteInfoAddress","class='form-control'");?>
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
	          <?php echo flash::data("_post.siteInfoDescription");?>
	        </div>
	        <input type='hidden' name='siteInfoDescription' id='siteInfoDescription' />
	    </div>
	    </div>
		</section>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12 text-center'>
	<?php echo form::submit("Add a new site!","class='btn btn-primary'");?>
	</div>
</div>
<script type="text/javascript">
var base_url	= "<?php echo url::base();?>/";
	
$("#siteAddForm").submit(function()
{
	$("#siteInfoDescription").val($("#editor").html());
});

$( "#stateID" ).change(function() {

  $.ajax({
            type: "GET",
            url:base_url+"site/parliamentList/"+this.value,
            dataType: "json",
            success: function (data) {
            	$('#siteInfoParliament').empty();
            	$('#siteInfoParliament').append('<option value="">[SELECT PARLIAMENT]</option>');
                $.each(data,function(i,obj)
                {
                 $('#siteInfoParliament').append($('<option></option>').attr('value', i).text(obj));
                });  
                }
          });

  $.ajax({
            type: "GET",
            url:base_url+"site/districtList/"+this.value,
            dataType: "json",
            success: function (data) {
            	$('#siteInfoDistrict').empty();
            	$('#siteInfoDistrict').append('<option value="">[SELECT DISTRICT]</option>');
                $.each(data,function(i,obj)
                {
                 $('#siteInfoDistrict').append($('<option></option>').attr('value', i).text(obj));
                });  
                }
          });
					
});

</script>
</form>