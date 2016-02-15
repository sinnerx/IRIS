<style type="text/css">
.mb-tab.active{
		font-weight: bold;
	}
	
	table#table tbody tr td:not(#null){
	 -webkit-transition: all 0.4s ease-out;
   -moz-transition: all 0.4s ease-out;
   -ms-transition: all 0.4s ease-out;
   -o-transition: all 0.4s ease-out;
   transition: all 0.4s ease-out;		
	}
	
	table#table tbody tr:hover td:not(#null)
	{
		cursor:pointer;
		background-color:#f5f5f5;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		pim.ajax.urlify('.container-pg a', '#ajaxModal');
		pim.ajax.urlify('.mb-tab', '#ajaxModal');
		start();
		function start() {
		    $("#one").fadeOut(500).fadeIn(500, start);
		}
	});

	function tr_clicked(element)
	{
		var r = confirm("Are you sure to select this activity?");
    	if (r == true) {
			document.getElementById('activityID').value = element.getElementsByTagName('input')['activityID'].value;
			document.getElementById('activityArticleType').value = element.getElementsByTagName('input')['activityArticleType'].value;
			document.getElementById('activity').innerHTML = '<i class="fa fa-link"></i> '+element.getElementsByTagName('input')['activityName'].value+' &nbsp;&nbsp;&nbsp;<a><i style="cursor: pointer;" onclick="removeActivity();" class="fa fa-times text-danger text"></i></a>&nbsp;&nbsp;&nbsp;<span class="caret"></span>';
        	return true;
    	} else {
        	window.event.cancelBubble = true;
    	}
	}
</script>
<form>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><a id="one">☝</a>	
					<span style='font-size:11px;'> Select only one activity.</span>
			<span style='margin-right:20px;' class="pull-right"><?php if($year && $month){ echo date('M-Y',strtotime($year.'-'.$month)); } ?></span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class='mb-header'>
				<a class='mb-tab active'>Incoming Activity</a> |
				<?php if($ref!=1){$ref = 0;} ?>
				<a href='<?php echo url::base('ajax/activity/previous/'.$articleID.'?ref='.$ref); ?>' class='mb-tab'>Previous Activity</a>
			</div>
			<div class='mb-content'>
				<div class='ajxgal-new active'> <!-- through adding new photo -->
					<div class='row'>
						<div class='col-sm-12' style="margin:auto;">
							<table id='table' class="table table-striped b-t b-light">
									<tr>
										<th style="text-align:center;vertical-align:middle;" width="20">No.</th>
										<th style="text-align:center;vertical-align:middle;">Title</th>
										<th style="text-align:center;vertical-align:middle;" colspan="2">Date</th>
									</tr>
									<?php
										if($result):
											$count = 1;
											foreach ($result as $row):
									?>
									<tr onclick="tr_clicked(this);" data-dismiss="modal">
										<td style="text-align:center;vertical-align:middle;"><?php echo $count; ?></td>
										<td style="text-align:center;vertical-align:middle;"><?php echo $row['activityName']; ?></td>
										<td style="text-align:center;vertical-align:middle;"><?php echo '<b>From</b>&nbsp;&nbsp;&nbsp;'.date('jS-M-Y',strtotime($row['activityStartDate'])); ?></td>
										<td style="text-align:center;vertical-align:middle;"><?php echo '<b>To</b>&nbsp;&nbsp;&nbsp;'.date('jS-M-Y',strtotime($row['activityEndDate'])); ?></td>
										<input type="hidden" name="activityID" value="<?php echo $row['activityID']; ?>" />
										<input type="hidden" name="activityArticleType" value="<?php if($ref == 1): ?>2<?php else: ?>1<?php endif; ?>" />
										<input type="hidden" name="activityName" value="<?php echo $row['activityName']; ?>" />
									</tr>
									<?php
											$count++;
											endforeach;
										else:
									?>
									<tr>
										<td id="null" align="center" colspan='4'>No activity on this month.</td>
									</tr>
									<?php
										endif;
									?>
							</table>
							<div class="container-pg">
								<ul class="pagination pagination-lg pull-right">
							<?php 
								$url = url::base('ajax/activity/'.$type);
								if($previous){
									echo '<li><a href="'.$url.'/'.$articleID.'?year='.$previousyear.'&month='.$previousmonth.'"><i class="fa fa-chevron-left"></i></a></li>';
								} 
								if($next){ 
									echo '<li><a href="'.$url.'/'.$articleID.'?year='.$nextyear.'&month='.$nextmonth.'"><i class="fa fa-chevron-right"></i></a></li>';
								} 
							?>
								</ul>
								<!-- <button onclick="selected();" data-dismiss="modal" class='btn btn-ms btn-default pull-left'>Select</button>&nbsp;&nbsp;&nbsp;
								<button data-dismiss="modal" class='btn btn-ms btn-default'>Cancel</button> -->
							</div>
						</div>
					</div>
				</div>
				<div class='ajxgal-photos'> <!-- no album photo -->
				</div>
				<div class='ajxgal-albums'> <!-- By albums -->
				</div>
			</div>
		</div>
	</div>
</div>
</form>