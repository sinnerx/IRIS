<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript">

	$(document).ready(function() {

		if($("#siteID").val() == "")
			$("#export_excel_btn").hide();
		else
			$("#export_excel_btn").show();

		// $('.custom-table tr td').filter(function () {
  //           return +($.trim($(this).text())) == ".";
  //       }).css('font-weight', 'bold');

		// $.fn.wrapInTag = function(opts) {
 
		//  var tag = opts.tag || 'strong',
		//      words = opts.words || [],
		//      regex = RegExp(words.join('|'), 'gi'),
		//      replacement = '<'+ tag +'>$&</'+ tag +'>';
		 
		//  return this.html(function() {
		//    return $(this).text().replace(regex, replacement);
		//  });
		// };

		// $('td').wrapInTag({
		//  tag: 'strong',
		//  words: ['0.00']
		// });

		// Make 0.00 bold
		$("td").each(function() {
		    var dat = $(this).html();

		    if (dat == "0.00" || dat == 0) {
		    	$(this).css('font-weight', 'bold');
		    };
		});

		// Add class for odd and even table row
		$("tr:odd").addClass("odd");
		$("tr:even").addClass("even");

		$('.row').floatingScrollbar();

		// table.toggleExpand();

		// show hide column
		$('.showhidecol').click(function(){

			//change color button
			$(this).toggleClass( 'btn-warning', 'add' );

			var row  = $(this).data('row');
			var col  = $(this).data('col');

			// separate col data
			eachCol = col.split(",");

			// row 1
			// split column data
			var colData1 = eachCol[0].split(":"); // eg: 2:4 / 2 - start / 4 - end
			
			// if only 1 column
			if (colData1[1] == 1) {
				$('.custom-table tr:nth-child(1) th:nth-child('+colData1[0]+')').toggle();
			} else {
				var colEnd1 = 1+Number(colData1[1]); // eg: 1 + 4 = 5 / Total Loop

				//loop for total column
				for (var i = colData1[0]; i < colEnd1; i++) {
					$('.custom-table tr:nth-child(1) th:nth-child('+i+')').toggle();
				};
			}

			// row 2
			// split column data
			var colData2 = eachCol[1].split(":"); // eg: 2:4 / 2 - start / 4 - end

			// if only 1 column
			if (colData2[1] == 1) {
				$('.custom-table tr:nth-child(2) th:nth-child('+colData2[0]+')').toggle();
			} else {
				var colEnd2 = 1+Number(colData2[1]); // eg: 1 + 4 = 5 / Total Loop

				//loop for total column
				for (var i = colData2[0]; i < colEnd2; i++) {
					$('.custom-table tr:nth-child(2) th:nth-child('+i+')').toggle();
				};
			}

			// row 3
			// split column data
			var colData3 = eachCol[2].split(":"); // eg: 2:4 / 2 - start / 4 - end
			
			// if only 1 column
			if (colData3[1] == 1) {
				$('.custom-table tr:nth-child(3) th:nth-child('+colData3[0]+')').toggle();
			} else {
				var colEnd3 = 1+Number(colData3[1]); // eg: 1 + 4 = 5 / Total Loop

				//loop for total column
				for (var i = colData3[0]; i < colEnd3; i++) {
					$('.custom-table tr:nth-child(3) th:nth-child('+i+')').toggle();
				};
			}

			// get all row
			var rowCount = $('.custom-table tr').length;
			// minus 7 row
			// 3 bottom row
			// 4 top row
			var totalRow = rowCount - 7;

			// row loop data
			// loop for total column
			for (var i = 5; i < 5+totalRow+1; i++) {
				if (colData3[1] == 1) {
					$('.custom-table tr:nth-child('+i+') td:nth-child('+colData3[0]+')').toggle();
				} else {
					for (var ii = colData3[0]; ii < colEnd3; ii++) {
						$('.custom-table tr:nth-child('+i+') td:nth-child('+ii+')').toggle();
						// last row
						$('.custom-table tr:nth-child('+rowCount+') td:nth-child('+ii+')').toggle();
					}
				}
			};

			// column 4
			// get all column
			var colCount = $('.custom-table tr:nth-child(5) td:visible').length;
			$('#e1').attr('colspan',colCount-2);
			//alert(colCount-2);

		});

		$('#export_excel_btn').click(function () {
			console.log($("#siteID").val());
			window.location.href = pim.url('expExcel/getDailyCashProcess/' + $("#siteID").val() + '/' + $("#selectMonth").val() + '/' + $("#selectYear").val());
		});

	});
	
	
	var base_url	= "<?php echo url::base();?>/";
	
	var billing	= new function()
	{	
		this.select	= function(itemID)
		{		
			var siteID	= $("#siteID").val() != ""?"&siteID="+$("#siteID").val():"";
			var selectMonth	= $("#selectMonth").val() != ""?"&selectMonth="+$("#selectMonth").val():"";		
			var selectYear	= $("#selectYear").val() != ""?"&selectYear="+$("#selectYear").val():"";
	
			if (!$("#siteID")[0]) {
        		var siteID = "<?php echo $siteID ?>";
	   		}

	   		window.location.href	= base_url+"billing/dailyCashProcess?"+siteID+selectMonth+selectYear;	
		}
	}

	var table = new function()
	{
		this.collapsed = false;

		this.toggleExpand = function()
		{
			var squareWrapper = $("#a2, #b2, #c2, #d2");
			squareWrapper.removeClass('fa-plus-square').removeClass('fa-minus-square');
			columns = $(".billing-column-member, .billing-column-pc-day, .billing-column-pc-night, .billing-column-print");

			// handle expansion
			if(this.collapsed)
			{
				// show collapsion button
				squareWrapper.addClass('fa-minus-square');

				columns.show();

				// colspan expansion
				$('#a1').attr('colspan','6');

				$('#b1').attr('colspan','15');
				$('#c1').attr('colspan','15');
				$('#d1').attr('colspan','3');

				$('#e1').attr('colspan','45');
				$('#f1').attr('colspan','47');
				$('#g1').attr('colspan','46');
				$('.j1').attr('colspan','44');

				this.collapsed = false;

			}
			// handle collapsion
			else
			{
				// show expansion button
				squareWrapper.addClass('fa-plus-square');

				columns.hide();

				// colspan collapsion
				$('#a1').attr('colspan','2');
				$('#b1').attr('colspan','3');
				$('#c1').attr('colspan','3');
				$('#d1').attr('colspan','1');
				$('#e1').attr('colspan','15');
				$('#f1').attr('colspan','17');
				$('#g1').attr('colspan','16');
				$('.j1').attr('colspan','14');

				this.collapsed = true;
			}
		}

		this.select	= function()
		{
			return this.toggleExpand();

			

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
	.ov {

		 overflow-x: auto;
  		width: 100%;
	}

	/* hide for now */
	.fa-plus-square
	{
		display: none;
	}

	/* bold left border for th and td */
	.custom-table tr th, tr td
	{
		border-left: 2px solid #ddd;
		border-right: 2px solid #ddd;
	}

	/* remove bottom border for td */
	.custom-table .skip-top
	{
		border-top: none !important;
	}

	.custom-table .skip-border td
	{
		border-top: 1px solid #ddd !important;
		border-bottom: 1px solid #ddd !important;
	}

	/* Change background color */
	.thead-bg {
	   background: #ededed !important;
	}

	/* Change background color for odd table row */
	.odd {
	   background: rgb(242, 244, 248);
	}

	/* Change background color for even table row */
	.even {
	   background: #ffffff;
	}
        .center {
            text-align:center;
            
        }

</style>

<h3 class='m-b-xs text-black'>
	Daily Cash Process Summary
</h3>
<!--<div class='well well-sm'>
	Choose month and year
</div>-->
<?php echo flash::data();?>
<!--<div class='row'>
	<div class='col-sm-10'>
		<form class="form-inline bs-example" method='post' action=''>
			<?php  //if((session::get("userLevel") == \model\user\user::LEVEL_ROOT) || (session::get("userLevel") == \model\user\user::LEVEL_CLUSTERLEAD)  || (session::get("userLevel") == \model\user\user::LEVEL_FINANCIALCONTROLLER)): ?>
			<div class="form-group" style="margin-left:10px">
				<?php //echo form::select("siteID",$siteList,"class='input-sm form-control input-s-sm inline v-middle' onchange='billing.select();'",$siteID,"[SELECT SITE]");?>			
			</div>
			<?php //endif;?>
			<div class="form-group" style="margin-left:10px">
				<?php //echo form::select("selectMonth",model::load("helper")->monthYear("monthE"),"onchange='billing.select();'",$selectMonth);?>
				<?php //echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='billing.select();'",$selectYear);?>
				<input type="button" id="export_excel_btn" class="btn btn-sm btn-default" value="Export to Excel">			
			</div>			
		</form>	
	</div>
</div>-->

<?php //echo var_dump($data) ?>
<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			 <?php 
			  //echo $site->siteName; ?> Verification Summary for <?php echo $selectYear; ?>/<?php echo $selectMonth; ?>
		</div>
		
		<div class="table-responsive">
			<table class='table b-t b-light custom-table'>

                            <tr class="thead-bg">
                                <th rowspan="2" class="center">Site</th>
                                    <th rowspan="2" class="center">RM</th>
                                    <th rowspan="2" class="center">Balance</th>
                                    <th colspan="2" class="center">Site Manager<br></th>
                                    <th colspan="2" class="center">Cluster Lead<br></th>
                                    <th colspan="2" class="center">Finance Controller<br></th>
                                  </tr>
                                  <tr>
                                    <td class="center">Date</td>
                                    <td class="center">Approved by<br></td>
                                    <td class="center">Date</td>
                                    <td class="center">Approved by<br></td>
                                    <td class="center">Date</td>
                                    <td class="center">Approved by<br></td>
                                  </tr>
                                  <tr> 
                                    <td>Site name here</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                  </tr>
                                  <tr> 
                                    <td>site name here</td>
                                    <td>12</td>
                                    <td>13</td>
                                    <td>14</td>
                                    <td>15</td>
                                    <td>16</td>
                                    <td>17</td>
                                    <td>18</td>
                                    <td>19</td>
                                  </tr>
<!-- Begin master loop. -->
<?php 
$float = function($val = null)
{
	if(!$val)
		return number_format(0, 2, '.', '');
	else
		return number_format($val, 2, '.', '');
};

$total = function($val = null)
{
	return !$val ? 0 : $val;
}

?>
<?php
			$beginningbalance = $balance;
			foreach(range(1, date('t', strtotime($selectYear.'-'.$selectMonth.'-01'))) as $day):
			$date = $selectYear.'-'.$selectMonth.'-'.$day;
			$date = date('Y-m-d', strtotime($date));
			$no = 0;
			?>
			<tr class="left-td">
				<td><?php echo "site-name-here"?></td>
                                
				<td><?php echo $amount = $float($report[$date]['Membership']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['Membership']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Membership']['student']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['Membership']['student']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $float($report[$date]['Membership']['adult']['nonmember']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['Membership']['adult']['nonmember']['total_users']); $totals[$no++] += $amount;?></td>

				<td><?php echo $amount = $float($report[$date]['PC']['day']['total']); $totals[$no++] += $amount;?></td>
				<td><?php echo $amount = $total($report[$date]['PC']['day']['total_users']); $totals[$no++] += $amount;?></td>
				
			</tr>
			<?php endforeach;?>
<!--  End of master loop. -->
<!--			<tr>
				<td>Total</td>
				<?php //foreach($totals as $value):?>
				<td><?php //echo $float($value);?></td>
				<?php //endforeach;?>
				<td></td>
				<td></td>
				<td><?php //echo $float($sums);?></td>
				<td><?php //echo $float($balance);?></td>
			</tr>-->
			<tr>					
<!--				<th id="f1" colspan="47"></th>-->
			</tr>
<!--			<tr style="background-color:#ededed">	
				<th> </th>
				<th id="g1" colspan="46"> Bank In </th>
			</tr>-->
			</table>			
		</div>
	</div>


</div><br><br>	


