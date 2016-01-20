<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var rl = new function()
{
  this.ManagerSubmit = function()
  {
    var asked = false;

    if(!$('.row-rl-file')[0])
    {
      if(!confirm('There is no uploaded file at all. Do you still want to submit?'))
      {
        asked = true;
        return false;
      }
    }

    if(!asked && !confirm('Submit this RL?'))
      return false;

    return true;
  }

  this.approveCheck = function()
  {
    if(!confirm('Approve this RL?'))
      return false;

    return true;
  }

  this.rejectCheck = function()
  {
    if(!confirm('Reject this RL?'))
      return false;

    return true;
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
  Reconciliation List
</h3>
<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
    <header class="panel-heading">
      Reconciliation List - Slip of Payment / Bill / Receipt
    </header>
    <form class="form-inline bs-example " method='post' action='<?php echo url::base('exp/rlEditSubmit/'.$prId);?>'>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <thead>
                <tr>
                  <td width="30%"><label>Cluster  :</label> <?php echo $pr->getCluster()->clusterName; ?></td>                                            
                </tr>                 
                <tr>
                  <td width="30%"><label>Month    :</label>
                  <?php if($rl->isSubmitted()):?>
                    <?php echo date('F Y', strtotime($rl->prReconcilationSubmittedDate));?>
                  <?php else:?>
                    <?php echo date('F Y');?>
                  <?php endif;?>
                  </td>
                </tr>                 
                 <tr>
                  <td width="30%"><label>PI1M     : </label> <?php echo $pr->getSite()->siteName; ?></td>                                    
                </tr>
                <tr>
                  <td width="30%"><label>PR Number     : </label> <?php echo $pr->prNumber; ?></td>
                </tr>
                 
              </thead>
             </table>
<?php if($rl->isUpdateableBy(user())):?>
<a href="<?php echo url::base("exp/rlFileUpload/".$rl->prReconcilationID); ?>"  class='pull-right btn btn-primary' data-toggle="ajaxModal" style="color:green; float:right"> Add File</a>
<?php endif;?>

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
            <?php foreach($files as $file):
            ?>
              <tr class='row-rl-file'>
                <td><?php echo 1+$no++;?>.</td>
                <td><?php echo $file->getCategory()->expenseCategoryName;?></td>
                <td><?php $fileUrl = $file->getFileName();?>
                  <a href='<?php echo url::base('exp/rlFile/'.$file->prReconcilationFileID);?>' data-toggle='ajaxModal'><?php echo $fileUrl;?></a>
                </td>
                <td><?php echo number_format($file->prReconcilationFileAmount, 2, '.', '');?></td>
                <td><?php echo number_format($file->prReconcilationFileGst, 2, '.', '');?></td>
                <td><?php echo number_format($file->prReconcilationFileTotal, 2, '.', '');?></td>
              </tr>
            <?php endforeach;?>

            <?php /*foreach ($fileList as $key => $category):?>
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
                  <a href='<?php echo url::base("expense/editFile/".$category['purchaseRequisitionId']."/".$category['purchaseRequisitionFileId']);?>' style="margin-left:20px"  data-toggle='ajaxModal' class='fa fa-edit pull-right' style='font-size:13px;'></a>
                  <a id='delete-button' onclick='return confirm("Delete this transaction, are you sure?");' href='<?php echo url::base('expense/deleteFile/'.$category['purchaseRequisitionFileId']); ?>' class='fa fa-trash-o pull-right' style='font-size:13px;'></a>             
                  </td>
                </tr>
                
        <?php endforeach; */?>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id ='alltotal' align="right">Total Amount :</td>
                  <td><?php echo number_format($rl->getFileTotalAmount(), 2, '.', ''); ?></td>
                  <td></td>
                </tr>      
              </tbody>
            </table>                  

          <header class="panel-heading">Reconciliation List Summary </header>

            <table class="table table-striped b-t b-light">
              <tbody>
                <tr>
                  <th width="30px">No.  </th>
                  <th>Category </th>
                  <th>Total Amount (RM)</th>
                </tr>

            <?php foreach($rl->getSummary() as $data):?>
              <tr>
                <td><?php echo 1+$y++;?>.</td>
                <td><?php echo $data['category']->expenseCategoryName;?></td>
                <td><?php echo number_format($data['total'], 2, '.', '');?></td>
              </tr>
            <?php endforeach;?>
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
                  <td>

                  
                    <?php 
                    if($rl->isManagerPending() && user()->isManager()):?>
                      <a href='<?php echo url::base('exp/rlSubmit/'.$rl->prReconcilationID);?>' onclick='return rl.ManagerSubmit();' class='btn btn-primary'>Submit</a>
                    <?php else:?>
                      Done
                    <?php endif;?>
                  </td>

                  <td>
                    <?php
                    $clApproval = $rl->getLevelApproval('cl');

                    if($clApproval->isPending() && user()->isClusterLead() && $rl->isPendingFor(user())):?>
                      <a href='<?php echo url::base('exp/rlApproval/'.$rl->prReconcilationID.'/approve');?>' class='btn btn-primary' onclick='return rl.approveCheck();'>Approve</a>
                      <a href='<?php echo url::base('exp/rlRejectForm/'.$rl->prReconcilationID);?>' class='btn btn-danger' data-toggle='ajaxModal'>Reject</a>
                    <?php else:?>
                      <?php if($clApproval->isRejected()):?>
                        <a href='<?php echo url::base('exp/rlRejectionReason/'.$rl->prReconcilationID);?>' style='color: red;' data-toggle='ajaxModal'><?php echo $clApproval->getStatusLabel();?> [See Reason] 
                        </a>
                      <?php else:?>
                        <?php echo $clApproval->getStatusLabel();?>
                      <?php endif;?>
                    <?php endif;?>
                  </td>
                  <td>
                    <?php
                    $omApproval = $rl->getLevelApproval('om');

                    if($omApproval->isPending() && user()->isOperationManager() && $rl->isPendingFor(user())):?>
                      <a href='<?php echo url::base('exp/rlApproval/'.$rl->prReconcilationID.'/approve');?>' class='btn btn-primary' onclick='return rl.approveCheck();'>Approve</a>
                      <a href='<?php echo url::base('exp/rlRejectForm/'.$rl->prReconcilationID);?>' class='btn btn-danger' data-toggle='ajaxModal'>Reject</a>
                    <?php else:?>
                      <?php if($omApproval->isRejected()):?>
                        <a href='<?php echo url::base('exp/rlRejectionReason/'.$rl->prReconcilationID);?>' style='color: red;' data-toggle='ajaxModal'><?php echo $omApproval->getStatusLabel();?> [See Reason]
                        </a>
                      <?php else:?>
                      <?php echo $omApproval->getStatusLabel();?>
                      <?php endif;?>
                    <?php endif;?>
                  </td>
                  <td>
                    <?php
                    $fcApproval = $rl->getLevelApproval('fc');

                    if($fcApproval->isPending() && user()->isFinancialController() && $rl->isPendingFor(user())):?>
                      <a href='<?php echo url::base('exp/rlApproval/'.$rl->prReconcilationID.'/approve');?>' class='btn btn-primary' onclick='return rl.approveCheck();'>Approve</a>
                      <a href='<?php echo url::base('exp/rlRejectForm/'.$rl->prReconcilationID);?>' class='btn btn-danger' data-toggle='ajaxModal'>Reject</a>
                    <?php else:?>
                      <?php if($fcApproval->isRejected()):?>
                        <a href='<?php echo url::base('exp/rlRejectionReason/'.$rl->prReconcilationID);?>' style='color: red;' data-toggle='ajaxModal'><?php echo $fcApproval->getStatusLabel();?> [See Reason]
                        </a>
                      <?php else:?>
                        <?php echo $fcApproval->getStatusLabel();?>
                      <?php endif;?>
                    <?php endif;?>
                  </td>
                </tr>

                <tr>
                  <td>
                  <?php if(!$rl->isManagerPending()):?>
                  <?php echo $rl->getSubmittedUser()->getProfile()->userProfileFullName;?>
                  <?php elseif(user()->isManager()):?>
                  <?php echo user()->getProfile()->userProfileFullName;?>
                  <?php endif;?>
                  </td>
                  <td>
                  <?php if(!$clApproval->isPending()):?>
                    <?php echo $clApproval->getUserProfile()->userProfileFullName;?>
                  <?php endif;?>
                  </td>
                  <td>
                  <?php if(!$omApproval->isPending()):?>
                    <?php echo $omApproval->getUserProfile()->userProfileFullName;?>
                  <?php endif;?></td>
                  <td>
                  <?php if(!$fcApproval->isPending()):?>
                    <?php echo $fcApproval->getUserProfile()->userProfileFullName;?>
                  <?php endif;?>

                  </td>
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
                  <td colspan="4"> Disclamer : </td>
                  
                </tr>

                </tbody>
              </table>
            </div>
          </div>

          </form>

          <footer class="panel-footer"></footer>
       
     </div>
</div>
