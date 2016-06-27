<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
    var type = $('#rlDownloadPrType').val();
    var month = $("#downloadMonth").val();
    var year = $("#downloadYear").val();

    if(!clusterID || !month || !year || !type)
      return alert('Please complete the form');

    $.ajax({url: pim.url('ajax/shared/exp/rlCheckPending/'+clusterID+'/'+type+'/'+month+'/'+year), dataType: 'json'}).done(function(result)
    {
      if(result.hasPending == true)
      {
        if(!confirm('There are still pending RL for this month. Do you still want to download?'))
          return;
      }

      window.location.href = pim.url('expExcel/rlSummaryGenerate/'+clusterID+'/'+type+'/'+month+'/'+year);
    });
  }

  this.updateFilter = function()
  {
    var filter = {
      status : $('#status').val(),
      month : $('#month').val(),
      year : $('#year').val(),
      cluster : $('#cluster').val(),
      site : $("#site_id").val(),
      siteName : $("#site_name").val(),      
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
              <span>
              Filter : 
            <?php if (user()->isOperationManager() || user()->isRoot()): ?>
            <?php echo form::select('cluster', model::load('site/cluster')->listsForDropDown(), 'onchange="rlList.updateFilter();" class="form-control"', $cluster, false);?>
            <?php echo form::text("site_name","class='form-control'", $siteName);?>
            <?php echo form::hidden("site_id","class='form-control'");?>

            <script type="text/javascript">
            var tempfieldName;
            var tempfieldID;
            //console.log('abc');
             tempfieldName  = $("#site_name" );
             tempfieldID  = $("#site_id" );
             //console.log(tempfieldName);
            $(document).ready(function() {
              tempfieldName.width(200);
              tempfieldName.autocomplete({
                    source: "/digitalgaia/iris/dashboard/report/get_site", // path to the get_user method
                    select: function (event, ui){
                      event.preventDefault();
                      //console.log(ui.item.value);

                      tempfieldName.val(ui.item.label);
                      //PK.render(ui.item.value);
                      //console.log(ui.item.value);
                      tempfieldID.val(ui.item.value);

                      
                      if(ui.item.label == '')
                          $("#site_id").val('')

                      rlList.updateFilter();
                      //console.log(tempfieldID.val());
                      //alert($("#siteid").val());
                      //alert(tempfieldID.val());
                      //$("#siteid").val();
                    }

                });

                tempfieldName.change(function(){
                    if(tempfieldName.val().length == 0){
                      tempfieldID.val('');
                    }
                  }); 

                tempfieldName.keypress(function(e) {
                    if(e.which == 13) {
                        //alert('You pressed enter!');
                        prList.updateFilter();
                    }
                }); 
                
                //$(this).data('ui-autocomplete')._trigger('site_name', 'autocompleteselect', {item:{value:$(this).val()}});                    
            });
            </script> 
          <?php endif;?>

              <?php echo form::select('status', array('pending' => 'Pending', 'closed' => 'Closed', 'all' => 'All'), 'onchange="rlList.updateFilter();" class="form-control"', $status, false);?>
              <?php echo form::select('month', model::load('helper')->monthYear('month'), 'onchange="rlList.updateFilter();" class="form-control"', $month, false);?>
               <?php echo form::select('year', model::load('helper')->monthYear('year'), 'onchange="rlList.updateFilter();" class="form-control"', $year, false);?>
              </span>
              <?php if(!user()->isManager()):?>
              <a class='btn btn-primary pull-right' data-toggle='ajaxModal' href='<?php echo url::base('exp/rlDownload');?>'><span class='fa fa-download'></span> Download RL Summary</a>
              <?php endif;?>
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