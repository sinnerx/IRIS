<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.css"); ?>" type="text/css" />

<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/jquery.datetimepicker.js"); ?>"></script>

<script type="text/javascript">
  
$(document).ready(function()
{
  pim.ajax.urlify("#category-list ","#catDetail");

});

</script>

<script type="text/javascript">

var base_url  = "<?php echo url::base();?>/";

var expenditure = new function()
{
    this.getItem1 = function(id)
    {                  
        var itemId  = $("#itemCategory1").val();          

        var url = base_url+"expense/editExpenditureItem/"+itemId;
        $.ajax({url:url}).done(function(data)
        {
            $("#itemCategory2").val('');  
            $("#itemCategory3").val('');  
            $("#catDetail").html(data);
        });
    }

    this.getItem2 = function(id)
    {                  
        var itemId  = $("#itemCategory2").val();          

        var url = base_url+"expense/editExpenditureItem/"+itemId;
        $.ajax({url:url}).done(function(data)
        {
            $("#itemCategory1").val('');  
            $("#itemCategory3").val('');  
            $("#catDetail").html(data);
        });
    }

    this.getItem3 = function(id)
    {                  
        var itemId  = $("#itemCategory3").val();          

        var url = base_url+"expense/editExpenditureItem/"+itemId;
        $.ajax({url:url}).done(function(data)
        {
            $("#itemCategory1").val('');  
            $("#itemCategory2").val('');  
            $("#catDetail").html(data);
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
  Edit Capital Expenditure
</h3>

<?php echo flash::data();

?>
<div class='row'>
  <div class='col-lg-6' id='category-list'>
   <section class="panel panel-default">
   <form class=" bs-example form-horizontal " method='post' action='<?php echo url::base('expense/ddd/');?>'>
          <header class="panel-heading">
            Choose Item to edit
          </header>
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Budgeted</label>
            <div class="col-lg-6">
                <?php echo form::select("itemCategory1",$budgeted,"class='input-sm form-control input-s-sm inline v-middle' onchange='expenditure.getItem1();'",$budgeted);?>
            </div>
          </div>
  
          <div class="form-group">
            <label class="col-lg-3 control-label">Addition</label>
            <div class="col-lg-6">
                <?php echo form::select("itemCategory2",$addition,"class='input-sm form-control input-s-sm inline v-middle' onchange='expenditure.getItem2();'",$addition);?>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-lg-3 control-label">Replacement</label>
            <div class="col-lg-6">
                <?php echo form::select("itemCategory3",$replacement,"class='input-sm form-control input-s-sm inline v-middle' onchange='expenditure.getItem3();'",$replacement);?>
            </div>
          </div>
        
    </form>
    </section>
  </div>

  <div class='col-lg-6' id='catDetail'>
   <section class="panel panel-default">
   
    </section>
  </div>  
</div>