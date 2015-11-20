<script type="text/javascript">
	
var monthChange = function()
{
	var month = $("#month").val();
	var year = $("#year").val();

	$.ajax({url: pim.base_url+'ajax/article/lists/'+<?php echo $activityId;?>+'?month='+month+'&year='+year}).done(function(response)
	{
		$("#ajaxModal").html(response);
	});
};

var chooseArticle = function(id)
{
	if(!confirm('Do you want to link this activity with this article?'))
		return;

	var type = $("#articleActivityType").val();

	if(type == '')
		return alert('Please choose either report or reference.');

	var data = {
		articleID : id,
		type : type
	};

	$.ajax({type: 'POST', url: pim.base_url+'ajax/article/lists/'+<?php echo $activityId;?>, data: data}).done(function()
	{
		window.location.reload();
	});
};

</script>
<style type="text/css">
	
.row-article
{
	cursor: pointer;
}

.row-article:hover td
{
	background: #dcf8de !important;
}

</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title">
				<span class='fa fa-anchor'></span>	
				<span>Link to article as a <?php echo form::select('articleActivityType', array(1 => 'Report', 2 => 'Reference'));?></span>
			<span style='margin-right:20px;' class="pull-right">
			<?php echo date('M-Y', strtotime($year.'-'.$month));?>
			</span>
			</h4>
		</div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class="mb-header">List of articles in <?php echo form::select('month', model::load('helper')->monthYear('month'), 'onchange="monthChange();";', $month);?>
			 <?php echo form::select('year', model::load('helper')->monthYear('year'), 'onchange="monthChange();";', $year);?>
			</div>
			<div class='mb-content'>
				<div class="row">
					<div class="col-sm-12">
						<div class='table-responsive'>
							<table class="table table-striped b-t b-light">
								<tr>
									<th width="20px">No.</th>
									<th>Activity Name</th>
									<th width='150px'>Date Published</th>
								</tr>
								<?php $no = 1;?>
								<?php if(count($articles) > 0):?>
								<?php foreach($articles as $article):?>
								<tr class='row-article' onclick="chooseArticle(<?php echo $article['articleID'];?>);">
									<td><?php echo $no++;?>.</td>
									<td><?php echo $article['articleName'];?></td>
									<td style="text-align: center;"><?php echo date('d F', strtotime($article['articlePublishedDate']));?></td>
								</tr>
								<?php endforeach;?>
								<?php else:?>
								<tr>
									<td colspan="3">No article found for this month.</td>
								</tr>
								<?php endif;?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>