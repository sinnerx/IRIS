<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var expenseItem = new function()
{
    this.getCategory = function(id)
    {                  
        var itemId  = $("#categoryList").val();          
 
        var url = base_url+"expense/selectItem/"+itemId;
        $.ajax({url:url}).done(function(data)
        { 
            $("#getCategory").html(data);
            
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

<h3 class='m-b-xs text-black'>
        <span>Insert Item</span>
</h3>

<?php echo flash::data();


?>
<div class='row'>
  <div class='col-lg-6' id='category-list'>
   <section class="panel panel-default">
   <form class=" bs-example form-horizontal " method='post'>
      <header class="panel-heading">
            Choose Category            
      </header>

      <div class="form-group">

        <label class="col-lg-3 control-label">Category</label>
          <div class="col-lg-6">
            <?php echo form::select("categoryList",$category,"class='input-sm form-control input-s-sm inline v-middle' onchange='expenseItem.getCategory();'",$category);?>
          </div>
      </div>

    </form>
    </section>

   <section  id='getCategory' class="panel panel-default">
   </section>

  </div>

  <div id='editItem'  class='col-lg-6'>
    <section class="panel panel-default">
    </section>


  </div>  

</div>
