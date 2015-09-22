<script type="text/javascript">

  $(document).ready(function() {

    var result = 1.00;
        $('#total').attr('value', function() {
            $('.amount').each(function() {
                if ($(this).val() != '') {
                    result *= parseFloat($(this).val());
                } else {
                   result = 0.00;
                }
            });
            return result;
        });
    
    $('.amount').keyup(function() {
        var result = 1.00;
        $('#total').attr('value', function() {
            $('.amount').each(function() {
                if ($(this).val() !== '') {
                    result *= parseFloat($(this).val());
                }
            });
            return result;
        });
    });
  });

</script>    
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Edit Transaction for <?php echo $transactionDate; ?></span>
      
			</h4>
		</div>
		<?php 
    echo flash::data();?>
		
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">

        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/editForm/'.$item[billingTransactionItemID].'/'.$item[billingTransactionID]);?>'>
      <input type="hidden" name="transactionDate" value="<?php echo $transactionDate ?>" />   
          <div class="form-group">
            <label class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              
              <?php echo form::select("itemID",$itemList,"class='input-sm form-control input-s-sm inline v-middle';'",$itemID,"[SELECT TRANSACTION]");?>
                        
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Description</label>
            <div class="col-lg-10">
              <?php echo form::text("description","class='form-control'",$item['billingTransactionItemDescription']);?>              
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Price (RM)</label>
            <div class="col-lg-10">
              <?php echo form::text("price","class='form-control amount' readonly",$item['billingItemPrice']);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Unit/Hours</label>
            <div class="col-lg-10">
              <?php echo form::text("unit","class='form-control amount'",$item['billingTransactionItemUnit']);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Quantity</label>
             <div class="col-lg-10">
              <?php echo form::text("quantity","class='form-control amount'",$item['billingTransactionItemQuantity']);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Total</label>
             <div class="col-lg-10">

              <?php echo form::text("total","class='form-control' readonly",number_format($item['billingTransactionItemPrice'], 2, '.', ''));?>
            </div>
          </div>
          
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