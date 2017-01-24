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

var pr = new function()
{
  this.approveCheck = function()
  {
    if(!confirm('Approve this PR?'))
      return false;

    return true;
  }

  this.rejectCheck = function()
  {
    if(!confirm('Reject this PR?'))
      return false;

    return true;
  }
}

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

    this.saveAndToCashAdvance = function()
    {
      return true;
    }

    this.save = function()
    {
      $("#form-prEdit").attr('action', $("#form-prEdit").attr("action").split('?')[0]+'?saveOnly=true');

      return true;
    }

    var i;
    var cache = {};
    var t;
    this.select = function(key, item)
    {           
      i = $('#p_scents tr').size() + 1;

      var newrow = $('<tr class="pr-item-row"></tr>');
      newrow.append('<td></td>');
        var select = $('<label></label>').attr('name', item ? 'existing['+item.id+'][expenseItemID]' : 'newItem[expenseItemID][]');
      newrow.append($('<td></td>').append(select));
        var description = $('<label />').attr('name', item ? 'existing['+item.id+'][itemDescription]':'newItem[itemDescription][]');
      newrow.append($('<td></td>').append(description.text(item ? item.description : '')));
        var quantity = $('<label onkeyup="requisition.calculate();" />').attr('name', item ? 'existing['+item.id+'][itemQuantity]' : 'newItem[itemQuantity][]');
      newrow.append($('<td></td>').append(quantity.text(item ? item.quantity : '')).css('width', '10%'));
        var price = $('<label onkeyup="requisition.calculate();" />').attr('name', item ? 'existing['+item.id+'][itemPrice]' : 'newItem[itemPrice][]');
      newrow.append($('<td></td>').append(price.text(item ? item.price : '')).css('width', '10%'));
        var total = $('<label />');
      newrow.append($('<td></td>').append(total.text(item ? item.total : '')).css('width', '10%'));
        var remark = $('<label  />').attr('name', item ? 'existing['+item.id+'][itemRemark]' : 'newItem[itemRemark][]');
      newrow.append($('<td></td>').append(remark.text(item ? item.remark : '')));
        var remove = $('<a href="#" id="remScnt" class="fa fa-times-circle"></a>');
      newrow.append($('<td></td>').append(remove));


      var response = $.ajax({
          type:"GET",
          // url: base_url+"expense/listItem/"+key,
          url: pim.url('exp/listItem/'+key),
          success: function(data){
        
            data = $.parseJSON(data);
            // var sel = $("#item"+key);
            // select.empty();          
            // select.append('<option value="">Please Select</option>');

            for (var t in data) {
              if (data.hasOwnProperty(t)) {
                if(item && item.itemID == t)
                {
                  // selected = 'selected';
                  select.append( data[t]);
                }
                else
                {
                  selected = '';
                  select.append('');
                }
                
                // select.append('<option '+selected+' value="' + t + '">' + data[t] + '</option>');
              }
            }

            if(disabled)
              requisition.disableForm();
          }
    });

    this.validate = function()
    {
      if(!$(".pr-item-input-category")[0])
      {
        alert('Please select at least 1 item');
        return false;
      }

      var failed = false;
      $(".pr-item-input-category, .pr-item-input-quantity, .pr-item-input-price, #allTotal").each(function(i, e)
      {
        if($(e).val() == '')
        {
          $(e).addClass('input-required');
          failed = true;
        }
      });

      if(failed)
        alert('Please complete the form.');

      return failed ? false : true;
    }
      
    /*var newrow = $('<tr class="pr-item-row"><td></td>' +
      '<td><select name="item[itemCategory][' + i +']" class="form-control itemValue' + i +' " id="item' + key +'"></select></td>' + 
      '<td><input type="text" class="form-control" name="item[itemDescription][' + i +'] " id="itemDescription' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" onkeyup="requisition.calculate(' + i +');" value="1" class="form-control pr-item-input-quantity amount' + i +'" name="item[itemQuantity][' + i +']" id="itemQuantity' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" onkeyup="requisition.calculate(' + i +');"class="form-control pr-item-input-price amount' + i +'" name="item[itemPrice][' + i +']" id="itemPrice' + i +'"/></td>' + 
      '<td width="10%"><input type="text" size="5" class="form-control pr-item-input-total total" readonly name="item[itemTotalPrice][' + i +']" id="itemTotalPrice' + i +'"/></td>' + 
      '<td><input type="text" class="form-control" name="item[itemRemark][' + i +']" id="itemRemark' + i +'"/></td>' + 
      '<td><a href="#" id="remScnt" class="fa fa-times-circle"></a></td></tr>');   */
      
      $('#'+key).after(newrow);

      if(disabled)
        requisition.disableForm();

      i++;

      return false;
    }

    this.disableForm = function()
    {
      if(!disabled)
        return;

      $('input, select').attr('disabled', true);
      $('.fa-plus-square-o').hide();
      $('.pr-item-row .fa-times-circle').hide();
    }

    this.calculate = function(key)
    {
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
          requisition.calculate();
          i--;
      }
      return false;
    });

  
}

window.onload = function () {
    window.print();
     // window.print();
    setTimeout(window.close, 0);
}
</script>
<style type="text/css">
  
  label {

      font-size: 13px;
      font-weight: normal;
  }
  .input-s-sm {

    width: 250px;
  }

  .input-required
  {
    border: 1px solid #009bff;
  }

@media print {
  * { margin: 0 !important; padding: 0 !important; }
  #controls, .footer, .footerarea{ display: none; }
  html, body {
    /*changing width to 100% causes huge overflow and wrap*/
    height:100%; 
    /*overflow: hidden;*/
    background: #FFF; 
    font-size: 9.5pt;
  }

  .template { width: auto; left:0; top:0; }
  img { width:100%; }
  li { margin: 0 0 10px 20px !important;}
}


</style>

<h3 class='m-b-xs text-black'>
  Purchase Requisition Form
</h3>

<?php echo flash::data();?>
<div class='row' style="height: 100%">
  <div class='col-sm-12'>
   <section class="panel panel-default" style="border: 1px">
          <header class="panel-heading">
            Purchase Requisition Type : <?php echo $prTerm[$pr->prType]; //form::select("prTerm1", $prTerm, "class='input-sm form-control input-s inline v-middle' onchange='requisition.setStatus();' disabled", $pr->prType, "[SELECT TYPE]"); ?>
            <?php if($isEditable):?>
            <input type='submit' style="background: #369120;" value='Save' onclick='return requisition.save();' class='btn btn-primary pull-right' />  
            <?php endif;?>
            <?php if($pr->isCashAdvance()):?>
              <a style="margin-right: 10px;" href='<?php echo url::base('exp/prEditCashAdvance/'.$pr->getCashAdvance()->prCashAdvanceID);?>' class='btn btn-primary pull-right hidden-print'>Cash Advance Form</a>
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
                  <td><?php echo date('d F Y', strtotime($selectDate));// form::text("selectDate","class='input-sm input-s form-control'",date('d F Y', strtotime($selectDate)));?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>

                 <tr>
                  <td><label>PI1M:</label></td>
                  <td>Budgeted:</td>  
          <?php foreach ($budgeted as $id => $value) { ?>
                  <td colspan="2">
                    <input name='expenditure[<?php echo $id?>]' type="checkbox" <?php if(isset($expenditures[$id])):?>checked<?php endif;?> > <?php echo $value ?>
                  </td>
          <?php }  ?>
                </tr>
                 <tr>
                  <td><?php echo $pr->getSite()->siteName;?></td>
                  <td>Addition:</td>
              <?php 
                foreach ($addition as $id => $value) { ?>
                  <td colspan="2">
                    <input name='expenditure[<?php echo $id?>]' type="checkbox" <?php if(isset($expenditures[$id])):?>checked<?php endif;?> > <?php echo $value ?>
                  </td>
              <?php 
                }  ?>
                </tr>
                 <tr>
                  <td></td>
                  <td>Replacement:</td>
              <?php 
                foreach ($replacement as $id => $value) { ?>
                  <td colspan="2">
                    <input name='expenditure[<?php echo $id?>]' type="checkbox" <?php if(isset($expenditures[$id])):?>checked<?php endif;?> > <?php echo $value ?>
                  </td>
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
                  <th width="25%">Remarks</th>
                  <th></th>
                </tr>
          
          <?php

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
                 <tr><td>&nbsp;</td></tr>        
                <tr >
                  <td colspan="4">Justification :</td>
                  <td colspan="4" bgcolor="#ededed"><b>Total (RM):</b></td>
                </tr> 
                
                <tr>
                  <td colspan="4">1. Current collection money: RM <?php echo $collection['total']; ?> (as of <?php echo $collection['date']; ?>)
                 </td>
                  <td></td>
                  <td width="10%"><?php echo $pr->prTotal;?></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr >
                  <td colspan="4">2. Balance Deposit: RM <?php echo $pr->prDeposit;?> (as of 6.00pm, <?php echo date('d F Y', strtotime($selectDate)); ?>)</td>
                  <td colspan="4" style="background-color:#ededed"><b>Terms of Payment (For Nusuara's use only):</b></td>
                </tr> 

                <tr >
                  <td colspan="4"></td>
                  <td colspan="4" bgcolor="#ededed">
                  <b><?php echo $prTerm[$pr->prType]; //form::select("prTerm",$prTerm,"class='input-sm form-control input-s inline v-middle' disabled='disabled'","[SELECT UTILITIES]"); ?></b>
                  <a id="addCashAdvance" href="<?php echo url::base('expense/addPRCashAdvance/');?>" target="_blank" style="display:none" class="btn btn-sm btn-default">Cash Advance Form</a>
                   </td>
                </tr> 


              </tbody>
            </table>                  
          </div>
          <br>
          <?php view::render('shared/exp/prApprovalPartial', compact('pr'));?>

          <footer class="panel-footer"></footer>
       
     </div>
</div>

<script type="text/javascript">

<?php if(!$isEditable): // disable everything. ?>
  $('input').attr('disabled', true);
  var disabled = true;
<?php else:?>
  var disabled = false;
<?php endif;?> 
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



