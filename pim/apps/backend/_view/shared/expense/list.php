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
  Purchase Requisition List Open / Closed
</h3>

<?php echo flash::data();?>
<div class='row'>
  <div class='col-sm-12'>
   <section class="panel panel-default">
   <form class="form-inline bs-example " method='post' action='<?php echo url::base('expense/submit1Requisition/');?>'>
          <header class="panel-heading">
            Purchase Requisition
          </header>
          <?php if (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER) { ?>
          <div align="right"> 
            <a href='<?php echo url::base("expense/add/");?>' class='fa fa-external-link' style="color:green;">Add New Purchase Requisition</a>     
          </div>
          <?php } ?>
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
                  <th width="10%">Remark</th>
                  <th width="10%">PR Number</th>
                  <th width="10%"></th>
                </tr>
          
          <?php 

          $no = pagination::recordNo();

          foreach ($listPR as $key => $list):?>           
                <tr id = "<?php echo $key ?>">
                  <td><?php echo $no++; ?></td>
                  <?php if (authData('user.userLevel') != \model\user\user::LEVEL_SITEMANAGER) { 
                   $siteName = model::load('site/site')->getSite($list['siteID']); ?>
                  <td><?php echo $siteName['siteName']; ?></td><?php } ?>
                  <td><?php echo $list['purchaseRequisitionCreatedDate']; ?></td>
                  <td><?php echo isset($prTerm[$list['purchaseRequisitionType']]) ? $prTerm[$list['purchaseRequisitionType']] : null;  ?></td>
                  <td><?php
                      $getStatus = model::load('expense/approval')->getStatus($list['purchaseRequisitionId'], $list['purchaseRequisitionType']);
                      if ($list['purchaseRequisitionNumber'] != ""){
                        echo "Closed";
                      } else {                        
                        echo $getStatus[0];
                      }                    
                    ?>
                  </td>
                  <td><?php 

                      if ($list['purchaseRequisitionRemark'] != null){
                          echo $checkLog = model::load('expense/transaction')->checkLog($list['purchaseRequisitionRemark']);      
                      }
                  ?>
                  </td>
                  <td><?php echo $list['purchaseRequisitionNumber']; ?></td>
                  <td><?php 

                  if (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER) { // fa fa-edit  

                      if ($getStatus[1] == x ) { ?>

                        <a href="<?php echo url::base('expense/editForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>
                        <?php } elseif ($getStatus[1] == 1) { ?>

                        <a href="<?php echo url::base('expense/viewForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                  
                      <?php } elseif ($getStatus[1] == 3) { ?>

                        <a href="<?php echo url::base('expense/viewForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                  
                      <?php } elseif ($getStatus[1] == "") { ?>

                        <a href="<?php echo url::base('expense/editForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                  
                      <?php }
                
                  } elseif (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD) {  
                           
                      if ($getStatus[1] == 1){ ?>

                        <a href="<?php echo url::base('expense/getFormSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                                    
                       <?php } elseif ($getStatus[1] == "") { ?>

                        <a href="<?php echo url::base('expense/getFormSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                  
                      <?php } elseif ($getStatus[1] != 3) { ?>            
                        
                        <a href="<?php echo url::base('expense/getForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                
                      <?php } ?>  
                      
                  <?php
                  } elseif (authData('user.userLevel') == \model\user\user::LEVEL_OPERATIONMANAGER) {

                      if ($getStatus[1] == 1){ ?>
                        <a href="<?php echo url::base('expense/getFormSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                                    
                 <?php } else  

                      if ($getStatus[1] == 3) { ?>            
                         <a href="<?php echo url::base('expense/getForm/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                
                 <?php } 

                  }  elseif (authData('user.userLevel') == \model\user\user::LEVEL_FINANCIALCONTROLLER) { 
                        
                        if ($getStatus[1] == 1){ ?>     
                        <a href="<?php echo url::base('expense/getFormSuccess/'.$list['purchaseRequisitionId']);?>" class='fa fa-external-link pull-right' style="color:green; float:right"></a>                                    
                 <?php }

                  } ?>
                  </td>            
        <?php endforeach; ?>  
                        
              </tbody>
            </table>                  
          </div>

          <br>
        
    </form>
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






