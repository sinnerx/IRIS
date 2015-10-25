<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title">
				<span class='fa fa-anchor'></span>	
				<span>Select article as the report.</span>
			<span style='margin-right:20px;' class="pull-right"><?php if($year && $month){ echo date('M-Y',strtotime($year.'-'.$month)); } ?></span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class="mb-header">List of articles</div>
			<div class='mb-content'>
				<div class="row">
					<div class="col-sm-12">
						<div class='table-responsive'>
							<table class="table table-striped b-t b-light">
								<tr>
									<th width="20px">No.</th>
									<th>Activity Name</th>
								</tr>
								<?php foreach($articles as $article):?>
								<tr>
									<td></td>
									<td><?php echo $article['articleName'];?></td>
								</tr>
								<?php endforeach;?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>