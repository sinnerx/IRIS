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

    if(!$('.is-uploaded')[0])
      return alert('You need to have at least one receipt uploaded in order to submit an RL');

    if(!this.hasReconciledItems())
      return alert('You need to have at least one item reconciled.');

    if($('.rl-file-upload-button')[0])
    {
      var catNames = [];

      // $(".rl-file-upload-button").each(function(i, e)
      // {
      //   var id = $(this).data('rlcategoryid');

      //   catNames.push($("#row-rl-category-"+id).find('.rl-category-name').html().trim());
      // });
      
      if(!confirm('Missing receipt for category '+catNames.join(', ')+'. Only category with related receipt uploaded will be reconciled. Do you still want to continue?'))
      {
        asked = true;
        return false;
      }
    }

    if(!this.validateInput())
      return alert('Please complete the inputs.');

    /*if(!this.validateAmounts())
      return alert('The amounts do no tally with receipt, please fix before submitting.');*/

    if(!asked && !confirm('Submit this RL?'))
      return false;

    $("#rl-form").submit();

    return true;
  }

  this.hasReconciledItems = function()
  {
    var result = false;
    $('.rl-item').each(function(i, e)
    {
      if($(e).find('input[type=checkbox]')[0])
      {
        if($(e).find('input[type=checkbox]')[0].checked)
          result = true;
      }
      else
      {
        result = true;
      }
    });

    return result;
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

  this.validateAmounts = function()
  {
    var validation = true;

    // clear validation.
    $('.amount-not-tally').removeClass('amount-not-tally');

    $('.row-rl-category').each(function(i, e)
    {
      if(!$(this).hasClass('is-uploaded'))
        return;

      var catId = $(this).data('categoryid');

      // calculate each amounts (amount, gst, total)
      var amount = Number($(this).find('.file-amount').val());
      var gst = Number($(this).find('.file-gst').val());
      var total = Number($(this).find('.file-total').val());

      var rowItemCategory = $(".rl-item-category-"+catId);

      var inputItemAmount = rowItemCategory.find('.item-amount');
      var inputItemGst = rowItemCategory.find('.item-gst');
      var inputItemTotal = rowItemCategory.find('.item-total');
      var inputItemReconciled = rowItemCategory.find('.item-reconciled');

      var itemAmounts = 0, itemGsts = 0, itemTotals = 0;
      var inputItemAmounts = [], inputItemGsts = [], inputItemTotals = [];

      inputItemAmount.each(function(i, e)
      {
        if(!inputItemReconciled[i])
          return;

        if(!inputItemReconciled[i].checked)
          return;

        inputItemAmounts.push(inputItemAmount[i]);
        inputItemGsts.push(inputItemGst[i]);
        inputItemTotals.push(inputItemTotal[i]);

        itemAmounts += Number($(inputItemAmount[i]).val());
        itemGsts += Number($(inputItemGst[i]).val());
        itemTotals += Number($(inputItemTotal[i]).val());
      });

      if(amount != itemAmounts || gst != itemGsts || total != itemTotals)
      {
        validation = false;

        if(amount != itemAmounts)
        {
          for(var i = 0; i < inputItemAmounts.length; i++)
            $(inputItemAmounts[i]).addClass('amount-not-tally');

          $(e).find('.file-amount').addClass('amount-not-tally');
          // $(rowItemCategory).find('.item-amount').addClass('amount-not-tally');
        }

        if(gst != itemGsts)
        {
          for(var i = 0; i < inputItemGsts.length; i++)
            $(inputItemGsts[i]).addClass('amount-not-tally');

          $(e).find('.file-gst').addClass('amount-not-tally');
          // $(rowItemCategory).find('.item-gst').addClass('amount-not-tally');
        }

        if(total != itemTotals)
        {
          for(var i = 0; i < inputItemTotals.length; i++)
            $(inputItemTotals[i]).addClass('amount-not-tally');

          $(e).find('.file-total').addClass('amount-not-tally');
          // $(rowItemCategory).find('.item-total').addClass('amount-not-tally');
        }
      }
    });

    return validation;
  }

  this.deleteItem = function(id)
  {
    $("#rl-item-"+id).addClass('deleted');
  }

  this.reconcileChange = function(id, checked)
  {
    if(checked)
      $("#rl-item-reconciled-"+id).attr('data-original-title', 'Reconciled').parent().find('.tooltip-inner').html('Reconciled');
    else
      $("#rl-item-reconciled-"+id).attr('data-original-title', 'Not reconciled').parent().find('.tooltip-inner').html('Not reconciled');

    this.reconcilationInputUpdate();
    this.amountUpdate();
  }

  this.amountUpdate = function()
  {
    // calculate amounts.
    var grandTotal = 0;

    $('.rl-item').each(function(i, e)
    {
      if($(e).find('.item-reconciled')[0])
      {
        if(!$(e).find('.item-reconciled')[0].checked)
          return;
      }

      var e = $(e);

      var total = Number(e.find('.item-amount').val()) + Number(e.find('.item-gst').val());
      e.find('.item-total').val(total);

      grandTotal += total;
    });

    $("#rl-total").html(grandTotal);
  }

  this.reconcilationInputUpdate = function()
  {
    // disable all reconcilation input for non reconciled items.
    $('.item-reconciled').each(function(i, e)
    {
      var tr = $(e).parent().parent();
      
      tr.find('input').removeAttr('readonly');

      if(!e.checked || $(e).attr(''))
        tr.find('input[type=text]').attr('readonly', true);
    });


    $('.row-rl-category').each(function(i, e)
    {
      if(!$(e).hasClass('is-uploaded'))
      {
        var catId = $(e).data('categoryid');

        var tr = $('.rl-item-category-'+catId);
        tr.find('input').attr('disabled', true);
        tr.find('input[type=checkbox]').each(function(i, e)
        {
          e.checked = false;
        });
      }
    });
  }

  this.removeReceipt = function(fileID)
  {
    if(!confirm('Remove this receipt?'))
      return false;

    pim.redirect('exp/rlRemoveReceipt/'+fileID);
  }

  this.addItem = function(categoryId, categoryRowNum)
  {
    var totalItems = $('.rl-item-category-'+categoryId).length;

    var lastRow = $($('.rl-item-category-'+categoryId)[$('.rl-item-category-'+categoryId).length-1]);

    var tr = $("<tr></tr>").addClass('rl-item-category-'+categoryId+' row-new-item rl-item')
      .append($('<td></td>').html(categoryRowNum+'.'+(totalItems+1)))
      .append($('<td></td>').append($('<input />').attr('name', 'newItem[name]['+categoryId+'][]').addClass('form-control item-name')))
      .append($('<td></td>').append($('<input />').attr('name', 'newItem[amount]['+categoryId+'][]').addClass('form-control item-amount').val(0)))
      .append($('<td></td>').append($('<input />').attr('name', 'newItem[gst]['+categoryId+'][]').addClass('form-control item-gst').val(0)))
      .append($('<td></td>').append($('<input />').attr('name', 'newItem[total]['+categoryId+'][]').addClass('form-control item-total').val(0)))
      .append($('<td></td>').append($('<a class="i i-cross2"></a>').attr('href', 'javascript: void(0);').click(function(){ $(this).parent().parent().remove(); })));

    lastRow.after(tr);

    this.applyKeyupEvent();
  }

  this.validateInput = function()
  {
    var result = true;

    $('.rl-item input').each(function(i, e)
    {
      if($(e).val() == '')
        result = false;
    });

    return result;
  }

  this.applyKeyupEvent = function()
  {
    $('.rl-item input').keyup(function()
    {
      rl.amountUpdate();
    });
  }

  this.removeItem = function(itemID)
  {
    if(!confirm('Remove this item?'))
      return;

    pim.redirect('exp/rlRemoveItem/'+itemID);
  }
}

$(document).ready(function()
{
  rl.applyKeyupEvent();
});

</script>
<style type="text/css">
  
  label {

      font-size: 13px;
      font-weight: bold;
  }
  .input-s-sm {

    width: 250px;
  }

  .rl-item.deleted
  {
    background: red;
  }

  .receipt-amount
  {
    border: 1px solid #727272;
  }

  .amount-not-tally
  {
    border: 1px solid red;
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
    <form class="form-inline bs-example " id='rl-form' method='post' action='<?php echo url::base('exp/rlSubmit/'.$rl->prReconcilationID);?>'>
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
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th>No.  </th>
                  <th>Particular</th>
                  <th>Amount without GST (RM)</th>
                  <th>GST Amount (RM)</th>
                  <th>Total Amount (RM)</th>
                  <th style="float:right; background-color: white">
                    <?php if($rl->isEditable()): ?>
                    <a data-rlcategoryid='<?php echo $rlCategory->prReconcilationCategoryID;?>' href='<?php echo url::base("exp/rlFileUpload/".$rl->prReconcilationID.'/'); ?>' class='rl-file-upload-button fa fa-upload' title='Upload receipt' data-toggle='ajaxModal'></a>
                    <?php endif;?>
                  </th>
                </tr>
            <?php $catNo = 1;?>
            <?php foreach($rl->getCategories() as $rlCategory):
            if(!$rlCategory->isReconciled() && !$rl->isEditable())
              continue;
            ?>
            <tr class='row-rl-category <?php if($rlCategory->isUploaded()):?>is-uploaded<?php endif;?>' id='row-rl-category-<?php echo $rlCategory->prReconcilationCategoryID;?>' data-categoryid='<?php echo $rlCategory->prReconcilationCategoryID;?>'>
              <td><?php echo 1+$no++;?></td>
              <td <?php if(!$rlCategory->isUploaded()):?>colspan='4'<?php endif;?>>
                <b class='rl-category-name'><?php echo $rlCategory->expenseCategoryName;?> <?php if($rlCategory->isUploaded()):?>(Uploaded) <?php endif;?></b> 
              </td>

              <td colspan="5" style="text-align: right;">
              <?php if($rlCategory->isUploaded()):?>
              <?php if($rl->isEditable()):?>
              <a class='fa fa-plus' href='javascript: void(0);' onclick='rl.addItem(<?php echo $rlCategory->prReconcilationCategoryID;?>, <?php echo $catNo;?>);'></a>
              <?php endif;?>
              <?php endif;?>
              <?php if(!$rlCategory->isUploaded()):?>
                <?php if($rl->isEditable()):
                //.$rlCategory->prReconcilationCategoryID?>
               
                <?php endif;?>
              <?php else:?>
                <a class='fa fa-file' title='Preview Receipt' href="<?php echo url::base('exp/rlFile/'.$rlCategory->getFile()->prReconcilationFileID);?>"  data-toggle="ajaxModal"></a>
                <?php if($rl->isEditable()):?>
                <a class='i i-cross2' onclick='rl.removeReceipt(<?php echo $rlCategory->getFile()->prReconcilationFileID;?>);' href='javascript: void(0);'></a>
                <?php endif;?>
              <?php endif;?>
              </td>
            </tr>
              <?php foreach($rlCategory->getItems() as $item):
              if(!$item->isReconciled() && !$rl->isEditable())
                continue;
              ?>
                <tr class='rl-item rl-item-category-<?php echo $rlCategory->prReconcilationCategoryID;?>' id='rl-item-<?php echo $item->prReconcilationItemID;?>'>
                  <td><?php echo ($no).'.'.(1+$no2++);?></td>
                  <td><?php echo $item->prReconcilationItemName;?></td>
                  <td><?php echo form::text('prReconcilationItemAmount['.$item->prReconcilationItemID.']', 'class="item-amount form-control"', $item->prReconcilationItemAmount);?></td>
                  <td><?php echo form::text('prReconcilationItemGst['.$item->prReconcilationItemID.']', 'class="item-gst form-control"', $item->prReconcilationItemGst);?></td>
                  <td><?php echo form::text('prReconcilationItemTotal['.$item->prReconcilationItemID.']', 'class="item-total form-control"', $item->prReconcilationItemTotal);?></td>
                  <td>
                    <?php if(!$item->isNonPR()):?>
                    <input class='item-reconciled' id='rl-item-reconciled-<?php echo $item->prReconcilationItemID;?>' type='checkbox' data-toggle='tooltip' data-placement='top' onchange='rl.reconcileChange(<?php echo $item->prReconcilationItemID;?>, this.checked);' data-original-title='<?php if($item->isReconciled()):?>Reconciled<?php else:?>Not reconciled<?php endif;?>' name='prReconcilationReconciled[<?php echo $item->prReconcilationItemID;?>]' <?php if($item->isReconciled()):?>checked<?php endif;?> />
                    <?php else:?>
                      <?php if($rl->isEditable()):?>
                      <a href='javascript: rl.removeItem(<?php echo $item->prReconcilationItemID;?>);' class='i i-cross2'></a>
                      <?php endif;?>
                    <?php endif;?>
                  </td>
                </tr>
              <?php endforeach;?>
              <?php $catNo++;?>
            <?php endforeach;?>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id ='alltotal' align="right">Total Amount :</td>
                  <td><span id='rl-total'><?php echo number_format($rl->getTotal(), 2, '.', ''); ?></span></td>
                  <td></td>
                </tr>      
              </tbody>
            </table>                  

          <?php /*<header class="panel-heading">Reconciliation List Summary </header>

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
            </table>*/?>
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
                   // var_dump($rl->getLevelApproval('sm'));
                    if($rl->isManagerPending() && user()->isManager()):?>
                      <a href='javascript: void(0);' onclick='return rl.ManagerSubmit();' class='btn btn-primary'>Submit</a>
                    <?php else:?>
                      Done
                    <?php endif;?>
                  </td>

                  <td>
                    <?php
                    
                    //die;
                    $clApproval = $rl->getLevelApproval('cl');
                    //var_dump($clApproval);
                    //die;
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
                  <!-- <td><?php //echo $siteName['siteName']; ?></td> -->
                  <td>
                    <?php if(!$rl->isManagerPending()):?>
                    <?php echo $pr->getSite()->siteName; ?>
                    <?php endif;?>
                  </td>
                  <td>
                    <?php if(!$clApproval->isPending()):?>
                    <?php echo $pr->getCluster()->clusterName; ?>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if(!$omApproval->isPending()):?>
                    <?php echo $pr->getOps(); ?>
                    <?php endif; ?>
                  </td>
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
<script type="text/javascript">
rl.reconcilationInputUpdate();

<?php if(!$rl->isEditable()):?>
$('input').attr('disabled', true);
<?php endif;?>
</script>