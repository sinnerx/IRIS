<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var site = new function()
{
  var context = this;
  this.overview = new function()
  {
    this.updateDate = function()
    {
      window.location.href = pim.base_url+"expense/reconciliation/"+$("#selectMonth").val()+"/"+$("#selectYear").val();
    }    
  };
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

  table th {

width: auto !important;
}
</style>

<h3 class='m-b-xs text-black'>
  Reconciliation List
</h3>
<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
          <header class="panel-heading">
            Reconciliation List - Slip of Payment / Bill / Receipt
          </header>
  <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/fcClose/'.$prId);?>'>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <thead>
                <tr>
                  <td width="30%"><label>Cluster  :</label> <?php echo $clusterName ?></td>                                            
                </tr>                 
                <tr>
                  <td width="30%"><label>Month    :</label>
                  <?php echo model::load("helper")->monthYear("monthE")[$selectMonth]."  ".model::load("helper")->monthYear("year")[$selectYear]; ?>
                  </td>
                </tr>                 
                 <tr>
                  <td width="30%"><label>PI1M     : </label> <?php echo $siteName['siteName'] ?></td>  
                  <input type="hidden" size="5" class="form-control" name="siteID" value="<?php echo $siteName['siteID'] ?>" />

                  
                </tr>
                <tr>
                  <td width="30%"><label>PR Number     : </label> <?php echo $prNo ?></td>                                    
                </tr>
                 
              </thead>
             </table>

            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th>No.  </th>
                  <th>Category </th>
                  <th>Particular</th>
                  <th>Amount (RM)</th>
                  <th>GST Amount (RM)</th>
                  <th>Total Amount (RM)</th>
                  <th></th>
                </tr>

            <?php foreach ($fileList as $key => $category):?>
                <tr id = "<?php echo $key ?>">
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo $category['purchaseRequisitionCategoryName']; ?></td>
                  <td><?php $file_name  = $category['purchaseRequisitionFileName'].".".$category['purchaseRequisitionFileExt']; ?>                  
                      <a href="<?php echo url::base("expense/viewFile/".$category['purchaseRequisitionFileId']); ?>"  data-toggle="ajaxModal" style="color:green;"><?php echo $file_name  ?></a>
                  </td>
                  <td><?php echo $category['purchaseRequisitionFileAmount']; ?></td>  
                  <td><?php echo $category['purchaseRequisitionFileGst']; ?></td>  
                  <td class="amount"><?php echo $category['purchaseRequisitionFileTotal']; ?></td>  
                  <td>
                  <!-- <a href='<?php echo url::base("expense/editFile/".$category['purchaseRequisitionId']."/".$category['purchaseRequisitionFileId']);?>' style="margin-left:20px"  data-toggle='ajaxModal' class='fa fa-edit pull-right' style='font-size:13px;'></a>
                  <a id='delete-button' onclick='return confirm("Delete this transaction, are you sure?");' href='<?php echo url::base('expense/deleteFile/'.$category['purchaseRequisitionFileId']); ?>' class='fa fa-trash-o pull-right' style='font-size:13px;'></a>              -->
                  </td>
                </tr>
                
        <?php endforeach; ?>
                 
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id ='alltotal' align="right">Total Amount :</td>
                  <td><?php echo number_format($totalAmount['totalAmount'],2,'.',''); ?></td>
                  <input type="hidden" size="5" class="form-control" name="total" value="<?php echo number_format($totalAmount['totalAmount'],2,'.','') ?>" />
                  <td></td>
                </tr>      
              </tbody>
            </table>                  

          <header class="panel-heading">Reconciliation List Summary </header>

            <table class="table table-striped b-t b-light">
              <tbody>
                <tr>
                  <th>No.  </th>
                  <th>Category </th>
                  <th>Total Amount (RM)</th>
                </tr>

            <?php foreach ($rlSummary as $key => $category):?>
                <tr id = "<?php echo $key ?>">
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo $category['purchaseRequisitionCategoryName']; ?></td> 
                  <td><?php echo number_format($category['amount'],2,'.',''); ?></td>
                </tr>
                
        <?php endforeach; ?>
                 
              </tbody>
            </table>          

          </div>
          <br>
          <div class='row'>
            <div class='col-sm-12'>
              <table class="table" border='0'>
              <tbody>
                <tr>
                  <th class="col-md-3">Prepared by:</th>
                  <th class="col-md-3">Verified by:</th>
                  <th class="col-md-3">Approved by:</th>                
                  <th class="col-md-3">Closed by:</th>
                </tr>

                <tr>
                  <td>Done</td>                  
                  <td>Done</td>
                  <td>Done</td>
                  <td>

                  <?php if ($fcApprove == "1") {  ?>

                  Done

                  <?php } else { ?>

                  <button id="check" name="check" type="submit" class="btn btn-sm btn-default" value="2">Close</button>

                  <?php } ?>
                  </td>
                    
                </tr>

                <tr>
                  <td><?php echo $siteManager['userProfileFullName'];?></td>
                  <td><?php echo $clusterLead['userProfileFullName']; ?></td>
                  <td><?php echo $opsManager; ?></td>
                  <td>finance</td>
                </tr>

                <tr>                  
                  <td>Manager</td>
                  <td>Cluster Lead</td>
                  <td>Operations Manager</td>
                  <td>Financial Controller</td>
                </tr>                

                <tr>
                  <td><?php echo $siteName['siteName']; ?></td>
                  <td><?php echo $clusterName ?></td>
                  <td>Semenanjung Malaysia</td>
                  <td></td>
                </tr>

                <tr>
                  <th colspan="4"> Disclamer : This is a computer-generated document and it does not require a signature</th>                  
                </tr>

                </tbody>
              </table>
            </div>
          </div>

          </form>

          <footer class="panel-footer"></footer>
       
     </div>
</div>
