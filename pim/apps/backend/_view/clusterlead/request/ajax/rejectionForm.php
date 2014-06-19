<script type="text/javascript">
	
cluster.overview.reject	= function(e)
{
	var reqID	= $("#siteRequestID").val();

	// update approval (reject).
	var res = cluster.overview.updateApproval(reqID,2,true);

	if(res == false)
	{
		e.stopPropagation();
	}
};

cluster.overview.sendCorrection = function()
{
	if($(".btn-primary").val() != "Send Correction Message")
	{
		$(".btn-reject").hide();
		$("#siteRequestCorrectionText").show();
		$(".btn-primary").val("Send Correction Message");
		setTimeout(function()
		{
			$(".btn-primary").attr("data-dismiss","modal");
		},100);
	}
	else
	{
		var reqID	= $("#siteRequestID").val();
		var text	= $("#siteRequestCorrectionText").val();

		$.ajax({type:"POST",data:{text:text},url:pim.base_url+"ajax/request/requestCorrection/"+reqID}).done(function(txt)
		{
			//refresh the site.
			cluster.overview.getSiteRequests(cluster.overview.currentSiteID);
		});
	}
}

</script>
<div class="modal-dialog">
	<input type="hidden" id='siteRequestID' value='<?php echo $siteRequestID;?>' />
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Reject changes - Announcement Update
				<div style='font-size:11px;'>Any rejected changes will be discarded, and reverted to last approved value. Maybe you can <u>send a correction</u> message upon this request.</div>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;padding-bottom:0px;'>
			<?php echo form::textarea("siteRequestCorrectionText","class='form-control' style='display:none;width:100%;height:150px;'");?>
		</div>
		<footer class='modal-footer' style='margin-top:5px;border-top:0px;'>
			<input type='button' value="Cancel" class='btn btn-default' data-dismiss='modal' />
			<input type='button' value='Just send a correction message' onclick='cluster.overview.sendCorrection(event);' class='btn btn-primary' />
			<input type='button' value='Reject' class='btn btn-danger btn-reject' onclick='return cluster.overview.reject(event);' data-dismiss='modal' />
		</footer>
	</div>
</div>