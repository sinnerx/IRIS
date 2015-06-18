<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Insert New Item</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">

        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/addItem/'.$sales->salesID);?>'>
          <div class="form-group">
            <label class="col-lg-2 control-label">Hot Key</label>
            <div class="col-lg-10">
              <?php echo form::select("hotKey",$keyList,"class='input-sm form-control input-s-sm inline v-middle'","[SELECT KEY]");?>              
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              <?php echo form::text("itemName","class='form-control'");?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Type</label>
            <div class="col-lg-10">             
              <?php echo form::select("itemType",$itemType,"class='input-sm form-control input-s-sm inline v-middle'",1);?>           
            </div>
          </div>


          <!-- <div class="form-group">
            <label class="col-lg-2 control-label">Description</label>
            <div class="col-lg-10">
              <?php echo form::text("description","class='form-control'");?>
              <div class="checkbox i-checks">            
                <label>
                  <input name="descriptionDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                </label>
              </div>

            </div>
          </div> -->
          <div class="form-group">
            <label class="col-lg-2 control-label">Price</label>
            <div class="col-lg-10">
              <?php echo form::text("price","class='form-control'");?>
              <div class="checkbox i-checks">            
                <label>
                  <input name="priceDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                </label>
              </div>
              <!-- <div class="checkbox i-checks">            
                <label>
                  <input name="taxDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Include GST.</span>
                </label>
              </div> -->
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Unit</label>
            <div class="col-lg-10">
              <div class="checkbox i-checks">            
                <label>
                  <input name="unitDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Quantity</label>
             <div class="col-lg-10">
              <div class="checkbox i-checks">            
                <label>
                  <input name="quantityDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <button type="submit" class="btn btn-sm btn-default">Submit</button>              
            </div>
          </div>
        </form>
      </div>
    </section>
    

		</div>
	</div>
</div>