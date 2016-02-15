<style type="text/css">
	
.table tr td:last-child
{
	text-align: right
}

</style>
<script type="text/javascript">
	
$(document).ready(function()
{
	pim.ajax.urlify("#category-list .fa","#catDetail");
});

</script>
<h3 class='m-b-xs text-black'>
Article Category
</h3>
<div class='well well-sm'>
List of category.
</div>
<div class='row'>
	<div class='col-sm-6'>
		<section class='panel panel-default'>
			<header>
				
			</header>
			<div class='table-responsive' id='category-list'>
				<table class='table'>
					<tr>
						<th width="15px">No.</th>
						<th>Category Name</th>
						<th width="58px" style='text-align:right;'><a href='<?php echo url::base("article/category_add");?>' class='fa fa-plus'></a></th>
					</tr>
					<?php
					if($res_category):
					$no = 1;
					foreach($res_category as $row)
					{

					?>
					<tr>
						<td><?php echo $no;?>.</td>
						<td id='catName<?php echo $row['categoryID'];?>'><?php echo $row['categoryName'];?></td>
						<td>
							<a href='<?php echo url::base("article/category_addchild/$row[categoryID]");?>' class='fa fa-plus'></a>
							<a href='<?php echo url::base("article/category_edit/$row[categoryID]");?>' class='fa fa-edit'></a>
						</td>
					</tr>
					<?php
					## loop anak-anak.
					if(isset($row['child'])):
						$noo = 1;
						foreach($row['child'] as $row_child)
						{
							?>
							<tr>
								<td style='text-align:right;'><?php echo $no.".".$noo++;?></td>
								<td id='catName<?php echo $row['categoryID'];?>'><?php echo $row_child['categoryName'];?></td>
								<td><a href='<?php echo url::base("article/category_edit/$row_child[categoryID]");?>' class='fa fa-edit'></a></td>
							</tr>
							<?php
						} 
					endif;?>	
					<?php
					$no++;
					}
					else:?>


					<?php
					endif;?>
				</table>
			</div>
		</section>
	</div>
	<div class='col-sm-6' id='catDetail'>

	</div>
</div>