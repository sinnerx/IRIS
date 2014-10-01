<div class='request-info'>
New Forum Category
</div>
<div class='table-responsive'>
<table class='table'>
	<tr>
		<td width='150px'>Title</td><td><?php echo $row['forumCategoryTitle'];?></td>
	</tr>
	<tr>
		<td>Description</td><td><?php echo $row['forumCategoryDescription'];?></td>
	</tr>
	<tr>
		<td>Access Level</td><td><?php echo model::load("forum/category")->accessLevel($row['forumCategoryAccess']);?>
	</tr>
</table>
</div>