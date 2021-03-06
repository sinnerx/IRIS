<style type="text/css">
  
  label {

      font-size: 13px;
      font-weight: bold;
  }
  .input-s-sm {

    width: 250px;
  }

</style>
<script type="text/javascript">

var prList = new function()
{
  this.submitPrNumber = function(id)
  {
    var value = $("#pr-input-"+id).val();

    if(value == '')
      return;

    $.ajax({type: 'POST', url: pim.url('exp/submitPrNumber/'+id), data: {prNumber: value}}).done(function()
    {
      window.location.reload();
    });
  }

  this.updateFilter = function()
  {
    var filter = {
      status : $('#status').val(),
      month : $('#month').val(),
      year : $('#year').val()
    };

    pim.redirect('exp/prList', filter);
  }
}


</script>
<h3 class='m-b-xs text-black'>
  Purchase Requisition <?php if(!user()->isFinancialController()):?> List Open / Closed <?php else:?> Cash Advance<?php endif;?>
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
   <div class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submit1Requisition/');?>'>
          <header class="panel-heading">
            Purchase Requisition <?php if(user()->isFinancialController()):?>Cash Advance<?php endif;?>
          </header>
          <div class="row wrapper">
            Filter : 
            <?php if(!user()->isFinancialController()):?>
            <?php echo form::select('status', array('pending' => 'Pending', 'closed' => 'Closed', 'all' => 'All'), 'onchange="prList.updateFilter(this.value);" class="form-control"', $status, false);?>
            <?php endif;?>
            <?php echo form::select('month', model::load('helper')->monthYear('month'), 'onchange="prList.updateFilter();" class="form-control"', $month, false);?>
            <?php echo form::select('year', model::load('helper')->monthYear('year'), 'onchange="prList.updateFilter();" class="form-control"', $year, false);?>
          <?php if (user()->isManager()) { ?>
            <a href='<?php echo url::base("exp/prAdd");?>' class='btn btn-primary pull-right'><span class='fa fa-plus'></span> Add New Purchase Requisition</a>     
          <?php } ?>
          </div>

          <?php 
          $isManager = user()->isManager();
          ?>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <th width="200px">Last Update</th>
                  <?php if(!$isManager):?>
                    <th>Microsite</th>
                  <?php endif;?>
                  <th width="150px">Type</th>
                  <th>Status</th>
                  <th>PR Number</th>
                  <th width="80px"></th>
                </tr>
          
          <?php 

          $no = pagination::recordNo();

          foreach ($pr as $key => $list):?>           
          <tr id = "<?php echo $key ?>">
            <td><?php echo $no++; ?></td>
            <td>
              <?php echo date('g:i A d-M Y', strtotime($list->prUpdatedDate));?>
            </td>
            <?php if(!$isManager):?>
              <td><?php echo $list->siteName;?></td>
            <?php endif;?>
            <td><?php echo $list->getTypeLabel();?></td>
            <td>
              <?php if($list->isRejected()):?>
                <a href='<?php echo url::base('exp/prRejectionReason/'.$list->prID);?>' style='color: red;' data-toggle='ajaxModal'><?php echo $list->getStatusLabel();?> [See Reason]</a>
              <?php else:?>
                <?php echo $list->getStatusLabel();?>
              <?php endif;?>
            </td>
            <td>
              <?php if($list->prNumber):?>
                <?php echo $list->prNumber;?>
              <?php elseif(user()->isRoot() && $list->isWaitingForPrNumber()):?>
                <form method="post" action='<?php echo url::base('exp/submitPrNumber/'.$list->prID);?>'>
                  <input type='text' name='prNumber' id='pr-input-<?php echo $list->prID;?>' class='form-control' style='display: inline;' size='22'  /> <input type='submit' value="Add" class='btn btn-primary'  />
                </form>
              <?php endif;?>
            </td>
            <td>
            <?php if($list->isDeletableBy(user())):?>
            <a href='<?php echo url::base('exp/prDelete/'.$list->prID);?>' onclick='return confirm("Delete this PR?");' class='pull-right fa fa-trash-o'></a>
            <?php endif;?>
            <?php if($list->cashAdvanceIsDownloadableBy(user())):?>
            <a href='<?php echo url::base('expExcel/prCashAdvanceDownload/'.$list->prID);?>' class='pull-right fa fa-download'></a>
            <?php endif;?>
            <?php if($list->isPendingFor(user()) && !user()->isRoot()):?>
            <a href="<?php echo url::base('exp/prEdit/'.$list->prID);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
            <?php else:?>
            <a href="<?php echo url::base('exp/prEdit/'.$list->prID);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                  
            <?php endif;?>
            </td>
          </tr>
        <?php endforeach; ?>  
        <?php if(count($pr) == 0):?>
        <tr>
          <td colspan='7' align="center">No PR is available</td>
        </tr>
        <?php endif;?>
                        
              </tbody>
            </table>                  
          </div>

          <br>
        
    </div>
    </section>

          <footer class='panel-footer'>
            <div class='row'>
              <div class="col-sm-10">
                <?php echo pagination::link();?>
              </div>
            </div>
          </footer>
       
     </div>
</div>






