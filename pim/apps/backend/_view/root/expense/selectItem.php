<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>



<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var expenditure = new function()
{
  
    this.selectItem = function(id)
    {    
         
        var url = base_url+"expense/editItem/"+id;
        $.ajax({url:url}).done(function(data)
        {
            $("#editItem").html(data);
        });
    }

    this.addItem = function(id)
    {    
         
        var url = base_url+"expense/addItem/"+id;
        $.ajax({url:url}).done(function(data)
        {
            $("#editItem1").html(data);
        });
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



<?php echo flash::data();
    
?>
<div class='row'>
  <div class='col-lg-12' id='category-list'>
   
   
      <header class="panel-heading">
            Choose Item to edit
            <div align="right">
            <a href="<?php echo url::base('expense/addItem/'.$row[0]['purchaseRequisitionCategoryId']) ?>"  class='fa fa-external-link' data-toggle='ajaxModal' style="color:green;"> Add New Item</a>     
            

            </div>
      </header>
      

       <table class="table table-striped m-b-none">
         <thead>
           <tr>
             <th>No</th>
             <th>Item Name</th>                    
             <th>Status</th>
             <th></th>
           </tr>
         </thead>
         <tbody>

           <?php foreach ($row as $key => $value): ?>

           <tr>                    
             <td><?php echo $key + 1; ?></td>
             <td><?php echo $value['purchaseRequisitionItemName'] ?></td>
             <td><?php if ($value['purchaseRequisitionItemStatus'] == 1) {
                        $status = "Active"; 
                      } else {
                        $status = "Not Activated";
                      } 

                echo $status; ?></td>
             <td class="text-success">
              <a onclick="expenditure.selectItem(<?php echo $value[purchaseRequisitionItemId] ?>);" href='#' style="margin-left:20px" class='fa fa-edit pull-right' style='font-size:13px;'></a>  
             </td>
           </tr>
           
           <?php endforeach; ?>
              
         </tbody>
       </table>
    
  </div>
</div>

