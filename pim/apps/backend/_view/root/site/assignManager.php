<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">

var base_url	= "<?php echo url::base();?>/";
//alert(get_post['cluster']+ 'aaaaaa');
$(document).ready(function() {
	$("#username").autocomplete({
        source: base_url + "site/searchManager", // path to the get_birds method
        select: function (event, ui){
          event.preventDefault();
          $("#username").val(ui.item.label);
          //PK.render(ui.item.value);
          // console.log(ui.item.value);
          $("#userID").val(ui.item.value);
          //alert($("#siteid").val());
          //$("#siteid").val();
       	}
    });
});
</script>

<h3 class="m-b-xs text-black">
Assign Manager
</h3>
<div class='well well-sm'>
Please choose one from the existing manager.
</div>
<div class='row'>
	<div class='col-sm-6'>
	<form method="post">
	<section class='panel panel-default'>
		<div class='panel-body'>
		<div class='form-group'>
		<label>1. Site</label>
		<div><?php echo $row['siteName'];?>, <?php echo $state;?></div>
		</div>

		<div class='form-group'>
		<label>1. Manager</label>
		<div><?php //echo form::select("userID",$userR);?></div>
        <?php echo form::text("username","class='form-control'");?>                       
        <?php echo form::hidden("userID","class='form-control'");?>                       		
		</div>

		<div class='form-group'>
		<?php echo form::submit("Assign","class='btn btn-primary pull-right'");?>
		<?php //echo flash::data("userID");?>
		</div>
		</div>
	</section>
	</form>
	</div>
</div>