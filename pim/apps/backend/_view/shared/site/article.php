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

</style>
<h3 class="m-b-xs text-black">
<?php if(session::get("userLevel") == 99):?>
<a href='info'>General Blog</a>
<?php else:?>
<a href='info'>My Blog Posts</a>
<?php endif;?>
</h3>
<div class='well well-sm'>
<?php if(session::get("userLevel") == 99):?>
List of requested blog posts on all Pi1M sites. Only root admin can manage this section.
<?php else:?>
Listing all your request blogs here.
<?php endif;?>
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
			<th width="60"></th>
		</tr>
	</thead>
	<tbody>
		<?php if($article):
		$requestdata = model::load('site/request')->replaceWithRequestData('article.update', array_keys($article));
		$no	= pagination::recordNo();
		foreach($article as $row):
		if(isset($requestdata[$row['articleID']])){
			$row = array_merge($row,$requestdata[$row['articleID']]);
			$articleTags = array();
			$row['articleTags'] = strtok($row['articleTags'],',');

			while ($row['articleTags'] != false)
    		{
				array_push($articleTags,array('articleTagName'=>$row['articleTags']));
				$row['articleTags'] = strtok(",");
			}
			$row['articleTags'] = $articleTags;
		}
		$active		= $row['articleStatus'] == 1?"active":"";
		$opacity	= $row['articleStatus'] == 0 || isset($requestdata[$row['articleID']])?"style='opacity:0.5;'":"";
		$href		= ($row['articleStatus'] == 1?"deactivate":"activate")."?".$row['articleID'];
		$href		= "?toggle=".$row['articleID'];
			?>
		<tr <?php echo $opacity;?>>
			<td><?php echo $no++;?>.</td>
			<td width='40%'>
			<div class='articleName'><?php echo $row['articleName'];?></div>
			</td>
			<td>
				<div class="tokenizer" style="width: 274px;">
					<div>
						<ul class="token">
							<?php
								foreach($row['articleTags'] as $tag):
							?>
							<li><span class="label"><?php echo $tag['articleTagName']; ?><i class="icon-remove icon-white"></i></span></li>
							<?php
								endforeach;
							?>
							<li><span class="input" contenteditable="false"></span></li>
						</ul>
					</div>
				</div>
			</td>
			<td><?php echo date("d-m-Y",strtotime($row['articlePublishedDate']));?></td>
			<td>
			<?php if($row['siteID'] != 0 || session::get("userLevel") == 99):?>
				<?php if($row['articleStatus'] != 2):?>
				<a href='<?php echo url::base("site/editArticle/".$row['articleID']);?>' class='fa fa-edit'></a>
				<?php endif; ?>
				<?php if(session::get("userLevel") == 99):?>
					<a href="<?php echo $href;?>" class="<?php echo $active;?>" ><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
				<?php else: ?>
					<a><?php echo model::load('template/icon')->status($row['articleStatus']); ?></a>
				<?php endif; ?>
			<?php endif;?>
			</td>
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