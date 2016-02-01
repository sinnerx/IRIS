<script type="text/javascript">
  
var rlFileUpload = new function()
{
  this.amountChangeUpdate = function()
  {
    var amount = Number($("#amount").val());
    var gst = Number($("#gst").val());

    $("#total").val(amount+gst);
  }
}

</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Add Slip Payment / Bill / Receipt</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		
    <div class="modal-body">
    <p>Upload receipt for this category, and input the reconciled amounts for this category.</p>
    <section class="panel panel-default">
      <div class="panel-body">
        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base("exp/rlFileUpload/".$rl->prReconcilationID.'/'.$categoryID); ?>' enctype="multipart/form-data" >        
          <div class="form-group">
            <label class="col-lg-5 control-label">Item Category</label>
            <div class="col-lg-7">
				<?php echo form::select("itemCategory",$categories,"class='input-sm form-control input-s-sm inline v-middle' disabled required", $categoryID,"-SELECT CATEGORY-");?>              
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-lg-5 control-label">Upload File </label>
             <div class="col-lg-7">
				<?php echo form::file("fileUpload", 'required');?>
				<?php echo flash::data("fileUpload");?>
            </div>
          </div>
        <?php /*
          <div class="form-group">
            <label class="col-lg-5 control-label">Amount Without GST (RM)</label>
            <div class="col-lg-7">
				<?php echo form::text("amount","class='form-control' required onkeyup='rlFileUpload.amountChangeUpdate();'"); ?>
            </div>

          </div>
          <div class="form-group">
            <label class="col-lg-5 control-label">GST Amount (RM)</label>
            <div class="col-lg-7">
        <?php echo form::text("gst","class='form-control' onkeyup='rlFileUpload.amountChangeUpdate();'"); ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-5 control-label">Total Amount with GST (RM)</label>
            <div class="col-lg-7">
        <?php echo form::text("total","class='form-control' required"); ?>
            </div>
          </div>
          */?>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-7">
            <input type="hidden" name="prId" value="<?php echo $prId?>"> 
              <button type="submit" class="btn btn-sm btn-default">Submit</button>              
            </div>
          </div>
        </form>
      </div>
    </section>
    </div>
	</div>
</div>