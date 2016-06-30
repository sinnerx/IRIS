<style type="text/css">
	
.category-list ul
{
	padding-left:10px;
	margin:0px;
}

</style>
<?php
$articleID = $row['articleID'];
$articleEditUrl = url::base("cluster/editArticle/".$articleID);
?>
<div class='request-info'>
	New Article : <u><?php echo $row['articleName'];?></u>
</div>
<script>
$( "#btnPanel" ).append( "<a href='<?php echo $articleEditUrl;?>' class='btn-approval pull-right fa fa-pencil-square-o'></a>" );
</script>
<div class='row'>
	<div class='col-sm-12'>
		<div class='table-responsive'>
			<table class='table'>
				<tr>
					<th width="200px">To be published after</th><td colspan='3'><?php echo date("d M Y",strtotime($row['articlePublishedDate']));?></td>
				</tr>
				<tr>
					<td colspan='3' rowspan="2">
						<?php echo $row['articleText'];?>
					</td>
					<td class='category-list' style='width:20%;'>
						<b>Categories</b><br>
						<?php
						if($row['article_category'])
						{
							echo "<ul>";
							foreach($row['res_category'] as $row_cat)
							{
								$catID	= $row_cat['categoryID'];

								if(!isset($row['article_category'][$catID]))
									continue;

								echo "<li>".$row['article_category'][$catID]['categoryName'];
								if(isset($row_cat['child']))
								{
									echo "<ul>";
									foreach($row_cat['child'] as $row_child)
									{
										$childCatID	= $row_child['categoryID'];
										if(!isset($row['article_category'][$childCatID]))
											continue;

										echo "<li>".$row['article_category'][$childCatID]['categoryName']."</li>";
									}
									echo "</ul>";
								}

								echo "</li>";
							}
							echo "</ul>";
						}
						else
						{
							echo "-";
						}
						;?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>Tags</b><br>
						<?php
						if($row['article_tag'])
						{
							$tag	= Array();
							foreach($row['article_tag'] as $row_tag)
							{
								$tag[]	= $row_tag['articleTagName'];
							}

							echo implode(", ",$tag);
						}
						else
						{
							echo "-";
						}
						?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>