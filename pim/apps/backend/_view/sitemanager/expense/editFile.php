<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Edit File</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">
        
        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base("expense/editFile/".$prId."/".$fileId); ?>' enctype="multipart/form-data" >        
          <div class="form-group">
            <label class="col-lg-3 control-label">Item Category</label>
            <div class="col-lg-8">
				<?php echo form::select("itemCategory",$categories,"class='input-sm form-control input-s-sm inline v-middle'",$item->purchaseRequisitionCategoryId);?>              
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-lg-3 control-label">Upload File </label>
             <div class="col-lg-8">
				<?php echo form::file("fileUpload");?>
				<?php echo flash::data("fileUpload");?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Amount (RM)</label>
            <div class="col-lg-8">
				<?php echo form::text("amount","class='form-control'",$item->purchaseRequisitionFileAmount); ?>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-lg-3 control-label">GST Amount (RM)</label>
            <div class="col-lg-8">
        <?php echo form::text("gst","class='form-control'",$item->purchaseRequisitionFileGst); ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Total Amount (RM)</label>
            <div class="col-lg-8">
        <?php echo form::text("total","class='form-control'",$item->purchaseRequisitionFileTotal); ?>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
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