<script type="text/javascript">
	$(document).ready(function() {

	var result = 1.00;
	    $('#totalprice').attr('value', function() {
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
	    $('#totalprice').attr('value', function() {
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
			<span>Insert Item</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">

      	<input type="hidden" name="selectDate" value="<?php echo $selectDate?>"> 
      	<?php $selectDate = strtotime($selectDate); ?>
        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base('expense/addTransaction/'.$categoryId.'/'.$selectDate);?>'>        
          <div class="form-group">
            <label class="col-lg-3 control-label">Item Type</label>
            <div class="col-lg-8">
				<?php echo form::select("itemType",$categories,"class='input-sm form-control input-s-sm inline v-middle'","[SELECT ITEM]");?>              
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-lg-3 control-label">Description</label>
             <div class="col-lg-8">
				<?php echo form::text("description","class='form-control'"); ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Unit Price (RM)</label>
            <div class="col-lg-8">
				<?php echo form::text("price","class='form-control amount'"); ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Quantity</label>
             <div class="col-lg-8">
				<?php echo form::text("quantity","class='form-control amount'"); ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Total Price (RM)</label>
            <div class="col-lg-8">
				<?php echo form::text("totalprice","class='form-control' readonly"); ?>
			</div>
          </div>

          <div class="form-group">
            <label class="col-lg-3 control-label">Remark</label>
            <div class="col-lg-8">
				<?php echo form::text("remark","class='form-control'"); ?>
			</div>
          </div>
          
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
              <button type="submit" class="btn btn-sm btn-default">Submit</button>              
            </div>
          </div>
        </form>
      </div>
    </section>
    </div>
	</div>
</div>