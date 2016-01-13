<div class='row'>
  <div class='col-sm-12'>
    <table class="table">
    <tbody>
      <tr>
        <th class="col-md-4">Requested by:</th>
        <th class="col-md-4">Reviewed by:</th>
        <th class="col-md-4">Verified by:</th>                  
      </tr>
      <!-- approval layer -->
      <tr>
         <!-- manager -->
        <td>
          <?php if($pr->isManagerPending()):?>
            <?php if(user()->isManager()):?>
              <?php if($isCashAdvanceForm):?>
                <input type='submit' class='btn btn-primary' value='Complete Cash Advance' />
              <?php else:?>
                <input type='submit' class='btn btn-primary' <?php if($pr->isCashAdvance()):?>value='Next to cash advance'<?php endif;?> />
              <?php endif;?>
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
            <input type='submit' class='btn btn-primary' value='Approve' onclick='return pr.approveCheck();' />
            <a href='<?php echo url::base('exp/prRejectForm/'.$pr->prID);?>' data-toggle='ajaxModal' class='btn btn-danger'>Reject</a>
          <?php else:?>
            <?php if($clApproval->isRejected()):?>
              <a href='<?php echo url::base('exp/prRejectionReason/'.$pr->prID);?>' style='color: red;' data-toggle='ajaxModal'><?php echo $clApproval->getStatusLabel();?></a>
            <?php else:?>
              <?php echo $clApproval->getStatusLabel();?>
            <?php endif;?>
          <?php endif;?>
        </td>
        <!-- om -->
        <td>
          <?php $omApproval = $pr->getLevelApproval('om');?>
          <?php if($omApproval->isPending() && $pr->isOperationManagerPending() && user()->isOperationManager()):?>
            <input type='submit' class="btn btn-primary" value='Approve' onclick='return pr.approveCheck();' />
            <a  href='<?php echo url::base('exp/prRejectForm/'.$pr->prID);?>' data-toggle='ajaxModal' class='btn btn-danger'>Reject</a>
          <?php else:?>
            <?php if($omApproval->isRejected()):?>
              <a href='<?php echo url::base('exp/prRejectionReason/'.$pr->prID);?>' style='color: red;' data-toggle='ajaxModal'><?php echo $omApproval->getStatusLabel();?></a>
            <?php else:?>
              <?php echo $omApproval->getStatusLabel();?>
            <?php endif;?>
          <?php endif;?>
        </td>
      </tr>

      <!-- names -->
      <tr>
        <td><?php echo $pr->getRequestingUser()->getProfile()->userProfileFullName;?></td>
        <td><?php echo $clApproval->getUserProfile()->userProfileFullName ? : '-';?></td>
        <td><?php echo $omApproval->getUserProfile()->userProfileFullName ? : '-';?></td>
      </tr>

      <!-- level label -->
      <tr>                  
        <td>Manager</td>
        <td>Cluster Lead</td>
        <td>Operation Manager</td>
      </tr>                

      <tr>
        <td><?php echo $pr->getSite()->siteName; ?></td>
        <td></td>
        <td></td>
      </tr>

      </tbody>
    </table>
  </div>
</div>