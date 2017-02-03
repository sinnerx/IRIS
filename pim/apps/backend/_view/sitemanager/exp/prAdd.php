<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

<?php if(date('Y-m') == '2016-01'): // enable backdating ?>
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

        window.location.href  = base_url+"exp/prAdd?"+selectDate;   
      }
    });
  });
<?php endif;?>

</script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var requisition = new function()
{ 
  this.month = <?php echo $month;?>;
  this.year = <?php echo $year;?>;

  this.showExistingWarning = function()
  {
    var type = {2: 'Cash Advance'}[$('#prTerm1').val()];
    $('.pim-warning').show().html('This site already made a '+type+' PR for this month already.');
  }

  this.setStatus = function(key)
  {   
    var prType  = $("#prTerm1").val();          
    $('#prTerm').val(prType);
    $("#submit").html('');

    $.ajax({url: pim.url('exp/prAddCheck/'+prType+'/'+requisition.month+'/'+requisition.year), dataType: 'json'}).done(function(result)
    {
      if(result.exists == true)
        return requisition.showExistingWarning();

      if (prType == 2){
//        $("#addCashAdvance").show();
        $("#check").remove();
        $("#submit").html($('<button id="check" name="check" type="submit" class="btn btn-sm btn-default" value="2">Proceed to Cash Advance</button>'));
      } else {
//        $("#addCashAdvance").hide();
        $("#check").remove();
        $("#submit").html($('<button id="check" name="check" type="submit" class="btn btn-sm btn-default" value="1">Submit</button>'));
      } 
    });
  }

  var i;
  var t;
  this.select = function(key)
  {           
    i = $('#p_scents tr').size() + 1;
    
    $.ajax({
        type:"GET",
        url: base_url+"exp/listItem/"+key,
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
    
  var newrow = $('<tr class="pr-item-row"><td></td>' +
    '<td><select name="item[itemCategory][' + i +']" class="form-control pr-item-input-category" id="item' + key +'"></select></td>' + 
    '<td><input type="text" class="form-control" name="item[itemDescription][' + i +'] " /></td>' + 
    '<td width="10%"><input type="text" size="5" onkeyup="requisition.calculate(' + i +');" value="1" class="form-control pr-item-input-quantity" name="item[itemQuantity][' + i +']" id="itemQuantity' + i +'"/></td>' + 
    '<td width="10%"><input type="text" size="5" onkeyup="requisition.calculate(' + i +');"class="form-control pr-item-input-price" name="item[itemPrice][' + i +']"/></td>' + 
    '<td width="10%"><input type="text" size="5" class="form-control pr-item-input-total total" readonly name="item[itemTotalPrice][' + i +']" /></td>' + 
    '<td><input type="text" class="form-control" name="item[itemRemark][' + i +']" id="itemRemark' + i +'"/></td>' + 
    '<td><a href="#" id="remScnt" class="fa fa-times-circle"></a></td></tr>');   
    
    $('#'+key).after(newrow);

    i++;
  return false;
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

  .input-required
  {
    border: 1px solid #009bff;
  }

</style>

<h3 class='m-b-xs text-black'>
  Purchase Requisition Form
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='alert alert-danger pim-warning' style="display: none;">

  </div>
   <section class="panel panel-default">
   <form onsubmit='return requisition.validate();' class="form-inline bs-example " method='post' action='<?php echo url::base('exp/prAddSubmit/');?>'>
          <header class="panel-heading">
            Purchase Requisition Type : <?php echo form::select("prTerm1", $prTerm, "class='input-sm form-control input-s inline v-middle' onchange='requisition.setStatus();'","[SELECT UTILITIES]"); ?>
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
                  <td><?php echo form::text("selectDate","readonly class='input-sm input-s form-control'",date('d F Y', strtotime($selectDate)));?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>

                 <tr>
                  <td><label>PI1M:</label></td>
                  <td>Budgeted:</td>  
          <?php foreach ($budgeted as $id => $value) { ?>
                  <td colspan="2"><input name='expenditure[budgeted][<?php echo $id?>]' type="checkbox" value="1"> <?php echo $value ?></td>
          <?php }  ?>
                </tr>

                 <tr>
                  <td><?php echo authData('site.siteName'); ?></td>
                  <td>Addition:</td>
              <?php 
                foreach ($addition as $id => $value) { ?>
                  <td colspan="2"><input name='expenditure[addition][<?php echo $id?>]' type="checkbox" value="1"> <?php echo $value ?></td>
              <?php 
                }  ?>
                </tr>

                 <tr>
                  <td></td>
                  <td>Replacement:</td>
              <?php 
                foreach ($replacement as $id => $value) { ?>
                  <td colspan="2"><input name='expenditure[replacement][<?php echo $id?>]' type="checkbox" value="1"> <?php echo $value ?></td>
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
                  <td colspan="4">1. Current collection money: RM <input type='text' size='5' class='form-control' name='curCollection' value='<?php echo $currentCollection ?>' /> (as of 6.00pm, <?php echo date('d F Y',$selectDate); ?>)
                 </td>
                  <td></td>
                  <td width="10%"><input type="text" size="5" class="form-control" id="allTotal" name='total' /></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr >
                  <td colspan="4">2. Balance Deposit: RM <input type="text" size="5" class="form-control" name="balDeposit" value='<?php echo $balanceDeposit ?>' /> (as of 6.00pm, <?php echo date('d F Y',$selectDate); ?>)</td>
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
                  <td><div id='submit'></div></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <td><?php echo authData('user.userProfileFullName'); ?></td>
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

