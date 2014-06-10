<style type="text/css">
	.mb-tab.active{
		font-weight: bold;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		pim.ajax.urlify('.container-pg a', '#ajaxModal');
		pim.ajax.urlify('.mb-tab', '#ajaxModal');
	});
	function selected()
	{
		<?php
			if($result):
				foreach ($result as $row):
		?>
		if(document.getElementById('activityID<?php echo $row["activityID"] ?>').checked){
			document.getElementById('activityID').value = <?php echo $row["activityID"] ?>;
			document.getElementById('activityArticleType').value = <?php if($ref == 1): ?>2<?php else: ?>1<?php endif; ?>;
			document.getElementById('activity').innerHTML = '<i class="fa fa-link"></i> <?php echo $row["activityName"]; ?> &nbsp;&nbsp;&nbsp;<a><i style="cursor: pointer;" onclick="removeActivity();" class="fa fa-times text-danger text"></i></a>&nbsp;&nbsp;&nbsp;<span class="caret"></span>';
		}
		<?php
				endforeach;
			endif;
		?>
	}
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">✘</button>
			<h4 class="modal-title">☮
			<span style='font-size:11px;'> Select one from existed activities.</span>
			<span style='margin-right:20px;' class="pull-right"><?php if($year && $month){ echo date('M-Y',strtotime($year.'-'.$month)); } ?></span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class='mb-header'>
				<?php if($ref == 1): ?><a href='<?php echo url::base('ajax/activity/incoming'); ?>' class='mb-tab'>Incoming Activity</a> |<?php endif; ?> 
				<a class='mb-tab active'>Previous Activity</a>
			</div>
			<div class='mb-content'>
				<div class='ajxgal-new active'> <!-- through adding new photo -->
					<div class='row'>
						<div class='col-sm-12' style="margin:auto;">
							<table id='table' class="table table-striped b-t b-light">
								<thead>
									<tr>
										<th style="text-align:center;vertical-align:middle;" width="20">No.</th>
										<th style="text-align:center;vertical-align:middle;" class="th-sortable" data-toggle="class">Title</th>
										<th style="text-align:center;vertical-align:middle;" colspan="2">Date</th>
										<th width="20"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										if($result):
											$count = 1;
											foreach ($result as $row):
									?>
									<tr>
										<td style="text-align:center;vertical-align:middle;"><?php echo $count; ?></td>
										<td style="text-align:center;vertical-align:middle;"><?php echo $row['activityName']; ?></td>
										<td style="text-align:center;vertical-align:middle;"><?php echo '<b>From</b>&nbsp;&nbsp;&nbsp;'.date('jS-M-Y',strtotime($row['activityStartDate'])); ?></td>
										<td style="text-align:center;vertical-align:middle;"><?php echo '<b>To</b>&nbsp;&nbsp;&nbsp;'.date('jS-M-Y',strtotime($row['activityEndDate'])); ?></td>
										<td style="text-align:center;vertical-align:middle;"><input type="radio" name="activity" id="activityID<?php echo $row['activityID']; ?>" /></td>
									</tr>
									<?php
											$count++;
											endforeach;
										else:
									?>
									<tr>
										<td align="center" colspan='4'>No activity on this month.</td>
									</tr>
									<?php
										endif;
									?>
								</tbody>
							</table>
							<div class="container-pg">
								<ul class="pagination pagination-lg pull-right">
							<?php 
								$url = url::base('ajax/activity/'.$type);
								if($previous){
									echo '<li><a href="'.$url.'?year='.$previousyear.'&month='.$previousmonth.'&ref='.$ref.'"><i class="fa fa-chevron-left"></i></a></li>';
								} 
								if($next){ 
									echo '<li><a href="'.$url.'?year='.$nextyear.'&month='.$nextmonth.'&ref='.$ref.'"><i class="fa fa-chevron-right"></i></a></li>';
								} 
							?>
								</ul>
								<button onclick="selected();" data-dismiss="modal" class='btn btn-ms btn-default pull-left'>Select</button>&nbsp;&nbsp;&nbsp;
								<button data-dismiss="modal" class='btn btn-ms btn-default'>Cancel</button>
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