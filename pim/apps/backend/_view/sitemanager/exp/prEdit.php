<?php echo 'Form no longer used?'; die;?>
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
        onChangeDateTime:function(dp,$input){
        
        var siteID  = $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";              
        var selectDate  = $input.val() != ""?"&selectDate="+$input.val():"";    
  
      if (!$("#siteID")[0]) {
            var siteID = "<?php echo $siteID ?>";
        }

        window.location.href  = base_url+"exp/prEdit/<?php echo $pr->prID;?>?"+selectDate;   
      }
  
    });
  });

</script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var requisition = new function()
{ 
    this.setStatus = function(key)
    {   
      var prType  = $("#prTerm1").val();          
      $('#prTerm').val(prType);


      if (prType == 2){
//        $("#addCashAdvance").show();
        $("#check").remove();
        $("#submit").after($('<button id="check" name="check" type="submit" class="btn btn-sm btn-default" value="2">Next to Cash Advance</button>'));
      } else {
//        $("#addCashAdvance").hide();
        $("#check").remove();
        $("#submit").after($('<button id="check" name="check" type="submit" class="btn btn-sm btn-default" value="1">Submit</button>'));
      } 

    }

    var i;
    var cache = {};
    var t;
    this.select = function(key, item)
    {           
      i = $('#p_scents tr').size() + 1;

      var newrow = $('<tr class="pr-item-row"></tr>');
      newrow.append('<td></td>');
        var select = $('<select class="form-control itemValue'+i+'"></select>');
      newrow.append($('<td></td>').append(select));
        var description = $('<input type="text" class="form-control" />').attr('name', item ? 'existing['+item.id+'][itemDescription]':'item[itemDescription][]');
      newrow.append($('<td></td>').append(description.val(item ? item.description : '')));
        var quantity = $('<input onkeyup="requisition.calculate();" type="text" class="form-control pr-item-input-quantity" size="5" />').attr('name', item ? 'existing['+item.id+'][itemQuantity]' : 'item[itemQuantity][]');
      newrow.append($('<td></td>').append(quantity.val(item ? item.quantity : '')).css('width', '10%'));
        var price = $('<input onkeyup="requisition.calculate();" type="text" class="form-control pr-item-input-price" size="5" />').attr('name', item ? 'existing['+item.id+'][itemPrice]' : 'item[itemPrice][]');
      newrow.append($('<td></td>').append(price.val(item ? item.price : '')).css('width', '10%'));
        var total = $('<input type="text" class="form-control pr-item-input-total" readonly size="5" />');
      newrow.append($('<td></td>').append(total.val(item ? item.total : '')).css('width', '10%'));
        var remark = $('<input type="text" class="form-control" />').attr('name', item ? 'existing['+item.id+'][itemRemark]' : 'item[itemRemark][]');
      newrow.append($('<td></td>').append(remark.val(item ? item.remark : '')));
        var remove = $('<a href="#" id="remScnt" class="fa fa-times-circle"></a>');
      newrow.append($('<td></td>').append(remove));

      var response = $.ajax({
          type:"GET",
          url: base_url+"expense/listItem/"+key,
          success: function(data){
        
            data = $.parseJSON(data);
            // var sel = $("#item"+key);
            // select.empty();          
            select.append('<option value="">Please Select</option>');

            for (var t in data) {
              if (data.hasOwnProperty(t)) {
                if(item && item.itemID == t)
                {
                  selected = 'selected';
                }
                else
                {
                  selected = '';
                }
                
                select.append('<option '+selected+' value="' + t + '">' + data[t] + '</option>');                  
              }
            }
          }
    });
      
    /*var newrow = $('<tr class="pr-item-row"><td></td>' +
      '<td><select name="item[itemCategory][' + i +']" class="form-control itemValue' + i +' " id="item' + key +'"></select></td>' + 
      '<td><input type="text" class="form-control" name="item[itemDescription][' + i +'] " id="itemDescription' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" onkeyup="requisition.calculate(' + i +');" value="1" class="form-control pr-item-input-quantity amount' + i +'" name="item[itemQuantity][' + i +']" id="itemQuantity' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" onkeyup="requisition.calculate(' + i +');"class="form-control pr-item-input-price amount' + i +'" name="item[itemPrice][' + i +']" id="itemPrice' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" class="form-control pr-item-input-total total" readonly name="item[itemTotalPrice][' + i +']" id="itemTotalPrice' + i +'"/></td>' + 
      '<td><input type="text" class="form-control" name="item[itemRemark][' + i +']" id="itemRemark' + i +'"/></td>' + 
      '<td><a href="#" id="remScnt" class="fa fa-times-circle"></a></td></tr>');   */
      
      $('#'+key).after(newrow);

      i++;

    return false;
    }

    this.calculate = function(key)
    {
      /*var result = 1.00;
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
      });*/
      $('.pr-item-row').each(function()
      {
        var row = $(this);
        var quantity = parseFloat(row.find('.pr-item-input-quantity').val());
        quantity = quantity ? quantity : 0;
        var price = parseFloat(row.find('.pr-item-input-price').val());
        price = price ? price : 0;

        var total = quantity*price;
        row.find('.pr-item-input-total').val(total);
      });
      
      // update grand total
      var grandTotal = 0;
      $('.pr-item-input-total').each(function()
      {
        grandTotal += parseFloat($(this).val());
      });

      $('#allTotal').val(grandTotal);
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
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('exp/prEditSubmit/'.$pr->prID);?>'>
          <header class="panel-heading">
            Purchase Requisition Type : <?php echo form::select("prTerm1", $prTerm, "class='input-sm form-control input-s inline v-middle' onchange='requisition.setStatus();' disabled", $pr->prType, "[SELECT TYPE]"); ?>
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
                  <td><?php echo form::text("selectDate","class='input-sm input-s form-control'",date('d F Y', strtotime($selectDate)));?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>

                 <tr>
                  <td><label>PI1M:</label></td>
                  <td>Budgeted:</td>  
          <?php foreach ($budgeted as $key => $value) { ?>
                  <td colspan="2"><input name='budgeted<?php echo $key?>' type="checkbox" value="1"> <?php echo $value ?></td>
          <?php }  ?>
                </tr>

                 <tr>
                  <td><?php echo authData('site.siteName'); ?></td>
                  <td>Addition:</td>
              <?php 
                foreach ($addition as $key => $value) { ?>
                  <td colspan="2"><input name='addition<?php echo $key?>' type="checkbox" value="1"> <?php echo $value ?></td>
              <?php 
                }  ?>
                </tr>

                 <tr>
                  <td></td>
                  <td>Replacement:</td>
              <?php 
                foreach ($replacement as $key => $value) { ?>
                  <td colspan="2"><input name='replacement<?php echo $key?>' type="checkbox" value="1"> <?php echo $value ?></td>
              <?php 
                }  ?>
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
          
          <?php $selectDate = strtotime($selectDate);

              foreach ($categories as $key => $category):?>           
                <tr id = "<?php echo $key ?>">
                  <td><?php echo $key; ?></td>
                  <td colspan="2"><?php echo $categories[$key]; ?><a href="Javascript:void(0);"  id="addScnt" onclick='requisition.select(<?php echo $key ?>);' class='fa fa-plus-square-o pull-right' style="color:green; float:right"></a></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
            
        <?php endforeach; ?>  
                        
                <tr >
                  <td colspan="4">Justification :</td>
                  <td colspan="4" bgcolor="#ededed"><b>Total (RM):</b></td>
                </tr> 

                <tr>
                  <td colspan="4">1. Current collection money: RM <?php echo $collection['total'] ?> (<?php echo $collection['date']; ?>)
                 <input type="hidden" size="5" class="form-control" name="curCollection" value="<?php echo $collection['total'];?>" /></td>
                  <td></td>
                  <td width="10%"><input type="text" size="5" class="form-control" id="allTotal" name='total' value="<?php echo $pr->prTotal;?>" /></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr >
                  <td colspan="4">2. Balance Deposit: RM <input type="text" size="5" class="form-control" value='<?php echo $pr->prDeposit;?>' name="balDeposit" /> (as at 6.00pm, <?php echo date('d/m/Y ',$selectDate); ?>)</td>
                  <td colspan="4" style="background-color:#ededed"><b>Terms of Payment (For Nusuara's use only):</b></td>
                </tr> 

                <tr >
                  <td colspan="4"></td>
                  <td colspan="4" bgcolor="#ededed">
                  <b><?php echo form::select("prTerm",$prTerm,"class='input-sm form-control input-s inline v-middle' disabled='disabled'","[SELECT UTILITIES]"); ?></b>
                  <a id="addCashAdvance" href="<?php echo url::base('expense/addPRCashAdvance/');?>" target="_blank" style="display:none" class="btn btn-sm btn-default">Cash Advance Form</a>
                   </td>
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
                  <!-- manager -->
                  <td>
                    <?php if($pr->isManagerPending()):?>
                      <?php if(user()->isManager()):?>
                        <input type='submit' class='btn btn-primary' <?php if($pr->isCashAdvance()):?>value='Next to cash advance'<?php endif;?> />
                      <?php else:?>
                        Pending
                      <?php endif;?>
                    <?php else:?>
                      Done
                    <?php endif;?>
                  </td> 
                  <!-- cl -->
                  <td>
                    <?php $clApproval = $pr->getLevelApproval('cl');?>
                    <?php if($clApproval->isPending() && $pr->isClusterLeadPending() && user()->isClusterLead()):?>
                      <input type='submit' class='btn btn-primary' value='approve' />
                      <a href='<?php echo url::base('exp/prReject/'.$pr->prID);?>' class='btn btn-primary'>Disapprove</a>
                    <?php else:?>
                      <?php echo $pr->getApprovalLabel();?>
                    <?php endif;?>
                  </td>
                  <!-- om -->
                  <td></td>
                </tr>

                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>                  
                  <td>Manager</td>
                  <td>Cluster Lead</td>
                  <td>Operations Manager</td>
                </tr>                

                <tr>
                  <td><?php echo authData('site.siteName'); ?></td>
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

<script type="text/javascript">
  
<?php foreach($prItems as $prItem):?>
requisition.select(<?php echo $prItem->expenseCategoryID;?>, <?php echo json_encode($prItem->toArray(array(
      'prItemID' => 'id',
      'expenseItemID' => 'itemID',
      'prItemQuantity' => 'quantity',
      'prItemPrice' => 'price',
      'prItemDescription' => 'description',
      'prItemRemark' => 'remark',
      'prItemTotal' => 'total')));?>);

<?php endforeach;?>
requisition.setStatus();
</script>



