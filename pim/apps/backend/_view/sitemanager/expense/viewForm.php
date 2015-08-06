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

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var requisition = new function()
{ 
    this.setStatus = function(key)
    {   
      var siteID  = $("#prTerm1").val();          
      $('#prTerm').val(siteID);


      if (siteID == 2){
        $("#addCashAdvance").show();
      } else {
        $("#addCashAdvance").hide();

      } 

    }

    var i;
    var t;
    this.select = function(key)
    {           
      i = $('#p_scents tr').size() + 1;
      
      $.ajax({
          type:"GET",
          url: base_url+"expense/listItem/"+key,
          success: function(data){
        
            data = $.parseJSON(data);
            var sel = $("#item"+key);
            sel.empty();          
            sel.append('<option value="">Please Select</option>');

            for (var t in data) {
              if (data.hasOwnProperty(t)) {             
                  sel.append('<option value="' + t + '">' + data[t] + '</option>');                  
              }
            }
          }
    });
      
    var newrow = $('<tr><td></td>' +
      '<td><select name="item[itemCategory][' + i +']" class="form-control itemValue' + i +' " id="item' + key +'"></select></td>' + 
      '<td><input type="text" class="form-control" name="item[itemDescription][' + i +'] " id="itemDescription' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" onchange="requisition.calculate(' + i +');" class="form-control amount' + i +'" name="item[itemQuantity][' + i +']" id="itemQuantity' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" onchange="requisition.calculate(' + i +');"class="form-control amount' + i +'" name="item[itemPrice][' + i +']" id="itemPrice' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" class="form-control total" name="item[itemTotalPrice][' + i +']" id="itemTotalPrice' + i +'"/></td>' + 
      '<td><input type="text" class="form-control" name="item[itemRemark][' + i +']" id="itemRemark' + i +'"/></td>' + 
      '<td><a href="#" id="remScnt" class="fa fa-times-circle"></a></td></tr>');   
      
      $('#'+key).after(newrow);

      i++;
    return false;
    }

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

    //Remove button
    $(document).on('click', '#remScnt', function() {

      if (i > 2) {
          $(this).closest('tr').remove();
          i--;
      }
      return false;
    });

  
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
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/viewCashAdvance/'.$prID);?>'>
          <header class="panel-heading">
            Purchase Requisition Type : <?php echo isset($prTerm[$prFile[0]['purchaseRequisitionType']]) ? $prTerm[$prFile[0]['purchaseRequisitionType']] : null; ?>
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
                  <td><?php echo $prFile[0]['purchaseRequisitionDate']; ?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>
                 <tr>
                  <td><label>PI1M:</label></td>
                  <td>Budgeted:</td>           
                  <?php if ($prFile[0]['purchaseRequisitionExpenses'] == 1) { $checked1 = "checked";} ?>
                  <td colspan="2"><input type="checkbox" <?php echo $checked1 ?> value="1" disabled="disabled"> PI1M Expenses</td>
                  <?php if ($prFile[0]['purchaseRequisitionEquipment'] == 1) { $checked2 = "checked";} ?>
                  <td colspan="2"><input type="checkbox" <?php echo $checked2 ?> value="1" disabled="disabled"> PI1M Equipment</td>
                </tr>
                 <tr>
                  <td><?php echo $siteName['siteName']; ?></td>
                  <td>Addition:</td>                        
                  <?php if ($prFile[0]['purchaseRequisitionEvent'] == 1) { $checked3 = "checked";} ?>             
                  <td colspan="2"><input type="checkbox" <?php echo $checked3 ?> value="1" disabled="disabled"> Scheduled Event</td>
                  <?php if ($prFile[0]['purchaseRequisitionAdhocevent'] == 1) { $checked4 = "checked";} ?>
                  <td colspan="2"><input type="checkbox" <?php echo $checked4 ?> value="1" disabled="disabled"> Ad hoc Event</td>
                </tr>
                 <tr>
                  <td></td>
                  <td>Replacement:</td>
                  <?php if ($prFile[0]['purchaseRequisitionOther'] == 1) { $checked5 = "checked";} ?>
                  <td colspan="2"><input type="checkbox" <?php echo $checked5 ?> value="1" disabled="disabled"> Other</td>
                  <?php if ($prFile[0]['purchaseRequisitionCitizen'] == 1) { $checked6 = "checked";} ?>
                  <td colspan="2"><input type="checkbox" <?php echo $checked6 ?> value="1" disabled="disabled"> 1Citizen</td>
                </tr>
              </thead>
             </table>

            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="40%" colspan="2">Description</th>
                  <th width="10%">Quantity</th>
                  <th width="10%">Unit Price (RM)</th>
                  <th width="10%">Total Price (RM)</th>
                  <th>Remarks</th>
                  <th></th>
                </tr>
          
          <?php 

              foreach ($prItemList as $key => $category):?>       

                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $itemName[$category['purchaseRequisitionDetailItemId']]; ?> : </td>
                  <td><?php echo $category['purchaseRequisitionDetailDescription']; ?></td>
                  <td><?php $extractLog = model::load('expense/transaction')->extractLog($category['purchaseRequisitionDetailEdit']);    
                    
                      if ($extractLog[1] == 0) { $item1[$key+1] = "class = has-warning"; } ?>
                      <div <?php echo $item1[$key+1] ?>>
                      <input type="text" size="5" class="form-control" disabled="disabled" value="<?php echo $category['purchaseRequisitionDetailQuantity']; ?>"/>
                      </div>
                  </td>
                  <td><?php if ($extractLog[2] == 0) { $item2[$key+1] = "class = has-warning"; } ?>
                      <div <?php echo $item2[$key+1] ?>>
                      <input type="text" size="5" class="form-control" disabled="disabled" value="<?php echo $category['purchaseRequisitionDetailPrice']; ?>"/>
                      </div>
                  </td>
                  <td><?php if ($extractLog[3] == 0) { $item3[$key+1] = "class = has-warning"; } ?>
                      <div <?php echo $item3[$key+1] ?>>
                    <?php $total = $category['purchaseRequisitionDetailTotal'] + $total; /*echo $category['purchaseRequisitionDetailTotal']; */ ?>
                      <input type="text" size="5" class="form-control" disabled="disabled" value="<?php echo $category['purchaseRequisitionDetailTotal']; ?>"/>    
                      </div>
                  </td>
                  <td><?php echo $category['purchaseRequisitionDetailRemark']; ?></td>
                  <td></td>
                </tr>
            
        <?php endforeach; ?>  
                        
                <tr >
                  <td colspan="5">Justification :</td>
                  <td colspan="3" bgcolor="#ededed"><b>Total (RM):</b></td>
                </tr> 

                <tr >
                  <td colspan="5">1. Current collection money: RM <?php echo $prFile[0]['purchaseRequisitionBalance']; ?> (as at 6.00pm, <?php echo date('d/m/Y ',strtotime($prFile[0]['purchaseRequisitionDate'])); ?>)</td>
                  <td width="10%"><input type="text" size="5" class="form-control" id="allTotal" value = "<?php echo $total ?>"  disabled="disabled" /></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr>
                  <td colspan="5">2. Balance Deposit: RM <?php echo $prFile[0]['purchaseRequisitionDeposit']; ?> (as at 6.00pm, <?php echo date('d/m/Y ',strtotime($prFile[0]['purchaseRequisitionDate'])); ?>)</td>
                  <td colspan="4" style="background-color:#ededed"><b>Terms of Payment (For Nusuara's use only):</b></td>
                </tr> 

                <tr >
                  <td colspan="5"></td>
                  <td colspan="4" bgcolor="#ededed">
                  <b><?php echo isset($prTerm[$prFile[0]['purchaseRequisitionType']]) ? $prTerm[$prFile[0]['purchaseRequisitionType']] : null; ?></b>
                   <!-- <a href="<?php echo url::base('expense/addPRCashAdvance/');?>">if cash advance</a> --></td>
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
                  <td>
                    <?php if ($prFile[0]['purchaseRequisitionType'] == 2) { ?>
                      <button type="submit" name="check" value="2" class="btn btn-sm btn-default"> Next to Cash Advance</button>
                    <?php } else {  ?>
                      <button type="submit" name="check" value="1" class="btn btn-sm btn-default"> Done</button>
                    <?php  }  ?>  
                  </td>                            
                  <td><?php echo isset($clstatus) ? $clstatus : "Pending"; ?></td>
                  <td><?php echo isset($omstatus) ? $omstatus : "Pending"; ?></td>
                </tr>

                <tr>
                  <td><?php echo $siteManager; ?></td>
                  <td><?php echo $clusterLead['userProfileFullName']; ?></td>
                  <td><?php echo $opsManager['userProfileFullName']; ?></td>
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





