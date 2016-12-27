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

var requisition = new function()
{

  this.calculate = function(key)
    {

      var result = 1.00;
      $('#itemTotalPrice'+key).attr('value', function() {
          $('.amount'+key).each(function() {
              if ($(this).val() !== '') {
                  result *= parseFloat($(this).val());
              }
          });
          return result;
      });

      var result = 0.00;
      $('#allTotal').attr('value', function() {
          $('.total').each(function() {
              if ($(this).val() !== '') {
                  result += parseFloat($(this).val());
              }
          });
          return result;
      });
    } 
}    


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
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submitRequisition/'.$prID);?>'>
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
                  <td colspan="4"><label>For Calent's use only</label></td>                        
                </tr>
                 <tr>
                  <td><?php echo $prDate; ?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>
                 <tr>
                  <td><label>Calent:</label></td>
                  <td>Budgeted:</td>           
                  <?php if ($prFile[0]['purchaseRequisitionExpenses'] == 1) { $checked1 = "checked";} ?>
<<<<<<< HEAD
                  <td colspan="2"><input name='expenses' type="checkbox" disabled="disabled" <?php echo $checked1?>>Expenses</td>
                  <?php if ($prFile[0]['purchaseRequisitionEquipment'] == 1) { $checked2 = "checked";} ?>
                  <td colspan="2"><input name='equipment' type="checkbox" disabled="disabled" <?php echo $checked2?>>Equipment</td>
=======
                  <td colspan="2"><input name='expenses' type="checkbox" disabled="disabled" <?php echo $checked1?>> Expenses</td>
                  <?php if ($prFile[0]['purchaseRequisitionEquipment'] == 1) { $checked2 = "checked";} ?>
                  <td colspan="2"><input name='equipment' type="checkbox" disabled="disabled" <?php echo $checked2?>> Equipment</td>
>>>>>>> d0dc45820c6e15278b0e0a6e146f869a71265117
                </tr>
                 <tr>
                  <td><?php echo $siteName['siteName']; ?></td>
                  <td>Addition:</td>                        
                  <?php if ($prFile[0]['purchaseRequisitionEvent'] == 1) { $checked3 = "checked";} ?>             
                  <td colspan="2"><input name='event' type="checkbox" disabled="disabled" <?php echo $checked3?>> Scheduled Event</td>
                  <?php if ($prFile[0]['purchaseRequisitionAdhocevent'] == 1) { $checked4 = "checked";} ?>
                  <td colspan="2"><input name='adhocevent' type="checkbox" disabled="disabled" <?php echo $checked4?>> Ad hoc Event</td>
                </tr>
                 <tr>
                  <td></td>
                  <td>Replacement:</td>
                  <?php if ($prFile[0]['purchaseRequisitionOther'] == 1) { $checked5 = "checked";} ?>
                  <td colspan="2"><input name='other' type="checkbox" disabled="disabled" <?php echo $checked5 ?>> Other</td>
                  <?php if ($prFile[0]['purchaseRequisitionCitizen'] == 1) { $checked6 = "checked";} ?>
                  <td colspan="2"><input name='1citizen' type="checkbox" disabled="disabled" <?php echo $checked6 ?>> 1Citizen</td>
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
                  <td><input type="text" size="5" onchange="requisition.calculate(<?php echo $key+1?>);" class="form-control amount<?php echo $key+1?>" name="item[itemQuantity][<?php echo $key+1; ?>]" id="itemQuantity" value="<?php echo $category['purchaseRequisitionDetailQuantity']; ?>"/></td>
                  <td><input type="text" size="5" onchange="requisition.calculate(<?php echo $key+1?>);" class="form-control amount<?php echo $key+1?>" name="item[itemPrice][<?php echo $key+1; ?>]" id="itemPrice" value="<?php echo $category['purchaseRequisitionDetailPrice']; ?>"/></td>
                  <td><input type="text" size="5" class="form-control total" name="item[itemTotalPrice][<?php echo $key+1; ?>]" id="itemTotalPrice<?php echo $key+1; ?>" value="<?php echo $category['purchaseRequisitionDetailTotal']; ?>"/></td>
                  <td><input type="text" class="form-control" name="item[itemRemark][<?php echo $key+1; ?>]" id="itemRemark" value="<?php echo $category['purchaseRequisitionDetailRemark']; ?>"/></td>
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
                  <td width="10%"><input type="text" size="5" class="form-control" id="allTotal" value="<?php echo $allTotal ?>"/></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr>
                  <td colspan="4">2. Balance Deposit: RM <?php echo $deposit ?> (as at 6.00pm, <?php echo date('d/m/Y ',strtotime($prDate)); ?>)</td>
                  <td colspan="4" style="background-color:#ededed"><b>Terms of Payment (For Calent's use only):</b></td>
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
                  <td>                   
                      <button name="submit" type="submit" value='1' class="btn btn-sm btn-default">Approved</button>
                      <button name="submit" type="submit" value='2' class="btn btn-sm btn-default">Reject</button>                                                       
                  </td>
                  <td></td>
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