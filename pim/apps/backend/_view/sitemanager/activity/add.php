<script type="text/javascript">
	
var activity	= new function()
{
	this.showTypeDetail	= function(type)
	{
		var r	= {1:"event",2:"training"};
		$("#activityType").val(type);
		$("#type-event, #type-training").hide();
		$("#type-"+r[type]).show();

		pim.uriHash.set(r[type]);
	}

	this.disableAddress	= function()
	{
		if($("#activityAddressFlag")[0].checked)
		{
			$("#activityAddress").attr("disabled",true);
			$("#activityAddress").val($("#siteInfoAddress").html());
		}
		else
		{
			$("#activityAddress").removeAttr("disabled");
			$("#activityAddress").val("");
		}
	}
	pim.uriHash.addCallback({"event":function(){activity.showTypeDetail(1)},"training":function(){activity.showTypeDetail(2)}});
}

</script>
<script type="text/javascript" src='<?php echo url::asset("backend/tools/daterangepicker/moment.min.js");?>'></script>
<script type="text/javascript" src='<?php echo url::asset("backend/tools/daterangepicker/daterangepicker.js");?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("backend/tools/daterangepicker/daterangepicker-bs3.css");?>">
<h3 class='m-b-xs text-black'>
Add Activity 
</h3>
<div class='well well-sm'>
Add activities to your side. Every activity added will be pending for your cluster lead approval first.
</div>
<?php echo flash::data();?>
<form method='post' onsubmit="">
<div class='row'>
	<div class='col-sm-7'>
		<div class='row'>
			<div class='col-sm-8'>
				<div class='form-group'>
					<label>
						1. Activity Name <?php echo flash::data("activityName");?>
					</label>
					<?php echo form::text("activityName","class='form-control'");?>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
				<label>2. Type <?php echo flash::data("activityType");?></label>
				<?php
				$conv	= Array("event"=>1,"training"=>2);
				?>
				<?php echo form::select("activityType",Array(1=>"Event",2=>"Training"),"onchange='activity.showTypeDetail(this.value);' class='form-control'",$conv[request::get("type")]);?>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-8'>
				<div class='form-group'>
				<label>3. Description</label>
				<?php echo form::textarea("activityDescription","class='form-control'");?>
				</div>
				<div class='form-group'>
				<label style='display:block;'>4. Where? (Address) 
					<span class='pull-right'>Use site address <input onclick='activity.disableAddress();' type='checkbox' id='activityAddressFlag' name='activityAddressFlag' value='1' /></span>
				</label>
				<?php echo form::textarea("activityAddress","class='form-control'");?>
				<div id='siteInfoAddress' style='display:none;'><?php echo $siteInfoAddress;?></div>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label>5. Participation <?php echo flash::data("activityParticipation");?></label>
					<?php echo form::select("activityParticipation",Array(1=>"Open",2=>"Only for site member"),"class='form-control'");?>
				</div>
				<div class='form-group'>
					<label>6. Date 
						<a href='javascript:void(0);' class='fa fa-calendar' onclick='$("#activityDate").focus();'></a>
						<?php echo flash::data("activityDate");?>
					</label>
					<?php echo form::text("activityDate","class='form-control' readonly style='background:white;'");?>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-5'> <!-- activity specific data. -->
		<div class='row'>
			<div class='col-sm-12' id='type-event' style="display:none;">
				<section class='panel panel-default'>
					<div class='panel-heading'>
					<h5>Event's detail</h5>
					</div>
					<div class='panel-body'>
						<div class='form-group'>
							<label>Type of event <?php echo flash::data("eventType");?></label>
							<?php echo form::select("eventType",$eventTypeR,"class='form-control'");?>
						</div>
					</div>
				</section>
			</div>
			<div class='col-sm-12' id='type-training' style="display:none;">
				<section class='panel panel-default'>
					<div class='panel-heading'>
					<h5>Training's detail</h5>
					</div>
					<div class='panel-body'>
						<div class='form-group'>
							<label>Training Type <?php echo flash::data("trainingType");?></label>
							<?php echo form::select("trainingType",$trainingTypeR,"class='form-control'",null,"[DEFAULT]");?>
						</div>
						<div class='form-group'>
							<label>Max Pax <span style='opacity:0.5;'>(0 for no-limit)</span></label>
							<?php echo form::text("trainingMaxPax","class='form-control' style='width:70px;'");?>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12' style='text-align:center;'>
		<input type='submit' value='Submit Activity' class='btn btn-primary' />
		<input type='button' value='Cancel' class='btn btn-default' />
	</div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
  $('#activityDate').daterangepicker({
  	format: 'DD/MM/YYYY',
  });
});
</script>