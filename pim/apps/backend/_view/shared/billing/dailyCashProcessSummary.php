<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("_scale/js/jquery.ba-floatingscrollbar.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url::asset("scripts/jquery.tablesorter.min.js"); ?>"></script>
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

	   		window.location.href	= base_url+"billing/dailyCashProcessSummary?"+siteID+selectMonth+selectYear;	
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

$(document).ready(function() 
    { 
        $("#DCPSummary").tablesorter(); 
    } 
); 
	
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
                border-bottom: 1px solid #ddd !important;

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
           border-top: 2px solid #ddd !important;

	}

	/* Change background color for odd table row */
	.odd {
/*	   background: rgb(242, 244, 248);*/
	}

	/* Change background color for even table row */
	.even {
/*	   background: #ffffff;*/
	}
        .center {
            text-align:center;
            
            
        }

            .tablesorter .header,.tablesorter .tablesorter-header {
                background-image:url(<?php echo url::asset("frontend/images/small.gif"); ?>); 
                background-position:center right;
                background-repeat:no-repeat;
                cursor:pointer;
                white-space:normal;
                padding:4px 20px 4px 4px;
            }

            .tablesorter thead .headerSortUp,.tablesorter thead .tablesorter-headerAsc,.tablesorter thead .tablesorter-headerSortUp {
                background-image:url(<?php echo url::asset("frontend/images/asc.gif"); ?>); 
/*                border-bottom:#000 2px solid;*/
            }

            .tablesorter thead .headerSortDown,.tablesorter thead .tablesorter-headerDesc,.tablesorter thead .tablesorter-headerSortDown {
                background-image:url(<?php echo url::asset("frontend/images/desc.gif"); ?>); 
/*                border-bottom:#000 2px solid;*/
            }
            th{
                vertical-align:middle !important;
            }
            
</style>
<?php echo flash::data();?>

<h3 class='m-b-xs text-black'>
	Daily Cash Process Summary
</h3>


<div class='row ov'>
	<div class="col-sm-12  ">
		<div class='well well-sm'>
			 <?php 
			  //echo $site->siteName; ?> Verification Summary for <?php echo $selectionData[1]; ?>/<?php echo $selectionData['0']; ?>
		</div>
                <!-- filter start -->
		<div class='row'>
                        <div class='col-sm-10'>
                                <form class="form-inline bs-example" method='post' action=''>
                                        <div class="form-group" style="margin-left:10px">
                                                <?php echo form::select("selectMonth",model::load("helper")->monthYear("monthE"),"onchange='billing.select();'",$selectMonth);?>
                                                <?php echo form::select("selectYear",model::load("helper")->monthYear("year"),"onchange='billing.select();'",$selectYear);?>
<!--                                                <input type="button" id="submit_btn" class="btn btn-sm btn-default" value="Submit">			-->
                                        </div>			
                                </form>	
                        </div>
                </div><br>
                <!-- filter end-->
		<div class="table-responsive">
			<table id="DCPSummary" class="tablesorter table b-t b-light custom-table">
                            <thead>
                            <tr class="thead-bg">
                                <th rowspan="2" class="center">Site</th>
                                    <th rowspan="2" class="center">Collection</th>
                                    <th rowspan="2" class="center">Balance</th>
                                    <th colspan="2" class="center">Site Manager<br></th>
                                    <th colspan="2" class="center">Cluster Lead<br></th>
                                    <th colspan="2" class="center">Finance Controller<br></th>
                                  </tr>
                                  <tr>
                                    <td class="center" style="background: #ededed;border-bottom: 2px solid #ddd">Date</td>
                                    <td class="center" style="background: #ededed;border-bottom: 2px solid #ddd">Approved<br></td>
                                    <td class="center" style="background: #ededed;border-bottom: 2px solid #ddd">Date</td>
                                    <td class="center" style="background: #ededed;border-bottom: 2px solid #ddd">Approved<br></td>
                                    <td class="center" style="background: #ededed;border-bottom: 2px solid #ddd">Date</td>
                                    <td class="center" style="background: #ededed;border-bottom: 2px solid #ddd">Approved<br></td>
                                  </tr>
                            </thead>
                            <tbody>
                                  <?php foreach($allSiteSummary as $siteData):?>
                                  <tr> 
                                    <td><?php echo $siteData['siteName'];?></td>
                                    <td><?php echo $siteData['collection'] = number_format($siteData['collection'], 2, '.', ''); ?></td>
                                    <td><?php echo $siteData['balance'] = number_format($siteData['balance'], 2, '.', ''); ?></td>
                                    <td><?php echo $siteData['date2'];?></td>
                                    <td><?php echo $siteData['user2'];?></td>
                                    <td><?php echo $siteData['date3'];?></td>
                                    <td><?php echo $siteData['user3'];?></td>
                                    <td><?php echo $siteData['date5'];?></td>
                                    <td><?php echo $siteData['user5'];?></td>
                                  </tr><?php endforeach;?>
                                  
<!--			<tr>					
				<th id="f1" colspan="47"></th>
			</tr>-->
<!--			<tr style="background-color:#ededed">	
				<th> </th>
				<th id="g1" colspan="46"> Bank In </th>
			</tr>-->
                            </tbody>
			</table>			
		</div>
	</div>


</div><br><br>	


