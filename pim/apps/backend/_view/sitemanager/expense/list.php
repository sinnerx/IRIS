<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

  $(document).ready(function() {
    jQuery("#selectDate").datetimepicker({
                  timepicker:false,
                  format:'d F Y',
                  step:5,
    });
  });

</script>


<style type="text/css">
  
  label {

      font-size: 13px;
      font-weight: bold;
  }
  .input-s-sm {

    width: 250px;
  }

</style>

<h3 class='m-b-xs text-black'>
  Purchase Requisition List Open / Closed
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submit1Requisition/');?>'>
          <header class="panel-heading">
            Purchase Requisition
          </header>
          <div align="right"> 
            <a href='<?php echo url::base("expense/add/");?>' class='fa fa-external-link' style="color:green;">Add New Purchase Requisition</a>     
          </div>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="10%">Date Created</th>
                  <th width="10%">Type</th>
                  <th width="10%">Status</th>
                  <th width="10%">Remark</th>
                  <th width="10%">PR Number</th>
                  <th width="10%"></th>
                </tr>
          
          <?php foreach ($listPR as $key => $list):?>           
                <tr id = "<?php echo $key ?>">
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo $list['purchaseRequisitionCreatedDate']; ?></td>
                  <td><?php echo isset($prTerm[$list['purchaseRequisitionType']]) ? $prTerm[$list['purchaseRequisitionType']] : null;  ?></td>
                  <td><?php $status = model::load('expense/approval')->getStatus(); ?></td>
                  <td></td>
                  <td></td>
                  <td><a href="<?php echo url::base('expense/editForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a></td>
                </tr>            
        <?php endforeach; ?>  
                        
              </tbody>
            </table>                  
          </div>
          <br>
        
    </form>
    </section>

    <section class="panel panel-default">
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submit1Requisition/');?>'>
          <header class="panel-heading">
            Reconciliation List
          </header>
          <div align="right"> 
            <a href='<?php echo url::base("expense/reconciliation/");?>' class='fa fa-external-link' style="color:green;">Add New Reconciliation List</a>     
          </div>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="10%">Date Created</th>
                  <th width="10%">Type</th>
                  <th width="10%">Status</th>                  
                  <th width="10%">PR Number</th>
                  <th width="10%"></th>
                </tr>
          
          <?php foreach ($listRL as $key => $list):?>           
                <tr id = "<?php echo $key ?>">
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo $list['purchaseRequisitionCreatedDate']; ?></td>
                  <td><?php echo isset($prTerm[$list['purchaseRequisitionType']]) ? $prTerm[$list['purchaseRequisitionType']] : null;  ?></td>
                  <td>Open</td>
                  <td></td>
                  <td></td>
                </tr>
            
        <?php endforeach; ?>  
                        
              </tbody>
            </table>                  
          </div>
          <br>
        
    </form>
    </section>

          <footer class="panel-footer"></footer>
       
     </div>
</div>






