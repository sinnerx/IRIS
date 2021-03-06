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
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submitPrNumber/');?>'>
          <header class="panel-heading">
            Purchase Requisition
          </header>
  
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="20%">Site Name</th>
                  <th width="10%">Date Created</th>
                  <th width="10%">Type</th>
                  <th width="10%">Status</th>                  
                  <th width="20%">PR Number</th>
                  <th width="10%"></th>
                </tr>
          
          <?php $no = pagination::recordNo();

          foreach ($listPR as $key => $list):?>           

                <tr id = "<?php echo $key ?>">
                  <td><?php echo $no++; ?></td>
                  <td><?php $siteName = model::load('site/site')->getSite($list['siteID']);
                      echo $siteName['siteName']; ?>
                  </td>
                  <td><?php echo $list['purchaseRequisitionDate']; ?></td>
                  <td><?php echo isset($prTerm[$list['purchaseRequisitionType']]) ? $prTerm[$list['purchaseRequisitionType']] : null;  ?></td>
                  <td><?php
                        $getStatus = model::load('expense/approval')->getStatus($list['purchaseRequisitionId'], $list['purchaseRequisitionType']);
                        if ($list['purchaseRequisitionNumber'] == null) { 
                        echo $getStatus[0]; 
                        } else {
                        echo "Success";   
                        }  ?>
                  </td>
                  <td><?php 
                      if ($list['purchaseRequisitionNumber'] == null) { 
                          
                          if ($getStatus[1] == 1) {  ?>

                            <input type="hidden" name="prID[<?php echo $key ?>]" id="prID" value="<?php echo $list['purchaseRequisitionId'] ?>" />
                            <input type="text"   name="prNumber[<?php echo $key ?>]" size="10" class="form-control total" id="prNumber" />
                            <button name="submit[<?php echo $key ?>]" type="submit" class="btn btn-sm btn-default">Add</button>

                        <?php   }
                        } else {  
                        
                      echo $list['purchaseRequisitionNumber']; } ?>                   
                      
                  </td>
                  <td><!-- <a href="<?php echo url::base('expense/getForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a> --></td>
                </tr>
            
        <?php endforeach; ?>  
                        
              </tbody>
            </table>                  
          </div>
          <br>
        
    </form>
    </section>
          <footer class='panel-footer'>
            <div class='row'>
              <div class="col-sm-10">
                <?php echo pagination::link();?>
              </div>
            </div>
          </footer>
       
     </div>
</div>





