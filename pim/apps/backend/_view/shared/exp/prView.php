<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

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
            Purchase Requisition Type : <?php echo $pr->getTypeLabel();?>
            <?php if($pr->isCashAdvance()):?>
              <a href='<?php echo url::base('exp/prEditCashAdvance/'.$pr->getCashAdvance()->prCashAdvanceID);?>' class='btn btn-primary pull-right'>Cash Advance Form</a>
            <?php endif;?>
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
                  <td colspan="3"><?php echo $pr->prNumber;?></td>                        
                </tr>
                 <tr>
                  <td><label>PI1M:</label></td>
                  <?php $expenditureChecked = function($id) use($expenditures)
                  {
                    return isset($expenditures[$id]) ? 'checked' : '';
                  };?>
                  <td>Budgeted:</td>
                  <?php foreach($budgeted as $id => $value):?>
                    <td colspan="2"><input type="checkbox"  value="1" <?php echo $expenditureChecked($id);?> disabled="disabled"><?php echo $value;?></td>
                  <?php endforeach;?>
                </tr>
                 <tr>
                  <td><?php echo $pr->getSite()->siteName; ?></td>
                  <td>Addition:</td>
                  <?php foreach($addition as $id => $value):?>
                    <td colspan="2"><input type="checkbox"  value="1" <?php echo $expenditureChecked($id);?>  disabled="disabled"><?php echo $value;?></td>
                  <?php endforeach;?>
                </tr>
                 <tr>
                  <td></td>
                  <td>Replacement:</td>
                  <?php foreach($replacement as $id => $value):?>
                    <td colspan="2"><input type="checkbox"  value="1" <?php echo $expenditureChecked($id);?>  disabled="disabled"><?php echo $value;?></td>
                  <?php endforeach;?>
                </tr>
              </thead>
             </table>

            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="40%" colspan="2">Description</th>
                  <th style="width: 10%;">Quantity</th>
                  <th style="width: 10%;">Unit Price (RM)</th>
                  <th style="width: 10%;">Total Price (RM)</th>
                  <th>Remarks</th>
                  <th></th>
                </tr>
          <?php $no = 1;?>
          <?php foreach($prItems as $category => $categoryPrItems):?>
            <?php foreach($categoryPrItems as $prItem):?>
            <tr>
              <td><?php echo $no++;?>.</td>
              <td><?php echo $prItem->expenseItemName;?> </td>
              <td><?php echo $prItem->prItemDescription;?></td>
              <td><?php echo form::text('prItemQuantity', 'size="5" disabled class="form-control"', $prItem->prItemQuantity);?></td>
              <td><?php echo form::text('prItemPrice', 'size="5" disabled class="form-control"', $prItem->prItemPrice);?></td>
              <td><?php echo form::text('prItemTotal', 'size="5" disabled class="form-control"', $prItem->prItemTotal);?></td>
              <td><?php echo $prItem->prItemRemark;?></td>
            </tr>
            <?php endforeach;?>
          <?php endforeach;?>
                <tr >
                  <td colspan="5">Justification :</td>
                  <td colspan="3" bgcolor="#ededed"><b>Total (RM):</b></td>
                </tr> 

                <tr >
                  <td colspan="5">1. Current collection money: RM <?php echo $pr->prBalance; ?> (as at <?php echo date('g:ia d/m/Y', strtotime($pr->prBalanceDate));?>)</td>
                  <td width="10%"><input type="text" size="5" class="form-control" id="allTotal" value = "<?php echo $pr->prTotal;?>"  disabled="disabled" /></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr>
                  <td colspan="5">2. Balance Deposit: RM <?php echo $pr->prDeposit ? : 0; ?> (as at <?php echo date('g:ia d/m/Y ',strtotime($pr->prUpdatedDate)); ?>)</td>
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
                    <?php if($pr->isManagerPending()):?>
                      Pending
                    <?php else:?>
                      Done
                    <?php endif;?></td>                            
                  <td><?php $clApproval = $pr->getLevelApproval('cl');?>
                    <?php if($clApproval->isPending() && $pr->isClusterLeadPending() && user()->isClusterLead()):?>
                      <a href='<?php echo url::base('exp/prApprove');?>'>Approve</a>
                      <a href='<?php echo url::base('exp/prReject');?>'>Reject</a>
                    <?php else:?>
                      <?php echo $clApproval->getStatusLabel();?>
                    <?php endif;?>
                  </td>
                  <td><?php $omApproval = $pr->getLevelApproval('om'); echo $omApproval->getStatusLabel();?></td>
                </tr>
                <tr>
                  <td>Site Manager</td>
                  <td>Cluster Lead</td>
                  <td>Operation Manager</td>
                </tr>
                <tr>                  
                  <td><?php echo $pr->getRequestingUser()->getProfile()->userProfileFullName;?></td>
                  <td>
                    <?php if($clApproval->isPending()):?>
                      <?php if(user()->isClusterLead()):?>
                        
                      <?php endif;?>
                    <?php else:?>
                      <?php echo $clApproval->getUserProfile()->userProfileFullName ? : '-';?>
                    <?php endif;?>
                  </td>
                  <td><?php echo $omApproval->getUserProfile()->userProfileFullName ? : '-';?></td>
                </tr>                

                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                </tbody>
              </table>
            </div>
          </div>

          </form>

          <footer class="panel-footer"></footer>
       
     </div>
</div>





