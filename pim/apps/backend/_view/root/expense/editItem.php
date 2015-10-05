<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<style type="text/css">
  
  label {

      font-size: 13px;
      font-weight: bold;
  }
  .input-s-sm {

    width: 250px;
  }

</style>

    <section class="panel panel-default">
      <form  class=" bs-example form-horizontal"  method='post' action="<?php echo url::base('expense/updateItem/'.$row['purchaseRequisitionItemId']);?>">
        <header class="panel-heading">
            Edit Item       
        </header>
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Item Name</label>
            <div class="col-lg-6">
              <?php echo form::text("itemName","class='input-sm form-control input-s-sm inline v-middle'",$row['purchaseRequisitionItemName']); ?>
            </div>
          </div>
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Status</label>
            <div class="col-lg-6">
              <?php echo form::select("itemStatus",$status,"class='input-sm form-control input-s-sm inline v-middle'",$row['purchaseRequisitionItemStatus']);?>            
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-offset-9 col-lg-10">
              <button class="btn btn-sm btn-default" value="Submit" >Submit</button>
            </div>
          </div>

      </form>
    </section>


  