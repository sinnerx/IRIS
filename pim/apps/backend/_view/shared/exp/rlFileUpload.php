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
        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base("exp/rlFileUpload/".$rl->prReconcilationID); ?>' enctype="multipart/form-data" >        
          
            <?php 
            $isCategoryExist = 0;
            foreach ($rl->getCategories() as $rlCategory) {
              # code...
              //var_dump($rlCategory);
            ?>
             <?php if(!$rlCategory->isUploaded()):?>
              <?php if($rl->isEditable()):?>
                  <div class="form-group">
                    <label class="col-lg-12 "><?php echo $rlCategory->expenseCategoryName; ?></label>
                    <div class="col-lg-12">
                    <?php 
                    //echo form::select("itemCategory".$rlCategory->prReconcilationCategoryID, $categories,"class='input-sm form-control input-s-sm inline v-middle' readonly ", $rlCategory->prReconcilationCategoryID,"-SELECT CATEGORY-");

                    //echo form::text("itemCategory", 'class="item-amount form-control " disabled style="width:300px"', $rlCategory->expenseCategoryName);
                    echo form::hidden("itemCategory".$rlCategory->prReconcilationCategoryID, 'class="item-amount form-control"', $rlCategory->prReconcilationCategoryID);
                    ?>              
                        
                    
                    <?php echo form::file("fileUpload[$rlCategory->prReconcilationCategoryID]");?>
                    <?php echo flash::data("fileUpload[$rlCategory->prReconcilationCategoryID]");?>
                    </div>
                  </div>
             <?php 
             $isCategoryExist++;
             endif;?>
            <?php endif;?>
            <?php
            }?>
        
         

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
              <?php if ($isCategoryExist > 0) : ?>
              <button type="submit" class="btn btn-sm btn-default">Submit</button>              
            <?php else: ?>
              <label>Nothing to be uploaded.</label>
            <?php endif; ?>
            </div>
          </div>
        </form>
      </div>
    </section>
    </div>
	</div>
</div>