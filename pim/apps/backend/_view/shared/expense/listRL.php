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
  Reconciliation List Open / Closed
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>


<div class='panel-heading'>
    <div class="row">
      <form method="get" id="formSearch"><!-- search form -->
        <div class="col-sm-3 pull-right">
            <div class="input-group">
            <input class="input-sm form-control" name="search" placeholder="Search : by PR Number" value='<?php echo request::get("search");?>' type="text">
              <span class="input-group-btn">
                <button class="btn btn-sm btn-default" type="button" onclick="$('#formSearch').submit();">Go!</button>
              </span>
            </div>
        </div>
      </form>
    </div>
  </div>


    <section class="panel panel-default">
          <header class="panel-heading">
            Reconciliation List
          </header>
          <div align="right"> 
          <!--   <a href='<?php echo url::base("expense/reconciliation/");?>' class='fa fa-external-link' style="color:green;">Add New Reconciliation List</a>      -->
          </div>
          <div class="table-responsive ">
            <table class="table table-striped b-t b-light">
              <tbody id="p_scents">
                <tr>
                  <th width="5%">No.  </th>
                  <?php if (authData('user.userLevel') != \model\user\user::LEVEL_SITEMANAGER) { ?>
                  <th width="10%">Site Name</th>
                  <?php } ?>
                  <th width="10%">Date Created</th>
                  <th width="10%">Type</th>
                  <th width="10%">Status</th>                  
                  <th width="10%">PR Number</th>
                  <th width="10%"></th>
                </tr>
          
          <?php 
            $no = pagination::recordNo();

            foreach ($listRL as $key => $list):?>           

                <tr id = "<?php echo $key ?>">
                  <td><?php echo $no++; ?></td>
                  <?php if (authData('user.userLevel') != \model\user\user::LEVEL_SITEMANAGER) { 
                   $siteName = model::load('site/site')->getSite($list['siteID']); ?>
                  <td><?php echo $siteName['siteName']; ?></td><?php } ?>
                  <td><?php echo $list['purchaseRequisitionCreatedDate']; ?></td>
                  <td><?php echo isset($prTerm[$list['purchaseRequisitionType']]) ? $prTerm[$list['purchaseRequisitionType']] : null;  ?></td>
                  <td><?php 
                      $getStatus = model::load('expense/approval')->getStatus($list['purchaseRequisitionId'], 3);

                      if ($getStatus[1] == 1){
                        echo "Closed";    
                      } else {
                        echo $getStatus[0];  
                      }
                      
                    ?>
                  </td>
                  <td><?php echo $list['purchaseRequisitionNumber']; ?></td>
                  <td>
                  <?php 
                  if ($getStatus[1] == "") {  
                      if (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER) {  ?> 
                          <a href="<?php echo url::base('expense/viewRList/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                      <?php  
                      }  
                  } else { ?>
                  <?php
                      if (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER) {  ?>                       
                          <?php 
                          if (($getStatus[1] == 1) || ($getStatus[1] == 2)) { ?>
                            <a href="<?php echo url::base('expense/viewRListSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                          <?php } ?>                          
                  <?php 
                      } elseif (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD) { 

                            if ($getStatus[1] == 2) { ?>
                              <a href="<?php echo url::base('expense/viewRList/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                
                            <?php } elseif ($getStatus[1] == 1) {?> 
                              <a href="<?php echo url::base('expense/viewRListSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                            <?php } ?>
                  <?php 
                      } else {  

                            if ($getStatus[1] == 1) { ?>
                              <a href="<?php echo url::base('expense/viewRListSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                            <?php } elseif ($getStatus[1] != 2) { ?>
                              <a href="<?php echo url::base('expense/viewRList/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                            <?php } 
                      }  ?>
                  <?php   
                  } 
                  if ($getStatus[1] == 1){ ?>

                    <a href="<?php echo url::base('expense/generateRLReport/'.$list['purchaseRequisitionNumber'].'/'.$list['purchaseRequisitionId']);?>" class='fa  fa-download pull-right' style="color:green; padding-right:20%; float:right"></a>

                  <?php } ?> 

                  </td>
                </tr>
            
        <?php endforeach; ?>  
                        
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