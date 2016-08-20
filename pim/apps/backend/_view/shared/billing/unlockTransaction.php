<script type="text/javascript">	

	var base_url	= "<?php echo url::base();?>/";

	var billing	= new function()
	{	
		this.select	= function()
		{					
			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";			
			var selectDateStart	= $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";		
			var selectDateEnd	= $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		//window.location.href	= base_url+"billing/dailyJournal?"+siteID+selectDateStart+selectDateEnd;
	   		//console.log(base_url + "ajax/unlockTransaction/" + $("#siteID").val());

		    var btn = $('<button class="btn btn-sm btn-default" id="btn_unlock">Unlock</button>').click(function () {
		        $.ajax({
					url: pim.url('ajax/shared/unlockTransaction/unlockSite/' + $("#siteID").val()), success: function (result){
						result = $.parseJSON(result);
						console.log(result);
						if(result.status == 'success'){
							//$("#btn_div").empty().remove(btn);
							//$("#btn_unlock").length ? $("#btn_unlock").remove() : "" ;
							//$("#btn_div").append(btn);
							$("#btn_unlock").prop('disabled', true);
							return;
						}				
					}
				});
		    });	   		 

	   		console.log(pim.url('ajax/shared/unlockTransaction/checkSite/' + $("#siteID").val()));
		$.ajax({
			url: pim.url('ajax/shared/unlockTransaction/checkSite/' + $("#siteID").val()), success: function (result){
				result = $.parseJSON(result);
				console.log(result);
				if(result.status == 'success'){
					//$("#btn_div").empty().remove(btn);
					$("#btn_unlock").length ? $("#btn_unlock").remove() : "" ;
					$("#btn_div").append(btn);
					$("#btn_unlock").prop('disabled', true);
				}				
				else if(result.status == 'fail'){
					//$("#btn_div").empty().remove(btn);
					$("#btn_unlock").length ? $("#btn_unlock").remove() : "" ;
					$("#btn_div").append(btn);
					$("#btn_unlock").prop('disabled', false);
				}
			}
		});
		}
	}

</script>
<style type="text/css">
	
	label {

    	font-size: 13px;
    	font-weight: bold;
	}
	.input-s-sm {

		width: 250px;
	}

</style>

<h3 class='m-b-xs text-black'>
	Unlock Site
</h3>
<div class='well well-sm'>
Select Date
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method="post" action="#">
			<?php  if(session::get("userLevel") == 99 || session::get("userLevel") == 3): ?>	
			<div  class="form-group" style="margin-left:10px">
			<?php echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select($itemID);'",request::get("siteID"),"[SELECT SITE]");?>			
			</div>
			<div id="btn_div" class="form-group"></div>
		<?php endif; ?>
		</form>	
	</div>

<div class='row'>
	<div class="col-sm-10">
		<div class='well well-sm'>
			List of Unlock Sites
		</div>
		
		<div class="table-responsive">
			<table class='table'>
				<tr>
					<th width="15px">No.</th>
					<th width="300px">Site</th>
					<th width="200px">Date/Time</th>
				</tr>
				<?php $count = 1; if(count($unlockSite) === 0):?>
				<tr>
					<td colspan="3" style="text-align: center;">No sites found.</td>
				</tr>				
				<?php else : foreach($unlockSite as $site):?>
				<tr>
					<td><?php echo $count; ?></td>
					<td><?php echo $site['siteName']; ?></td>
					<td><?php echo $site['siteUnlockDate']; ?></td>
				</tr>
				<?php $count++; endforeach; endif;?>	
			</table>
		</div>
	</div>
</div>