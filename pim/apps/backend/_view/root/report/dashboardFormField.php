<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script><h3 class="m-b-xs text-black">
<script type="text/javascript" src='<?php echo url::asset("scripts/jquery-min.js");?>'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
var base_url	= "<?php echo url::base();?>/";

$(document).ready(function() {

});
</script>

<?php

	$form_fields_raw = $model_form_fields;
	//var_dump($form_fields_raw);
    $form_fields = array();
    $children = array();
    foreach ($form_fields_raw as $form_field_raw) {
        /*echo "<li>";
        echo count($form_fields);
        print_r($form_field_raw['nm_form_field']);*/
        $id_parent = $form_field_raw['report_fieldsID'];
        if ($form_field_raw['report_fieldsParentID'] == 0) {
            $tmp_form_field = array(
            	'report_fieldsID'=>$form_field_raw['report_fieldsID'],
                'report_fieldsName'=>$form_field_raw['report_fieldsName'],
                'report_fieldsTitle'=>$form_field_raw['report_fieldsTitle'],
                'report_fieldsType'=>$form_field_raw['report_fieldsType'],
                'report_fieldsIsMandatory'=>$form_field_raw['report_fieldsIsMandatory'],
                'report_fieldsTableID'=>$form_field_raw['report_fieldsTableID'],
                'report_fieldsTableName'=>$form_field_raw['report_fieldsTableName'],
                'report_fieldsTable'=>$form_field_raw['report_fieldsTable'],
                'report_fieldsSequence'=>$form_field_raw['report_fieldsSequence'],
                'children' => array());
            foreach ($form_fields_raw as $child) {
                if ($child['report_fieldsParentID'] == $id_parent) {
                    $tmp_form_field['children'][] = $child['report_fieldsTitle'];
                }
            }
            $form_fields[] = $tmp_form_field;
        }
    }
    //var_dump($form_fields);
    //var_dump(model::load("site/cluster")->lists());
?>
<h3 class="m-b-xs text-black">
<a href='info'><?php echo $model_report_form['reportsFormName'];?></a>
</h3>
<div class='well well-sm'>
Please complete the form below to generate the report.
</div>
<form method='post' action="../reportDashboardGenerator" target="_blank">
	<?php echo form::hidden("idreport","class='form-control'",$idreport); ?>
<div class='row'>
	<div class='col-sm-12'>
		<div class='panel panel-default'>
		<div class='panel-body'>

 <?php 
 	//var_dump($form_fields);
 	//die;
 	$form_fields = model::load("helper")->sortBy("report_fieldsSequence", $form_fields, $direction = 'asc');
 	//var_dump($form_fields);
 	//die;
 	foreach ($form_fields as $form_field) {
 	switch ($form_field['report_fieldsType']) {
 		case 1:
 			# code...
 			//textfield
 		?>
		<div class='row'>
			<div class='form-group'>
				<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>
				<?php echo form::text($form_field['report_fieldsName'],"class='form-control' placeholder=\"\"");?>
				</div>
			</div>			
		</div>
 		<?php
 			break; 		

 		case 2:
 			# code...
 			//dropdown
 			//var_dump($form_field);
 		?>

 		<!-- html -->
		<div class='row'>
			<div class='form-group'>
				<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>

				<?php 
				//var_dump($form_field['children']);
				echo form::select($form_field['report_fieldsName'],$form_field['children'],"class='form-control'",'');?>
				</div>
			</div>			
		</div> 		
 		

 		<?php
 			break;

 		case 3:
 			# code...
 			//dropdown from table
 		?>
 		<!-- html -->
		<div class='row'>
			<div class='form-group'>
				<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>

				<?php 

				db::select($form_field['report_fieldsTableName']. ",". $form_field['report_fieldsTableID']);
				db::from($form_field['report_fieldsTable']);
				$results = db::get()->result();
				//$fieldsArray = array();
				foreach ($results as $result) {
					# code...
					$fieldsArray[$result[$form_field['report_fieldsTableID']]] = $result[$form_field['report_fieldsTableName']];
					//var_dump($result[$form_field['report_fieldsTableID']]);
					
				}
				//var_dump($fieldsArray);
				echo form::select($form_field['report_fieldsName'],$fieldsArray,"class='form-control'",'');

				?>
				</div>
			</div>			
		</div>  		

 		<?php
 			break;
 		
 		case 4:
 			# code...
 			//date
 		?>

 		<!-- html -->
		<div class='row'>
			<div class='form-group'>
				<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>

 				<?php echo form::text($form_field['report_fieldsName'],"class='input-sm input-s datepicker-input form-control' date-date-format='dd-mm-yyyy'",date('Y-m-d', strtotime($todayDateStart)));?>	
				</div>
			</div>			
		</div>  
		<script>
		$(document).ready(function() {
			var datepicker = "<?php echo $form_field['report_fieldsName'];?>";
			//console.log(datepicker);
			$("#"+datepicker).change(function(){
				$( "#"+datepicker ).datepicker("option","dateFormat","yy-mm-dd");
			});
		    
		  });
		</script>
 		<?php
 			break;

 		case 5:
 			# code...
 			//autocomplete
 		?>

 		<!-- html -->

		<div class='row'>
			<div class='form-group'>
				<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>

 				<?php echo form::text($form_field['report_fieldsName'],"class='form-control'",'');?>
 				<?php echo form::hidden($form_field['report_fieldsName']."ID","class='form-control'",'');?>
				</div>
			</div>			
		</div> 


 		<script type="text/javascript">
 		var tempfieldName;
 		var tempfieldID;
 		//console.log('abc');
 		 tempfieldName 	= $("#"+ "<?php echo $form_field['report_fieldsName']; ?>" );
 		 tempfieldID	= $("#"+ "<?php echo $form_field['report_fieldsName']; ?>"+"ID" );
 		 //console.log(tempfieldName);
		$(document).ready(function() {
			
			tempfieldName.autocomplete({
		        source: "/digitalgaia/iris/dashboard/report/get_" + "<?php echo $form_field['report_fieldsTable']; ?>", // path to the get_user method
		        select: function (event, ui){
		          event.preventDefault();
		          console.log(ui.item.value);

		          tempfieldName.val(ui.item.label);
		          //PK.render(ui.item.value);
		          //console.log(ui.item.value);
		          tempfieldID.val(ui.item.value);
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
		});
		</script>		
 		<?php
 			break;
 			case 6:
 			//dropdown from array
 		?>
 		<!-- html -->
		<div class='row'>
			<div class='form-group'>
				<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>

				<?php 
				//var_dump($form_field['children']);
				//model::load("helper")->monthYear("year")
				//var_dump(model::load("helper")->$form_field['report_fieldsTable']("". $form_field['report_fieldsTableID'].""));
				echo form::select($form_field['report_fieldsName'],model::load("helper")->$form_field['report_fieldsTable']("". $form_field['report_fieldsTableID'].""),"class='form-control'",'');?>
				</div>
			</div>			
		</div> 
 		<?php
 			break;
 			case 7:
 			//checkbox
 			//var_dump($form_field);

			$index = 0;
                    $checks = array();
                    $check_value = $form_field['report_fieldsName'];
                    for ($i = count($form_field['children']) - 1; $i >= 0; $i--) { 
                        if ($check_value >= pow(2, $i)) {
                            $checks[$i] = 1;
                            $check_value -= pow(2, $i);
                        } else {
                            $checks[$i] = 0;
                        }
                    }
        ?>
        	<div class='row'>
			<div class='form-group'>
			<div class='col-sm-6'>
				<label><?php echo  $form_field['report_fieldsTitle']; ?></label>
                <?php    foreach ($form_field['children'] as $child) { ?>

                        <div class="col-sm-12">
                        <div class="row">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="<?php echo $form_field['report_fieldsName']?>[]" id="" value="<?php echo pow(2, $index)?>"
                                        <?php if ($checks[$index]) echo "checked"; ?>> <?php echo $child?>
                                </label>
                            </div>
                        </div>
                        </div>

 		<?php
 			$index ++;
 		}//foreach
 		?>
				</div>
			</div>			
		</div>  		
 		<?php
 			break;

 			case 8: 

 			break;
 		default:
 			# code...
 			break;
 	}
 ?>


 <?php
 }//end foreach form_fields

 ?>
		</div>

		</div>

	</div>

</div>

<!-- <button name="back" id="backbtn" value="Back" class='btn btn-primary pull-left'>
 -->
<?php echo form::submit("Submit","class='btn btn-primary pull-right'");?>
<input type="button" name="back" id="backbtn" value="Back" class='btn btn-primary' onclick="history.back()">
</form>