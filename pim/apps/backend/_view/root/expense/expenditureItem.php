<script type="text/javascript">
	
$(document).ready(function()
{
	pim.ajax.formify("#myform",null,function(txt)
	{
		if(txt)
		{
			window.location.href = "";
		}
	});
});

</script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var edit = new function()
{
    this.submit = function(id)
    {                  
        
        var url = base_url+"expense/updateExpenditureItem/"+id;
        
          $.ajax({
                    url: url,
                    type: "POST",
                    data: $('#editExpenditure').serialize(),
                    success: function (response) {

                    
                        	$("#catDetail").html();
                        	location.reload();
                        }
                    });

    }

}

</script>


<section class="panel panel-default">
	<header class="panel-heading">
        Edit expenditure item
	</header>

<div class='row'>
	<form id="editExpenditure" class=" bs-example form-horizontal ">
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Item Name</label>
            <div class="col-lg-6">
            	<?php echo form::text("itemName","class='input-sm form-control input-s-sm inline v-middle'",$row['purchaseRequisitionExpenditureName']); ?>
            </div>
          </div>
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Status</label>
            <div class="col-lg-6">
            	<?php echo form::select("itemStatus",$status,"class='input-sm form-control input-s-sm inline v-middle'",$row['purchaseRequisitionExpenditureStatus']);?>
            	<?php // echo form::text("itemStatus","class='form-control'",$row['purchaseRequisitionExpenditureStatus']); ?>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-offset-9 col-lg-10">
              <input type="button" class="btn btn-sm btn-default" value="Submit" onclick='edit.submit(<?php echo $row['purchaseRequisitionExpenditureId'] ?>);'>
            </div>
          </div>

	</form>
</div>
</section>