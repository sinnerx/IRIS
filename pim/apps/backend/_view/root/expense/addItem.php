<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
       Add Item   
     </div>      
    <section class="panel panel-default">
      <form class=" bs-example form-horizontal" method='post' action="<?php echo url::base('expense/saveItem/'.$categoryId);?>">
        <header class="panel-heading">
           
        </header>
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Item Name</label>
            <div class="col-lg-6">
              <?php echo form::text("itemName","class='input-sm form-control input-s-sm inline v-middle'",$row['purchaseRequisitionItemName']); ?>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-offset-9 col-lg-10">              
              <button class="btn btn-sm btn-default" value="Submit" >Submit</button>
            </div>
          </div>

      </form>
    </section>
    </div>
    </div>


  