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

/*var requisition = new function()
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
}*/

var rl = new function()
{
  this.save = function()
  {
    $("#form-rlEdit").attr('action', $("#form-rlEdit").attr('action')+'?saveOnly=true');
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
textarea {margin:0; padding:0;  display:block;}
</style>

<h3 class='m-b-xs text-black'>
  Purchase Requisition
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
          <form class="form-inline bs-example" id='form-rlEdit' method='post' action='<?php echo url::base('exp/prEditCashAdvance/'.$cashAdvanceID);?>'>
          <header class="panel-heading">
            REQUEST for CASH ADVANCE

            <?php if($isEditable):?>
              <input type='submit' class='btn btn-primary pull-right' onclick='return rl.save();' style="background: #369120;" value='Save' />
            <?php endif;?>
            <a href='<?php echo url::base('exp/prEdit/'.$pr->prID);?>' class='btn btn-primary pull-right' style='margin-right: 10px;'>PR Form</a>
          </header>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
               
              <thead>
                <tr> 
                  <td width="200px">Applicant's Name</td>
                  <td colspan="5"> : <?php echo $siteManager; ?></td>
                </tr>

                <tr> 
                  <td>Designation</td>
                  <td colspan="5"> : Manager <?php echo $siteName; ?></td>
                </tr>

                <tr>
                  <td colspan="2"><label>PURPOSE / REMARKS </label></td>
                  <td colspan="3"><?php echo date('d F Y', strtotime($pr->prDate));?></td>
                </tr>

                <tr>        
                  <td colspan="2" rowspan="3"><textarea style="height: 115px;" rows="2" cols="100" <?php echo $disabled;?> name="purpose"><?php echo $ca->prCashAdvancePurpose;?></textarea></td>
                  <td colspan="4"><label>For ACCOUNTS DEPARTMENT </label></td>                
                </tr>
                <tr>                  
                  <td>Folio No. :</td>
                  <td></td>                        
                  <td></td>
                </tr>


                <tr>                  
                  <!-- <td colspan="2"><input type="checkbox" value=""> CDC Equipments</td>
                  <td colspan="2"><input type="checkbox" value=""> TELCO</td> -->
                  <td>Entry Date :</td>
                  <td></td> 
                  <td colspan="2"></td>
                </tr>

                 <tr>
                  <td colspan="5"></td>              
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
                <?php $no = 1;?>
                <?php foreach($pr->getItems() as $item):?>
                <tr>
                  <td><?php echo $no++;?>.</td>
                  <td><?php echo $item->expenseItemName;?></td>
                  <td></td>
                  <td><?php echo $item->prItemTotal;?></td>
                </tr>
                <?php endforeach;?>
                <tr>
                  <td></td>
                  <td>Ringgit Malaysia : 
                   <input type="text" size="70" class="form-control" value='<?php echo $ca->prCashAdvanceAmount;?>' <?php echo $disabled;?> name= "amount" id=""/></td>
                  <td></td>
                  <td></td>
                  <td></td>                  
                </tr>
    

              </tbody>
            </table>      

          </div>
          <br>
          <?php $isCashAdvanceForm = true;?>
          <?php view::render('shared/exp/prApprovalPartial', compact('pr', 'isCashAdvanceForm'));?>
          <!-- <div class='row'>
            <div class='col-sm-12'>
              <table class="table">
              <tbody>
                <tr>
                  <th class="col-md-4">Requested by:</th>
                  <th class="col-md-4">Verified by:</th>
                  <th class="col-md-4">AUTHORIZED APPROVAL</th>                  
                </tr>
                <tr>
                  <td>
                    <?php if($pr->isManagerPending()):?>
                      <input type='submit' class='btn btn-primary' value='Complete Cash Advance' />
                    <?php else:?>
                      Done
                    <?php endif;?>
                  </td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?php echo $siteManager; ?></td>
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
          </div> -->

          </form>

          <footer class="panel-footer"></footer>
       
     </div>
</div>