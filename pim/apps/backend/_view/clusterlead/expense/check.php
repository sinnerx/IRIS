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

  /*$(document).on('keyup', '.amount', function (e) {

    });*/

</script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var requisition = new function()
{ 
    var i;
    var t;
    this.select = function(key)
    {           
      i = $('#p_scents tr').size() + 1;
      
      $.ajax({
          type:"GET",
          url: base_url+"requisition/listItem/"+key,
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
  Purchase Requisition
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
          <header class="panel-heading">
            Purchase Requisition Form
          </header>
  <form class="form-inline bs-example " method='post' action='<?php echo url::base('requisition/submitRequisition/');?>'>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <thead>
                <tr>
                  <td><label>Date:</label></td>
                  <td width="30%">To be completed for Capital Expenditure</td>                       
                  <td colspan="4"><label>For Calent's use only</label></td>                        
                </tr>
                 <tr>
                  <td><?php echo form::text("selectDate","class='input-sm input-s form-control'",date('d F Y', strtotime($selectDate)));?></td>
                  <td></td>
                  <td width="7%">PR No</td>
                  <td colspan="3"></td>                        
                </tr>
                 <tr>
<<<<<<< HEAD
                  <td><label>Calent:</label></td>
                  <td>Budgeted:</td>                        
                  <td colspan="2"><input type="checkbox" value="">Expenses</td>
=======
                  <td><label>CALENT:</label></td>
                  <td>Budgeted:</td>                        
                  <td colspan="2"><input type="checkbox" value=""> Expenses</td>
>>>>>>> d0dc45820c6e15278b0e0a6e146f869a71265117
                  <td colspan="2"><input type="checkbox" value=""> Equipment</td>
                </tr>
                 <tr>
                  <td><?php echo $siteName; ?></td>
                  <td>Addition:</td>                        
                  <td colspan="2"><input type="checkbox" value=""> Scheduled Event</td>
                  <td colspan="2"><input type="checkbox" value=""> Ad hoc Event</td>
                </tr>
                 <tr>
                  <td></td>
                  <td>Replacement:</td>
                  <td colspan="2"><input type="checkbox" value=""> Other</td>
                  <td colspan="2"><input type="checkbox" value=""> 1Citizen</td>
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
                  <td colspan="2"><?php echo $categories[$key]; ?> <a href="Javascript:void(0);"  id="addScnt" onclick='requisition.select(<?php echo $key ?>);' class='fa fa-plus-square-o pull-right' style="color:green; float:right"></a></td>
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

                <tr >
                  <td colspan="4">1. Current collection money: RM XXX.XX (as at 6.00pm, XX/XX/2015)</td>
                  <td></td>
                  <td width="10%"><input type="text" size="5" class="form-control" id="allTotal" /></td>
                  <td></td>
                  <td></td>
                </tr> 

                <tr >
                  <td colspan="4">2. Balance Deposit: RM XXX.XX (as at 6.00pm, XX/XX/2015)</td>
                  <td colspan="4" style="background-color:#ededed"><b>Terms of Payment (For Calent's use only):</b></td>
                </tr> 

                <tr >
                  <td colspan="4"></td>
                  <td colspan="4" bgcolor="#ededed"><b>Collection Money</b></td>
                </tr> 


              </tbody>
            </table>                  
          </div>
          
          <div class='row'>
            <div class='col-sm-12'>
              <table class="table">
              <tbody>
                <tr>
                  <th>Requested by:</th>
                  <th>Reviewed by:</th>
                  <th>Verified by:</th>
                </tr>

                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <td><?php echo $siteManager; ?></td>
                  <td>clusterlead</td>
                  <td>opsmanager</td>
                </tr>

                <tr>                  
                  <td>Manager</td>
                  <td>Cluster Lead</td>
                  <td>Operations Manager</td>
                </tr>                

                <tr>
                  <td><?php echo $siteName; ?></td>
                  <td>South, Semenanjung Malaysia</td>
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





