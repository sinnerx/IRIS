<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript">

// $(document).ready(function() {

  jQuery("#selectDatePoint").datetimepicker({
    //showTimepicker: false,
    format:'d-m-Y',  
  });

  // $("#selectDatePoint").on("changeDate", function(ev)
  // {
  //   // var siteID  = $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";      
  //   // var selectDateStart = $("#selectDateStart").val() != ""?"&selectDateStart="+$("#selectDateStart").val():"";   
  //   // var selectDateEnd = $("#selectDateEnd").val() != ""?"&selectDateEnd="+$("#selectDateEnd").val():"";
  
  //   //   if (!$("#siteID")[0]) {
  //   //         var siteID = "<?php echo $siteID ?>";
  //   //     }

  //   //     window.location.href  = base_url+"billing/dailyJournal?"+siteID+selectDateStart+selectDateEnd;
  //   // }); 


  //   });
// });

  
var itemEdit = new function()
{
  this.togglePricingType = function()
  {
    if(!$("#price-general")[0].checked)
    {
      $("#price").hide();
      $(".price-membership-based").show();
    }
    else
    {
      $("#price").show();
      $(".price-membership-based").hide();
    }
  }
}

</script>
<style type="text/css">
  
#price
{
  display: inline;
}

</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Edit Item</span>
			</h4>
		</div>
		<?php echo flash::data();?>
		
    <div class="modal-body">    
    <section class="panel panel-default">
      <div class="panel-body">

        <form class="bs-example form-horizontal" method='post' action='<?php echo url::base('billing/editItem/'.$item->billingItemID);?>'>
          <div class="form-group">
            <label class="col-lg-2 control-label">Hot Key</label>
            <div class="col-lg-10">        
              <?php echo form::select("hotKey",$keyList,"class='input-sm form-control input-s-sm inline v-middle'","$item->billingItemHotkey");?>  
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Name</label>
            <div class="col-lg-10">
              <?php echo form::text("itemName","class='form-control'",$item->billingItemName);?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Type</label>
            <div class="col-lg-10">             
              <?php echo form::select("itemType",$itemType,"class='input-sm form-control input-s-sm inline v-middle'",$item->billingItemType);?>           
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Price 
            </label>
            <div class="col-lg-10">
              <?php echo form::text("price","class='form-control' style='display: ".($item->billingItemPriceType == 1 ? 'inline' : 'none')."; width: 150px;' ", $item->billingItemPrice);?> 
              <div style="display: inline;" class="checkbox i-checks">
                <label>
                  <input type='checkbox' id='price-general' name='priceGeneral' value='1' <?php if($isGeneralPricing = ($item->billingItemPriceType == 1)):?>checked<?php endif;?> onchange="itemEdit.togglePricingType();"  /> <i></i>
                    <span>Same for member/non-member</span>
                </label>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class="col-lg-4 control-label">Enable editing</label>
            <div class="col-lg-8">
              <span class="checkbox i-checks">
                <label>
                  <input name="priceEnabled" type="checkbox" value="1" <?php echo $item->billingItemPriceDisabled == 1 ? '':'checked';?>><i></i>
                </label>
              </span>
            </div>
          </div>
          <div class='form-group price-membership-based' <?php if($item->billingItemPriceType != 2):?>style="display: none;"<?php endif;?>>
            <label class="col-lg-4 control-label">Price (member)</label>
            <div class="col-lg-8"><?php echo form::text('priceMember', 'class="form-control" style="display: inline; width: 150px;"', $item->billingItemPrice);?></div>
          </div>
          <div class='form-group price-membership-based' <?php if($item->billingItemPriceType != 2):?>style="display: none;"<?php endif;?>>
            <label class="col-lg-4 control-label">Price (non-member)</label>
            <div class="col-lg-8"><?php echo form::text('priceNonmember', 'class="form-control" style="display: inline; width: 150px;"', $item->billingItemPriceNonmember);?></div>
          </div>
          <?php /*<div class="form-group">
            <label class="col-lg-2 control-label">Unit</label>
            <div class="col-lg-10">
              <div class="checkbox i-checks">            
                <label>
                <?php
                    if ($item->billingItemUnitDisabled == 1){  ?>
                    <input name="unitDisabled" type="checkbox" value="1" checked><i></i><span style="font-size: 12px;">Enable Editing.</span>
                    <?php } else {  ?>
                    <input name="unitDisabled" type="checkbox" value="1"><i></i><span style="font-size: 12px;">Enable Editing.</span>
                  <?php } ?>                         
                </label>
              </div>
            </div>
          </div> */?>
          <div class="form-group">
            <label class="col-lg-2 control-label">Quantity</label>
             <div class="col-lg-10">
              <div class="checkbox i-checks">            
                <label>
                    <input name="quantityEnabled" type="checkbox" value="1" <?php echo $item->billingItemQuantityDisabled == 1 ? '' : 'checked' ;?>><i></i><span style="font-size: 12px;">Enable Editing.</span>
                </label>
              </div>
            </div>
          </div>
          

         
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <button type="submit" class="btn btn-sm btn-default">Update</button>
              <a onclick='return confirm("Delete this item?");' href='<?php echo url::base('billing/deleteItem/'.$item->billingItemID); ?>' class='btn btn-sm btn-default' style='font-size:13px;'>Delete</a>
            </div>
          </div>
        </form>
      </div>
      <div id="addpoint">
        <form method='post' action='<?php echo url::base("billing/addPoint/$item->billingItemID");?>'>
          
          <?php echo form::text("selectDatePoint","class='input-sm input-s datepicker-input form-control ' date-date-format='dd-mm-yyyy' style='width:100px; display: inline;'",date('d-m-Y'));?>
          <?php echo form::text('rewardtxt', 'class="form-control" style="display: inline; width: 50px;"');?>
          <?php echo form::text('redeemtxt', 'class="form-control" style="display: inline; width: 50px;"');?>
           <button type="submit" class="btn btn-sm btn-default">Add Point</button>
        </form>
      </div>

    <section class="vbox" style="height:200px" id="pointhistory">
      <section class="scrollable wrapper">  
          <div class='table-responsive'>
            Point History
          <table class='table'>
            <tr>
              <th width='15px'>No.</th>
              <th width="300px">Effective Date</th>
              <th width="200px">Reward </th>
              <th width="200px">Redeem </th>
              <th width="200px">Action</th>
              <th>
            </tr>
            <?php if(!$billingItemPointList):?>
            <tr>
              <td style="text-align:center;" colspan="3">No history point was found.</td>
            </tr>
            <?php else:?>
              <?php
              // $no = pagination::recordNo();
              $no = 1;
              foreach($billingItemPointList as $row)
              {
                // var_dump($row);
                $rewardPoint        = $row['rewardPoint'];
                $effectiveDate      = $row['effectiveDate'];
                $redeemPoint        = $row['redeemPoint'];
              ?>
              <tr <?php if($currentPoint == $row['billingItemPointID']) echo "style='font-weight:bold'"; ?> >
                <td><?php echo $no++;?></td>
                <td><?php echo date("d-m-Y", strtotime($effectiveDate));?></td>
                <td><?php echo $rewardPoint;?></td>
                <td><?php echo $redeemPoint;?></td>
                <td><center>
                  <a class='fa fa-edit' href="<?php echo url::base('member/edit'); ?>/<?php echo $row['billingItemPointID']; ?>" ></a>
                  <a onclick='return confirm("Confirm delete?");' class="i i-cross2" href="delete/<?php echo $row['billingItemPointID']; ?>"></a>
                </center></td>
              </tr>
              <?php

              }
              ?>
            <?php endif;?>
          </table>
        </div>
        <div class='panel-footer'>
        <div class='row'>
          <div class="col-sm-12">
          <?php echo pagination::link();?>
          </div>
        </div>
        </div>   
      </section>    
    </section> 

    </section>

   
    

		</div>
	</div>
</div>