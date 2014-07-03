<link rel="stylesheet" href="<?php echo url::asset("_scale/js/datepicker/datepicker.css"); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo url::asset("_scale/js/datepicker/bootstrap-datepicker.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/tokenizer.css"); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo url::asset("backend/tools/bootstrap-tokenizer/bootstrap-tokenizer.css"); ?>" type="text/css" />
<style type="text/css">

#table-slider td
{
	position: relative;
}

#table-slider img
{
	border:1px solid #d6ddeb;
}

.articleName
{
	font-weight: bold;
}

.general-label
{
	position:absolute;
	right:0px;
	opacity: 0.5;
}

.tags-article{
	width:200px !important;
	
}

</style>
<h3 class="m-b-xs text-black">
<a href='info'>My Blog Posts</a>
</h3>
<div class='well well-sm'>
List of all your approved and pending blog articles.
</div>
	<?php echo flash::data();?>
<section class="panel panel-default">
<div class="table-responsive">
	<table id='table-slider' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th class="th-sortable" data-toggle="class">Title
			</th>
			<th>Tag(s)</th>
			<th>Date to be Published</th>
			<th colspan="2"></th>
		</tr>
	</thead>
	<tbody>
		<?php
	 if($article):
		$requestdata = model::load('site/request')->replaceWithRequestData('article.update', array_keys($article));
		/*if($data){
			array_merge($article,$data);
		}echo '<pre>';print_r($data);die;*/
		$no	= pagination::recordNo();
		foreach($article as $row):
		
		if(isset($requestdata[$row['articleID']])){
			$row = array_merge($row,$requestdata[$row['articleID']]);
		}

		$active		= $row['articleStatus'] == 1?"active":"";
		$opacity	= $row['articleStatus'] == 0 || isset($requestdata[$row['articleID']])?"style='opacity:0.5;'":"";
		$href		= ($row['articleStatus'] == 1?"deactivate":"activate")."?".$row['articleID'];
		$row['articleStatus'] = $row['articleStatus'] == 1 && isset($requestdata[$row['articleID']])?4:$row['articleStatus'];
		$href		= "?toggle=".$row['articleID'];
			?>
		<tr <?php echo $opacity;?>>
			<td><?php echo $no++;?>.</td>
			<td width='40%'>
			<div class='articleName'><?php echo $row['articleName'];?></div>
			</td>
			<td>
				<div class="tokenizer tags-article" style="border: 0px;background-color: transparent;">
					<div>
						<ul class="token">
							<?php
								if($articleTags[$row['articleID']]):
								foreach($articleTags[$row['articleID']] as $tag):
							?>
							<li><span class="label"><?php echo $tag['articleTagName']; ?></span></li>
							<?php
								endforeach;
								endif;
							?>
							<li><span class="input" contenteditable="false"></span></li>
						</ul>
					</div>
				</div>
			</td>
			<td><?php echo date("d-m-Y",strtotime($row['articlePublishedDate']));?></td>
			<td width="29">
			
			<a><?php echo model::load('template/icon')->status($row['articleStatus']); ?></a>
			</td>
			<td width="29"><?php if($row['articleStatus'] != 2):?>
				<a href='<?php echo url::base("site/editArticle/".$row['articleID']);?>' class='fa fa-edit'></a>
			<?php endif; ?></td>
		</tr>
		<?php 
		endforeach;
		?>
		<?php
		else:?>
			<tr>
				<td align="center" colspan='4'>No blog was posted yet.</td>
			</tr>
		<?php endif;?>
	</tbody>
	</table>
</div>
<footer class='panel-footer'>
	<div class="row">
		<div class="col-sm-12">
			<?php echo pagination::link();?>
		</div>
	</div>
</footer>
</section>