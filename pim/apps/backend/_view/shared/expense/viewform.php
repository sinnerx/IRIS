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
  Purchase Requisition Form
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/listStatusOm/');?>'>
          <header class="panel-heading">
            Purchase Requisition Type : <?php echo isset($prTerm[$prType]) ? $prTerm[$prType] : null; ?>            
            <input type="hidden" name='prTerm'  value="<?php echo $prType ?>">
          </header>
  
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <thead>
                <tr>
                  <td><label>Date:</label></td>
                  <td width="30%">To be completed for Capital Expenditure</td>                       
                  <td colspan="4"><label>For NuSuara's use only</label></td>                        
                </tr>
                 <tr>
                  <td><?php echo $prDate; ?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>
                 <tr>
                  <td><label>PI1M:</label></td>
                  <td>Budgeted:</td>           
                  <?php //if ($prFile[0]['purchaseRequisitionExpenses'] == 1) { $checked1 = "checked";} ?>
                  <td colspan="2"><input name='expenses' type="checkbox" disabled="disabled" value="1"> PI1M Expenses</td>
                  <?php //if ($prFile[0]['purchaseRequisitionEquipment'] == 1) { $checked2 = "checked";} ?>
                  <td colspan="2"><input name='equipment' type="checkbox" disabled="disabled" value="1"> PI1M Equipment</td>
                </tr>
                 <tr>
                  <td><?php echo $siteName['siteName']; ?></td>
                  <td>Addition:</td>                        
                  <?php //if ($prFile[0]['purchaseRequisitionEvent'] == 1) { $checked3 = "checked";} ?>             
                  <td colspan="2"><input name='event' type="checkbox" disabled="disabled" value="1"> Scheduled Event</td>
                  <?php //if ($prFile[0]['purchaseRequisitionAdhocevent'] == 1) { $checked4 = "checked";} ?>
                  <td colspan="2"><input name='adhocevent' type="checkbox" disabled="disabled" value="1"> Ad hoc Event</td>
                </tr>
                 <tr>
                  <td></td>
                  <td>Replacement:</td>
                  <?php //if ($prFile[0]['purchaseRequisitionOther'] == 1) { $checked5 = "checked";} ?>
                  <td colspan="2"><input name='other' type="checkbox" disabled="disabled" value="1"> Other</td>
                  <?php //if ($prFile[0]['purchaseRequisitionCitizen'] == 1) { $checked6 = "checked";} ?>
                  <td colspan="2"><input name='1citizen' type="checkbox" disabled="disabled" value="1"> 1Citizen</td>
                </tr>
             </table>

            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="40%">Description</th>
                  <th width="10%">Quantity</th>
                  <th width="10%">Unit Price (RM)</th>
                  <th width="10%">Total Price (RM)</th>
                  <th>Remarks</th>
                  <th></th>
                </tr>
          
        <?php foreach ($prItemList as $key => $category):?>           
            
                 <tr>  
                  <td><?php echo $key+1; ?></td> 
                  <td><?php echo $itemList[$category['purchaseRequisitionDetailItemId']]." : ".$category['purchaseRequisitionDetailDescription']; ?></td>     
                  <td><input type="text" size="5" onchange="requisition.calculate();" class="form-control amount" disabled="disabled" name="item[itemQuantity][<?php echo $key+1; ?>]" id="itemQuantity" value="<?php echo $category['purchaseRequisitionDetailQuantity']; ?>"/></td>
                  <td><input type="text" size="5" onchange="requisition.calculate();" class="form-control amount" disabled="disabled" name="item[itemPrice][<?php echo $key+1; ?>]" id="itemPrice" value="<?php echo $category['purchaseRequisitionDetailPrice']; ?>"/></td>
                  <td><input type="text" size="5" class="form-control total" name="item[itemTotalPrice][<?php echo $key+1; ?>]" disabled="disabled" id="itemTotalPrice" value="<?php echo $category['purchaseRequisitionDetailTotal']; ?>"/></td>
                  <td><input type="text" class="form-control" name="item[itemRemark][<?php echo $key+1; ?>]" id="itemRemark" disabled="disabled" value="<?php echo $category['purchaseRequisitionDetailRemark']; ?>"/></td>
                  <td></td>
                </tr>

               <input type="hidden" class="form-control" name="item[prDetailId][<?php echo $key+1; ?>]" value="<?php echo $category['purchaseRequisitionDetailId']; ?>"/> 
        <?php $allTotal = $category['purchaseRequisitionDetailTotal'] + $allTotal; 
              endforeach; ?>  
                        
                <tr >
                  <td colspan="4">Justification :</td>
                  <td colspan="3" bgcolor="#ededed"><b>Total (RM):</b></td>
                </tr> 

                <tr >
                  <td colspan="4">1. Current collection money: RM <?php echo $currentCollection ?> (as at 6.00pm, <?php echo date('d/m/Y ',strtotime($prDate)); ?>)</td>
                  <td width="10%"><input type="text"  disabled="disabled" size="5" class="form-control" id="allTotal" value="<?php echo $allTotal ?>"/></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr>
                  <td colspan="4">2. Balance Deposit: RM <?php echo $deposit ?> (as at 6.00pm, <?php echo date('d/m/Y ',strtotime($prDate)); ?>)</td>
                  <td colspan="4" style="background-color:#ededed"><b>Terms of Payment (For Nusuara's use only):</b></td>
                </tr> 

                <tr >
                  <td colspan="4"></td>
                  <td colspan="4" bgcolor="#ededed">
                  <?php echo isset($prTerm[$prType]) ? $prTerm[$prType] : null; ?></td>
                </tr> 

              </tbody>
            </table>                  
          </div>
          <br>
          <div class='row'>
            <div class='col-sm-12'>
              <table class="table">
              <tbody>
                <tr>
                  <th class="col-md-4">Requested by:</th>
                  <th class="col-md-4">Reviewed by:</th>
                  <th class="col-md-4">Verified by:</th>                  
                </tr>

                <tr>
                  <td>Done</td>                  
                  <td>Done</td>
                  <td>Done</td>
                </tr>

                <tr>
                  <td><?php echo $siteManager['userProfileFullName'];?></td>
                  <td><?php echo $clusterLead; ?></td>
                  <td>opsmanager</td>
                </tr>

                <tr>                  
                  <td>Manager</td>
                  <td>Cluster Lead</td>
                  <td>Operations Manager</td>
                </tr>                

                <tr>
                  <td><?php echo $siteName['siteName']; ?></td>
                  <td><?php echo $clusterName ?></td>
                  <td>Semenanjung Malaysia</td>
                </tr>

                </tbody>
              </table>
            </div>
          </div>

          </form>

          <footer class="panel-footer"></footer>       
     </div>
</div>