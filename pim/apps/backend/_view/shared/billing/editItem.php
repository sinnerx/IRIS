<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Edit Item</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">

        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/editItem/'.$item->billingItemID);?>'>
          <div class="form-group">
            <label class="col-lg-2 control-label">Hot Key</label>
            <div class="col-lg-10">
              <?php echo form::text("hotKey","class='form-control'",$item->billingItemHotkey);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              <?php echo form::text("itemName","class='form-control'",$item->billingItemName);?>
            </div>
          </div>
         <!--  <div class="form-group">
            <label class="col-lg-2 control-label">Description</label>
            <div class="col-lg-10">
              <?php echo form::text("description","class='form-control'",$item->billingItemDescription);?>
              <div class="checkbox i-checks">            
                <label>
                  <?php
                    if ($item->billingItemDescriptionDisabled == 1){  ?>
                    <input name="descriptionDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                    <?php } else {  ?>
                    <input name="descriptionDisabled" type="checkbox" value="1"><i></i><span style="font-size: 12px;">Enable Editing.</span>
                  <?php } ?>                  
                </label>
              </div>

            </div>
          </div> -->
          <div class="form-group">
            <label class="col-lg-2 control-label">Price</label>
            <div class="col-lg-10">
              <?php echo form::text("price","class='form-control'",$item->billingItemPrice);?>
              <div class="checkbox i-checks">            
                <label>
                  <?php
                    if ($item->billingItemPriceDisabled == 1){  ?>
                    <input name="priceDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                    <?php } else {  ?>
                    <input name="priceDisabled" type="checkbox" value="1"><i></i><span style="font-size: 12px;">Enable Editing.</span>
                  <?php } ?>                                    
                </label>
              </div>
              <!-- <div class="checkbox i-checks">            
                <label>
                  <?php
                    if ($item->billingItemPriceDisabled == 1){  ?>
                    <input name="taxDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">GST.</span>
                    <?php } else {  ?>
                    <input name="taxDisabled" type="checkbox" value="1"><i></i><span style="font-size: 12px;">GST.</span>
                  <?php } ?>                                    
                </label>
              </div> -->
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Unit</label>
            <div class="col-lg-10">
              <div class="checkbox i-checks">            
                <label>
                <?php
                    if ($item->billingItemUnitDisabled == 1){  ?>
                    <input name="unitDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                    <?php } else {  ?>
                    <input name="unitDisabled" type="checkbox" value="1"><i></i><span style="font-size: 12px;">Enable Editing.</span>
                  <?php } ?>                         
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Quantity</label>
             <div class="col-lg-10">
              <div class="checkbox i-checks">            
                <label>
                <?php
                    if ($item->billingItemQuantityDisabled == 1){  ?>
                    <input name="quantityDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                    <?php } else {  ?>
                    <input name="quantityDisabled" type="checkbox" value="1"><i></i><span style="font-size: 12px;">Enable Editing.</span>
                  <?php } ?>       
                </label>
              </div>
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