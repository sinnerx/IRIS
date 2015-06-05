   
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Edit Transaction</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		<?php// foreach ($fcTransaction as $row):?>
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">

        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/editTransaction/'.$fcTransaction->billingFinanceTransactionID);?>'>

          <div class="form-group">
            <label class="col-lg-2 control-label">Date</label>
            <div class="col-lg-10">
              
              <?php echo form::text("selectDate","class='input-sm input-s form-control'",date('d-m-Y', strtotime($fcTransaction->billingFinanceTransactionDate)));?>
                        
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Site</label>
            <div class="col-lg-10">
                 <?php
            echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle'",$fcTransaction->siteID,"[SELECT SITE]");
              ?>
        
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Type</label>
            <div class="col-lg-10">
              <?php 
              echo form::select("transactionType",$typeList,"class='input-sm form-control input-s-sm inline v-middle'",$fcTransaction->billingFinanceTransactionType,"[SELECT TYPE]"); 
                ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Description</label>
            <div class="col-lg-10">
              <?php echo form::text("description","class='form-control amount'",$fcTransaction->billingFinanceTransactionDescription);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Amount</label>
             <div class="col-lg-10">
              <?php echo form::text("total","class='form-control amount'",$fcTransaction->billingFinanceTransactionTotal);?>
            </div>
          </div>
          
        <?php //endforeach; ?>
         
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <button type="submit" class="btn btn-sm btn-default">Update</button>
            </div>
          </div>
        </form>
      </div>
    </section>
    

		</div>
	</div>
</div>