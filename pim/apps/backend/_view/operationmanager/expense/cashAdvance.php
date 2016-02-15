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
      
      rowId = i - 2;
      nextRow = rowId -1;

    var newrow = $('<tr id='+ rowId +'><td>'+rowId+'</td>' +
      '<td><textarea rows="2" cols="100" name="item[itemRemark][' + rowId +']"></textarea></td>' + 
      '<td></td>' +  
      '<td><input type="text" size="5" class="form-control" name="item[itemPrice][' + rowId +']" /></td>' +  
      '<td><a href="#" id="remScnt" class="fa fa-times-circle"></a></td></tr>');   
      
      if(i == 4){
        $('#'+key).after(newrow);
      } else {
        $('#'+nextRow).after(newrow);
      }

      i++;
    return false;
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
textarea {margin:0; padding:0;  display:block;}
</style>

<h3 class='m-b-xs text-black'>
  Purchase Requisition
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
          <header class="panel-heading">
            REQUEST for CASH ADVANCE
          </header>
  <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submitCashAdvance/'.$prId);?>'>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
               
              <thead>
                <tr> 
                  <td class="col-md-4">Applicant's Name : </td>
                  <td colspan="5"><?php echo $siteManager['userProfileFullName'];?></td>
                </tr>

                <tr> 
                  <td class="col-md-4">Designation : </td>
                  <td colspan="5">Manager <?php echo $siteName['siteName']; ?></td>
                </tr>

                <tr>
                  <td colspan="3"><label>PURPOSE / REMARKS </label></td>

                  <?php if (count($cashadvance) == 0){  ?>
                  <td colspan="3"><?php echo form::text("selectDate","class='input-sm input-s form-control'",date('d F Y', strtotime($selectDate)));?></td>
                  <?php } else { ?>
                  <td colspan="3"><?php echo date('d F Y', strtotime($cashadvance[0][purchaseRequisitionCashAdvanceCreatedDate]));?></td> 
                  <?php } ?>
                </tr>

                <tr>        
                <?php if (count($cashadvance) == 0){  ?>
                <td colspan="2"><textarea rows="2" cols="100" name="purpose"></textarea></td>
                <?php } else { ?>
                <td colspan="2"><textarea rows="2" cols="100" name="purpose" disabled="disabled"><?php echo $cashadvance[0][purchaseRequisitionCashAdvancePurpose] ?></textarea></td>
                <?php } ?>
                  <!-- <td colspan="2"><input type="checkbox" value=""> Project Expense / General</td>
                  <td colspan="2"><input type="checkbox" value=""> Apps / Shared Service</td> -->
                  <td colspan="4"><label>For ACCOUNTS DEPARTMENT </label></td>                
                </tr>
                <tr>                  
                <td colspan="3"></td>          
                  <!-- <td colspan="2"><input type="checkbox" value=""> Chill Works / Renovation Works</td>
                  <td colspan="2"><input type="checkbox" value=""> HPU Profesional Services</td> -->
                  <td>Folio No. :</td>
                  <td></td>                        
                  <td></td>
                </tr>


                <tr>                  
                <td colspan="3"></td>          
                  <!-- <td colspan="2"><input type="checkbox" value=""> CDC Equipments</td>
                  <td colspan="2"><input type="checkbox" value=""> TELCO</td> -->
                  <td>Entry Date :</td>
                  <td></td> 
                  <td></td>
                </tr>

                 <tr>
                  <td colspan="6"></td>              
                </tr>                 
               
              </thead>
             </table>

            <table class="table table-striped b-t b-light" >
              <tbody id="p_scents">
                <tr>
                  <th>No </th>
                  <th>ITEMS / DESCRIPTION </th>                  
                  <th>GLC Code</th>
                  <th>Amount (RM)</th>
                  <th></th>
                </tr>
          
                <?php 

                if (count($cashadvance) == 0){  ?>

                <tr id = "1">                  
                  <td>1</td>
                  <td><textarea rows="2" cols="100" name="item[itemRemark][1]"></textarea></td>
                  <td></td>
                  <td><input name="item[itemPrice][1]" type="text" size="5" class="form-control" /></td>
                  <th><a href="Javascript:void(0);"  id="addScnt" onclick='requisition.select(1);' class='fa fa-plus-square-o' style="color:green; float:left; padding-right:10px"></a></th>
                </tr>


                <?php }  else {

                 foreach ($cashadvance as $key => $list):?>           

                <tr id = "1">                  
                  <td><?php echo $key + 1 ?></td>
                  <td><textarea rows="2" cols="100" name="item[itemRemark][1]" disabled="disabled"><?php echo $list[purchaseRequisitionCashDetailItem] ?></textarea></td>
                  <td></td>
                  <td><input name="item[itemPrice][1]" type="text" size="5" class="form-control" disabled="disabled" value="<?php echo $list[purchaseRequisitionCashDetailAmount] ?>"/></td>
                  <th></th>
                </tr>


                <?php endforeach;

                } ?>

                <tr>
                  <td></td>
                  <td>Ringgit Malaysia : 
                  <?php if (count($cashadvance) == 0): ?>
                  <input type="text" size="70" class="form-control" name= "amount" id=""/></td>
                  <?php else: ?>
                  <input type="text" size="70" class="form-control" name= "amount" disabled="disabled" value="<?php echo $cashadvance[0][purchaseRequisitionCashAdvanceTotal] ?>"/></td>
                  <?php endif; ?>
                  <td></td>
                  <td></td>
                  <td></td>                  
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
                  <th class="col-md-4">Verified by:</th>
                  <th class="col-md-4">AUTHORIZED APPROVAL</th>                  
                </tr>

                <tr>
                  <td>Done</td>
                  <td> 
                    <button name="submit" type="submit" class="btn btn-sm btn-default" value="1">Approved</button>                   
                    <button name="submit" type="submit" class="btn btn-sm btn-default" value="2">Reject</button>
                  </td>
                  <td></td>
                </tr>

                <tr>
                  <td><?php echo $siteManager['userProfileFullName'];?></td>
                  <td>opsmanager</td>
                  <td>finance</td>
                </tr>

                <tr>                  
                  <td>Manager</td>
                  <td>Operations Manager</td>
                  <td>Finance</td>                  
                </tr>                

                </tbody>
              </table>
            </div>
          </div>

          </form>

          <footer class="panel-footer"></footer>
       
     </div>
</div>





