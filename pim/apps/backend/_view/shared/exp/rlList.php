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
  
var rlList = new function()
{
  this.downloadSummary = function()
  {
    var clusterID = $("#clusterID").val();
    var month = $("#downloadMonth").val();
    var year = $("#downloadYear").val();

    if(!clusterID || !month || !year)
      return alert('Please complete the form');

    $.ajax({url: pim.url('ajax/shared/exp/rlCheckPending/'+month+'/'+year), dataType: 'json'}).done(function(result)
    {
      if(result.hasPending == true)
      {
        if(!confirm('There are still pending RL for this month. Do you still want to download?'))
          return;
      }

      if(result.hasRecords)
      {

      }


      window.location.href = pim.url('expExcel/rlSummaryGenerate/'+clusterID+'/'+month+'/'+year);
    });
  }

  this.updateFilter = function()
  {
    var filter = {
      status : $('#status').val(),
      month : $('#month').val(),
      year : $('#year').val()
    };

    pim.redirect('exp/rlList', filter);
  }
}

</script>

<h3 class='m-b-xs text-black'>
  Reconciliation List Open / Closed
</h3>
<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>


    <section class="panel panel-default">
          <header class="panel-heading">
            Reconciliation List
          </header>
          <div class="row wrapper">
            <form class='form-inline'>
            <div class="col-sm-9 m-b-xs">
              <?php if(!user()->isManager()):?>
              <a class='btn btn-primary' data-toggle='ajaxModal' href='<?php echo url::base('exp/rlDownload');?>'><span class='fa fa-download'></span> Download RL Summary</a>
              <?php endif;?>
              <span class='pull-right'>
              Filter : <?php echo form::select('status', array('pending' => 'Pending', 'closed' => 'Closed', 'all' => 'All'), 'onchange="rlList.updateFilter();" class="form-control"', $status, false);?>
              <?php echo form::select('month', model::load('helper')->monthYear('month'), 'onchange="rlList.updateFilter();" class="form-control"', $month, false);?>
               <?php echo form::select('year', model::load('helper')->monthYear('year'), 'onchange="rlList.updateFilter();" class="form-control"', $year, false);?>
              </span>
            </div>
            <div class="col-sm-3">
              <div class='input-group'>
              </div>
              <div class="input-group">
                <form method='get'>
                <input type="text" class="input-sm form-control" name='search' value='<?php echo request::get('search');?>' placeholder="Search by PR number">
                <span class="input-group-btn">
                  <button class="btn btn-sm btn-default" type="submit">Go!</button>
                </span>
                </form>
              </div>
            </div>
            </form>
          </div>
          <?php $isManager = user()->isManager();?>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="20px">No.  </th>
                  <th width="200px">Last Update</th>
                  <?php if(!$isManager):?>
                    <th width="">Microsite</th>
                  <?php endif;?>
                  <th width="150px">Type</th>
                  <th width="">Status</th>                  
                  <th width="200px">PR Number</th>
                  <th width="60px"></th>
                </tr>
          
          <?php 
            $no = pagination::recordNo();

            foreach ($rl as $key => $list):
              $pr = $list->getPr();
              ?>           

                <tr id = "<?php echo $key ?>">
                  <td><?php echo $no++; ?></td>
                  <td>
                  <?php if($list->isSubmitted()):?>
                    <?php echo date('g:i A, d-M Y', strtotime($list->prReconcilationUpdatedDate)); ?>
                  <?php else:?>
                    -
                  <?php endif;?>
                  </td>
                  <?php if(!$isManager):?>
                    <td><?php echo $list->siteName;?></td>
                  <?php endif;?>
                  <td><?php echo $pr->getTypeLabel();?></td>
                  <td>
                    <?php if($list->isRejected()):?>
                      <a style="color: red;" href='<?php echo url::base('exp/rlRejectionReason/'.$list->prReconcilationID);?>' data-toggle='ajaxModal'><?php echo $list->getStatusLabel();?> [See Reason]</a>
                    <?php else:?>
                      <?php echo $list->getStatusLabel();?>
                    <?php endif;?>
                  </td>
                  <td><?php echo $pr->prNumber; ?></td>
                  <td>
                      <a href='<?php echo url::base('exp/rlDelete/'.$list->prReconcilationID);?>' onclick='return confirm("Delete this RL?");' class='i i-cross2 pull-right'></a>
                      <a href='<?php echo url::base('exp/rlEdit/'.$list->prReconcilationID);?>' class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                  </td>
                </tr>
        <?php endforeach; ?>
                <?php if(count($rl) == 0):?>
                  <tr>
                    <td colspan="7" align="center">No Reconcilation Records found</td>
                  </tr>
                <?php endif;?>
              </tbody>
            </table>                  
          </div>
          <br>
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